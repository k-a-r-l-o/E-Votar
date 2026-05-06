<?php

namespace App\Livewire\Dashboard;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\User;
use Exception;
use Livewire\Component;

class AdminDashboard extends Component
{
    protected $listeners = [
        'candidate-created' => '$refresh',
        'echo:election-status,ElectionStatus' => '$refresh',
        'global-election-updated' => 'handleGlobalElectionUpdate'
    ];
    public $candidates = [];
    public $search = '';
    public $selectedElection;
    public $elections;
    public $councils;
    public $positions;
    public $latestElection;
    public $hasStudentCouncilPositions;
    public $hasLocalCouncilPositions;

    public $totalVoters, $totalVoterVoted, $totalCandidates, $totalPositions;

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $this->selectedElection = session('selectedElection');
        if ($this->selectedElection) {
            $this->handleGlobalElectionUpdate($this->selectedElection);
        }
    }

    public function handleGlobalElectionUpdate($electionId): void
    {
        $this->selectedElection = $electionId;
        $this->fetchElection();
        $this->fetchCandidates();
        $this->fetchVoterTally();
    }

    public function updatedSearch(): void
    {
        $this->fetchCandidates();
        $this->fetchVoterTally();
    }

    public function fetchCandidates(): void
    {
        if ($this->selectedElection){
            $query = Candidate::with(['users.program.council', 'elections', 'election_positions.position.electionType'])->where('election_id', $this->selectedElection);

            if ($this->search) {
                $query->whereHas('users', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            }

//        if ($this->selectedElection) {
//            $query->whereHas('elections', function ($q) {
//                $q->where('id', $this->selectedElection);
//            });
//        }

//        if ($this->filter) {
//            $query->whereHas('elections.election_type', function ($q) {
//                $q->where('name', $this->filter);
//            });
//        }

            $this->candidates = $query->get();

            $councilsWithCandidates = collect();
            foreach ($this->candidates as $candidate) {
                if ($candidate->users && $candidate->users->program && $candidate->users->program->council) {
                    $councilsWithCandidates->push($candidate->users->program->council);
                }
            }

            // Remove duplicate councils
            $councilsWithCandidates = $councilsWithCandidates->unique('id');

            // Check if there are any student council candidates
            $hasStudentCouncilCandidates = $this->candidates->contains(function ($candidate) {
                return $candidate->election_positions->position->electionType->name === 'Student Council Election';
            });

            // If there are student council candidates, include the student council
            if ($hasStudentCouncilCandidates) {
                $studentCouncil = (object)[
                    'id' => null, // Use null or a unique identifier for the student council
                    'name' => 'Tagum Student Council',
                    'election_type' => (object)['name' => 'Student Council Election'],
                ];

                // Add the student council to the list of councils
                $councilsWithCandidates->prepend($studentCouncil);
            }

            // Store the councils in a property for use in the view
            $this->councils = $councilsWithCandidates;
        }

    }

    public function fetchElection(): void
    {
        if (!$this->selectedElection) {
            $this->latestElection = null;
            $this->hasStudentCouncilPositions = false;
            $this->hasLocalCouncilPositions = false;
            $this->positions = collect();
            return;
        }

        $this->latestElection = Election::with('election_type')->find($this->selectedElection);

        if ($this->latestElection) {
            $this->hasStudentCouncilPositions = ElectionPosition::where('election_id', $this->latestElection->id)
                ->whereHas('position.electionType', function ($q) {
                    $q->where('name', 'Student Council Election');
                })
                ->exists();

            $this->hasLocalCouncilPositions = ElectionPosition::where('election_id', $this->latestElection->id)
                ->whereHas('position.electionType', function ($q) {
                    $q->where('name', 'Local Council Election');
                })
                ->exists();
        }

        $this->fetchPositions();
    }

    public function fetchPositions(): void
    {
        if ($this->selectedElection) {
            $this->positions = ElectionPosition::with('position.electionType')
                ->where('election_id', $this->selectedElection)
                ->get()
                ->pluck('position')
                ->unique('id'); // Ensure positions are unique
        } else {
            $this->positions = collect(); // Return an empty collection if no election is selected
        }
    }

    public function fetchVoterTally(): void
    {
        $election = Election::find($this->selectedElection);
        if ($election) {
            $this->totalVoters = User::where('campus_id', $election->campus_id)
                ->whereDoesntHave('electionExcludedVoters', function ($query) use ($election) {
                    $query->where('election_id', $election->id);
                })
                ->count();

            $this->totalVoterVoted = User::where('campus_id', $election->campus_id)
                ->whereDoesntHave('electionExcludedVoters', function ($query) use ($election) {
                    $query->where('election_id', $election->id);
                })
                ->where(function ($query) use ($election) {
                    $query->whereHas('votes', function ($subQuery) use ($election) {
                        $subQuery->where('election_id', $election->id);
                    })
                        ->orWhereHas('abstained', function ($subQuery) use ($election) {
                            $subQuery->where('election_id', $election->id);
                        });
                })
                ->count();

            $this->totalCandidates = Candidate::where('election_id', $election->id)->count();
            $this->totalPositions = ElectionPosition::where('election_id', $election->id)->count();
        }
    }

    public function render()
    {
        return view('evotar.livewire.dashboard.admin-dashboard', [
            'candidates' => $this->candidates,
            'totalVoters' => $this->totalVoters,
            'totalVoterVoted' => $this->totalVoterVoted,
            'totalCandidates' => $this->totalCandidates,
            'totalPositions' => $this->totalPositions,
            'selectedElection' => $this->selectedElection,
            'latestElection' => $this->latestElection,
            'councils' => $this->councils,
            'positions'=> $this->positions,
        ]);
    }
}
