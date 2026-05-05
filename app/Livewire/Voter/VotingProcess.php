<?php

namespace App\Livewire\Voter;

use App\Events\VoteTallyUpdated;
use App\Helpers\EncryptionHelper;
use App\Helpers\SteganographyHelper;
use App\Models\AbstainVote;
use App\Models\Candidate;
use App\Models\Election;
use App\Models\FeedbackToken;
use App\Models\Vote;
use App\Models\VoterEncodeVote;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class VotingProcess extends Component
{
    public $election;
    public $voter;
    public $positionsWithCandidates = [];
    public $currentStage;
    public $studentCouncilPositions = [];
    public $localCouncilPositions = [];
    public $showProceedButton = false;

    protected $listeners = ['submitVotes', 'echo:vote-tally,VoteTallyUpdated' => '$refresh',];
    public $selectedCandidates = [];
    public $showSummaryModal = false;
    public $showDuplicateErrorModal = false;
    public $duplicateError = '';
    public $abstainSelections = [];


    public function mount($slug)
    {

        $this->voter = auth()->user();
        $this->election = Election::where('slug', $slug)->first();

        if (!$this->election) {
            session()->flash('error', 'Election not found.');
            return redirect()->route('voter-election-redirect');
        }

        $this->positionsWithCandidates = $this->election->positions()
            ->with([
                'electionPositions.candidates' => function ($q) {
                    $q->where('election_id', $this->election->id)
                        ->with(['users.program', 'users.programMajor']);
                }
            ])
            ->get();

        // Categorize the positions
        $this->studentCouncilPositions = $this->positionsWithCandidates->filter(function ($position) {
            return $position->electionType->name === 'Student Council Election';
        });

        $this->localCouncilPositions = $this->positionsWithCandidates->filter(function ($position) {
            return $position->electionType->name === 'Local Council Election';
        });

        // Determine initial stage and proceed button
        if ($this->election->election_type->name === 'Student and Local Council Election') {
            $this->currentStage = 'student';
            $this->showProceedButton = $this->studentCouncilPositions->isNotEmpty() && $this->localCouncilPositions->isNotEmpty();
        } elseif ($this->election->election_type->name === 'Student Council Election') {
            $this->currentStage = 'student';
        } elseif ($this->election->election_type->name === 'Local Council Election') {
            $this->currentStage = 'local';
        }
        return 403;
    }

    public function proceedToLocalCouncilElection(): void
    {
        if ($this->currentStage === 'student' && $this->showProceedButton) {
            $this->currentStage = 'local';
        }
    }

    public function goBackToStudentCouncilElection(): void
    {
        if ($this->currentStage === 'local') {
            $this->currentStage = 'student';
        }
    }

    public function decrypt($value)
    {
        try {
            return Crypt::decrypt($value);
        } catch (\Exception $e) {
            return $value; // Return original if decryption fails
        }
    }
    public function decryptForFrontend($value)
    {
        return $this->decrypt($value);
    }

    public function decryptUserData($userData): ?array
    {
        if (!$userData) return null;

        return [
            'first_name' => $userData['first_name'] ? $this->decrypt($userData['first_name']) : '',
            'last_name' => $userData['last_name'] ? $this->decrypt($userData['last_name']) : '',
            'middle_initial' => $userData['middle_initial'] ? $this->decrypt($userData['middle_initial']) : '',
            'extension' => $userData['extension'] ? $this->decrypt($userData['extension']) : ''
        ];
    }

    public function showSummary(): void
    {
        if ($this->hasDuplicateVotes()) {
            $this->showDuplicateErrorModal = true;
            $this->duplicateError = 'Duplicate votes detected. Please review your selections.';
            return;
        }

        $this->showSummaryModal = true;
    }

    public function addSelections($selections): void
    {
        foreach ($selections as $key => $value) {
            $this->selectedCandidates[$key] = $value;
        }
    }

    public function selectCandidate($positionId, $candidateId): void
    {
        $this->selectedCandidates[$positionId] = $candidateId;

        if (isset($this->abstainSelections[$positionId])) {
            unset($this->abstainSelections[$positionId]);
        }
    }

    public function toggleAbstain($positionId): void
    {
        if (isset($this->abstainSelections[$positionId])) {
            unset($this->abstainSelections[$positionId]);
        } else {
            $this->abstainSelections[$positionId] = true;
            // If abstaining, remove any candidate selection for this position
            if (isset($this->selectedCandidates[$positionId])) {
                unset($this->selectedCandidates[$positionId]);
            }
        }
    }

    private function hasDuplicateVotes(): bool
    {
        $votesByPosition = [];

        foreach ($this->selectedCandidates as $key => $candidateId) {

            if ($candidateId === 'abstain') {
                continue;
            }

            $positionId = explode('_', str_replace('selected_candidate_', '', $key))[0];

            if (!isset($votesByPosition[$positionId])) {
                $votesByPosition[$positionId] = [];
            }

            $votesByPosition[$positionId][] = $candidateId;
        }

        foreach ($votesByPosition as $positionId => $candidateIds) {
            if (count($candidateIds) !== count(array_unique($candidateIds))) {
                return true;
            }
        }

        return false; // No duplicates
    }

    /**
     * @throws Exception
     */
    public function submitVotes()
    {
        // Check if user has already voted for this election
        $alreadyVoted = Vote::where('user_id', auth()->id())
            ->where('election_id', $this->election->id)
            ->exists() ||
            AbstainVote::where('user_id', auth()->id())
                ->where('election_id', $this->election->id)
                ->exists();

        if ($alreadyVoted) {
            return $this->redirect(route('voter.voting.confirm'));
        }

        // Check for duplicates
        if ($this->hasDuplicateVotes()) {
            $this->showDuplicateErrorModal = true;
            $this->duplicateError = 'Duplicate votes detected. Please review your selections.';
            return;
        }

        $encryptedVoteDetails = [];
        $abstentions = [];

        foreach ($this->selectedCandidates as $key => $candidateId) {
            [$positionId, $slot] = explode('_', str_replace('selected_candidate_', '', $key));

            if ($candidateId === 'abstain') {
                // Encryptable abstention
                $positionName = optional($this->positionsWithCandidates->firstWhere('id', $positionId))->name;
                $abstentions[] = [
                    'position_id' => $positionId,
                    'position_name' => $positionName ?? 'N/A',
                ];

                // DB record
                AbstainVote::create([
                    'user_id' => auth()->id(),
                    'election_id' => $this->election->id,
                    'position_id' => $positionId,
                    'created_at' => now(),
                ]);

                continue; // Skip candidate logic
            }

            $candidate = Candidate::with(['users.program', 'users.programMajor', 'partyLists', 'election_positions'])->find($candidateId);

            if ($candidate) {
                $encryptedVoteDetails[] = [
                    'candidate_id' => $candidate->id,
                    'candidate_name' => $candidate->users->first_name . ' ' . $candidate->users->last_name,
                    'position_id' => $positionId,
                    'position_name' => $candidate->election_positions->position->name ?? 'N/A',
                    'party_list' => $candidate->partyLists->name ?? 'Independent',
                    'program' => $candidate->users->program->name ?? 'N/A',
                    'major' => $candidate->users->programMajor->name ?? 'N/A',
                    'vote_slot' => $slot,
                ];

                Vote::create([
                    'user_id' => auth()->id(),
                    'candidate_id' => $candidate->id,
                    'election_id' => $this->election->id,
                    'election_type_id' => $this->election->election_type->id,
                    'position_id' => $positionId,
                    'vote_slot' => $slot,
                ]);
            }
        }

        // Include abstentions not already processed (in case they come from a different source)
        foreach ($this->abstainSelections as $positionId => $_) {
            $alreadyAdded = collect($abstentions)->contains('position_id', $positionId);
            if (!$alreadyAdded) {
                $positionName = optional($this->positionsWithCandidates->firstWhere('id', $positionId))->name;
                $abstentions[] = [
                    'position_id' => $positionId,
                    'position_name' => $positionName ?? 'N/A',
                ];

                AbstainVote::create([
                    'user_id' => auth()->id(),
                    'election_id' => $this->election->id,
                    'position_id' => $positionId,
                    'created_at' => now(),
                ]);
            }
        }

        $voteData = [
            'election_id' => $this->election->id,
            'election_name' => $this->election->name,
            'voter_id' => auth()->id(),
            'voter_name' => auth()->user()->first_name.' '.auth()->user()->last_name,
            'voter_program' => auth()->user()->program->name ?? 'N/A',
            'voter_major' => auth()->user()->programMajor->name ?? 'N/A',
            'votes' => $encryptedVoteDetails,
            'abstentions' => $abstentions,
            'timestamp' => now()->toDateTimeString(),
        ];

        // Encode the vote data as JSON
        $jsonData = json_encode($voteData);

        // Encrypt the JSON data
        EncryptionHelper::setKey(config('app.stegano_secret_key'));
        $encryptedData = EncryptionHelper::encrypt($jsonData);

        try {
            // Ensure output directory exists
            $encodedVotesDir = storage_path('app/public/encoded_votes');
            if (!file_exists($encodedVotesDir)) {
                mkdir($encodedVotesDir, 0775, true);
            }

            // Encode the encrypted data into an image
            $imagePath = storage_path('app/public/'.$this->election->image_path);

            if (empty($this->election->image_path) || !file_exists($imagePath)) {
                throw new Exception("The election does not have a valid banner image. Please contact an admin.");
            }

            $outputFileName = auth()->user()->first_name.'_'.auth()->user()->last_name.'_'.$this->election->name.'_vote_'.time().'.png';
            $relativePath = 'encoded_votes/'.$outputFileName;
            $outputPath = storage_path('app/public/'.$relativePath);

            $encodedPath = SteganographyHelper::encode($imagePath, $encryptedData, $outputPath);

            // Save encoded vote record
            VoterEncodeVote::create([
                'user_id' => auth()->id(),
                'election_id' => $this->election->id,
                'encrypted_data' => $encryptedData,
                'encoded_image_path' => $relativePath,
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            Log::error("Steganography failed: ".$e->getMessage());

            VoterEncodeVote::create([
                'user_id' => auth()->id(),
                'election_id' => $this->election->id,
                'encrypted_data' => $encryptedData,
                'encoded_image_path' => '',
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            session()->flash('error', 'Vote submission failed during encoding: '.$e->getMessage());
            return;
        }

        $feedbackCode = Str::uuid(); // or use Str::random(16) for shorter code

        // Save the feedback code for the user and election
        FeedbackToken::create([
            'user_id' => auth()->id(),
            'election_id' => $this->election->id,
            'token' => $feedbackCode,
        ]);

        // Reset selections and show success message
        event(new \App\Events\VoteTallyUpdated());
        $this->selectedCandidates = [];
        logger()->info("🚀 Dispatching vote-submitted event for election ID: ".$this->election->id);
        $this->dispatch('updateChartData', $this->election->id);
        session()->flash('success', 'Votes submitted successfully. Download your encoded vote receipt.');
        $this->redirect(route('voter.voting.confirm'));
    }

    public function getAbstentionCounts()
    {
        return AbstainVote::where('election_id', $this->election->id)
            ->select('position_id', DB::raw('count(*) as total'))
            ->groupBy('position_id')
            ->pluck('total', 'position_id');
    }


    public function render(): \Illuminate\Contracts\View\View
    {
        $abstentionCounts = $this->getAbstentionCounts();
        // Filter positions based on the current stage
        if ($this->currentStage === 'student') {
            // Student Council Election: No separation by major
            $positionsWithCandidates = $this->studentCouncilPositions->map(function ($position) {
                $position->candidates = $position->electionPositions
                    ->flatMap(function ($electionPosition) {
                        return $electionPosition->candidates
                            ->where('election_id', $this->election->id)
                            ->map(function ($candidate) {
                                $candidate->load('users.program', 'users.programMajor', 'partyLists');
                                return $candidate;
                            });
                    });
                return $position;
            });
        } else {
            // Local Council Election: Apply separation by major only for positions that require it
            $positionsWithCandidates = $this->localCouncilPositions->map(function ($position) {
                $position->candidates = $position->electionPositions
                    ->flatMap(function ($electionPosition) use ($position) {
                        // Fetch council-specific settings for this position
                        $councilPositionSettings = DB::table('council_position_settings')
                            ->where('position_id', $position->id)
                            ->where('council_id', auth()->user()->program->council_id)
                            ->first();

                        \Log::info('Position Id:', ['position id' => $position->id]);

                        // Determine if candidates should be separated by major for this position
                        $separateByMajor = $councilPositionSettings && $councilPositionSettings->separate_by_major;

                        // Fetch candidates for the current election and filter by the voter's program
                        $candidates = $electionPosition->candidates
                            ->where('election_id', $this->election->id)
                            ->filter(function ($candidate) {
                            return $candidate->users->program_id === auth()->user()->program_id;
                        });

                        // If separation by major is required for this position, filter candidates by major
                        if ($separateByMajor) {
                            $candidates = $candidates->filter(function ($candidate) {
                                return $candidate->users->programMajor->id === auth()->user()->programMajor->id;
                            });
                        }
                        \Log::info('Filtered Candidates:', ['candidates' => $candidates]);
                        return $candidates->map(function ($candidate) {
                            $candidate->load('users.program', 'users.programMajor', 'partyLists');
                            return $candidate;
                        });
                    });
                return $position;
            });
        }


        return view('evotar.livewire.voter.voting-process', [
            'election' => $this->election,
            'positions' => $positionsWithCandidates,
            'currentStage' => $this->currentStage,
            'showProceedButton' => $this->showProceedButton,
            'selectedCandidates' => $this->selectedCandidates,
            'showSummaryModal' => $this->showSummaryModal,
            'abstentionCounts' => $abstentionCounts,
            'abstainSelections' => $this->abstainSelections,
        ]);
    }
}
