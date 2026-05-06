<?php
namespace App\Livewire\ManageCandidate;

use App\Exports\CandidateExport;
use App\Exports\ElectionsExport;
use App\Models\Candidate;
use App\Models\Council;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\User;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class ViewCandidate extends Component
{
    protected $listeners = [
        'candidate-created' => '$refresh', 
        'candidate-edited' => '$refresh', 
        'candidate-deleted' => '$refresh',
        'global-election-updated' => 'handleGlobalElectionUpdate'
    ];
    public $candidates = [];
    public $search = '';
    public $selectedElection;
    public $latestElection;
    public $hasStudentCouncilPositions;
    public $hasLocalCouncilPositions;
    public $selectedElectionName;
    public $selectedElectionCampus;

    public $totalVoters;
    public $totalVoterVoted;
    public $hasLocalCouncilCandidate = false;
    public $hasStudentCouncilCandidate = false;
    public $councils;


    public function mount(): void
    {
        $this->selectedElection = session('selectedElection');
        if ($this->selectedElection) {
            $this->handleGlobalElectionUpdate($this->selectedElection);
        }
        $this->councils = Council::all();
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
    }

    public function updatedSelectedElection(): void
    {
        $election = Election::find($this->selectedElection);
        if ($election) {
            $this->selectedElectionName = $election->name;
            $this->selectedElectionCampus = $election->campus;
            
            // Persist the selection to session
            session(['selectedElection' => $this->selectedElection]);

            // Update flags and candidates
            $this->fetchElection();
            $this->fetchCandidates();
            $this->fetchVoterTally();
        }
    }

    public function exportCandidate()
    {
        return Excel::download(new CandidateExport($this->search, null, $this->selectedElection), 'LIST_OF_CANDIDATES.xlsx');
    }

    public function fetchVoterTally(): void
    {
        if (!$this->selectedElection) {
            $this->totalVoters = 0;
            $this->totalVoterVoted = 0;
            return;
        }

        $election = Election::find($this->selectedElection);

        if (!$election) {
            $this->totalVoters = 0;
            $this->totalVoterVoted = 0;
            return;
        }

        $this->totalVoters = User::where('campus_id', $election->campus_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'voter');
            })
            ->whereDoesntHave('electionExcludedVoters', function ($query) use ($election) {
                $query->where('election_id', $election->id);
            })
            ->count();

        $this->totalVoterVoted = User::where('campus_id', $election->campus_id)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'voter');
            })
            ->whereDoesntHave('electionExcludedVoters', function ($query) use ($election) {
                $query->where('election_id', $election->id);
            })
            ->whereHas('votes', function ($query) use ($election) {
                $query->where('election_id', $election->id);
            })
            ->count();
    }

    public function fetchCandidates(): void
    {
        if (!$this->selectedElection) {
            $this->candidates = [];
            $this->hasStudentCouncilCandidate = false;
            $this->hasLocalCouncilCandidate = false;
            return;
        }

        $query = Candidate::with(['users', 'users.program.council', 'elections', 'election_positions.position', 'election_positions.position.electionType'])
            ->withCount('votes')
            ->join('election_positions', 'candidates.election_position_id', '=', 'election_positions.id')
            ->orderBy('election_positions.position_id', 'asc')
            ->select('candidates.*')
            ->where('candidates.election_id', $this->selectedElection);

        if ($this->search) {
            $matchingUserIds = User::searchEncrypted($this->search, ['first_name', 'last_name'])
                ->pluck('id');
            $query->whereIn('user_id', $matchingUserIds);
        }

        $this->candidates = $query->get();

        $this->hasStudentCouncilCandidate = Candidate::whereHas('election_positions.position.electionType', function ($q) {
            $q->where('name', 'Student Council Election');
        })->where('election_id', $this->selectedElection)->exists();

        $this->hasLocalCouncilCandidate = Candidate::whereHas('election_positions.position.electionType', function ($q) {
            $q->where('name', 'Local Council Election');
        })->where('election_id', $this->selectedElection)->exists();
    }

    public function fetchElection(): void
    {
        if (!$this->selectedElection) {
            $this->latestElection = null;
            $this->selectedElectionName = null;
            $this->selectedElectionCampus = null;
            $this->hasStudentCouncilPositions = false;
            $this->hasLocalCouncilPositions = false;
            return;
        }

        $this->latestElection = Election::with('election_type', 'campus')->find($this->selectedElection);

        if ($this->latestElection) {
            $this->selectedElectionName = $this->latestElection->name;
            $this->selectedElectionCampus = $this->latestElection->campus;

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
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        if ($this->selectedElection) {
            $this->fetchElection();
            $this->fetchCandidates();
            $this->fetchVoterTally();
        }

        return view('evotar.livewire.manage-candidate.view-candidate', [
            'candidates' => $this->candidates,
            'selectedElectionName' => $this->selectedElectionName,
            'selectedElectionCampus' => $this->selectedElectionCampus,
            'totalVoters' => $this->totalVoters,
            'totalVoterVoted' => $this->totalVoterVoted,
            'councils' => $this->councils,
            'hasStudentCouncilPositions' => $this->hasStudentCouncilPositions,
            'hasLocalCouncilPositions' => $this->hasLocalCouncilPositions,
        ]);
    }
}
