<?php

namespace App\Livewire\Dashboard;

use App\Models\Candidate;
use App\Models\Council;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\User;
use Exception;
use Livewire\Component;

class WatcherDashboard extends Component
{
    protected $listeners = [
        'candidate-created' => '$refresh',
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
    public $selectedFilter = 'tsc';
    public $selectedElectionName;
    public $selectedElectionCampus;
    public $hasLocalCouncilCandidate = false;
    public $hasStudentCouncilCandidate = false;

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
        $this->fetchVoterTally();
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
            ->select('candidates.*'); // Ensure only candidate columns are selected

        // Check if the logged-in user has the 'local-council-watcher' role
        $user = auth()->user();
        if ($user && $user->hasRole('local-council-watcher')) {
            // Filter for Local Council Election candidates
            $query->whereHas('election_positions.position.electionType', function ($q) {
                $q->where('name', 'Local Council Election');
            });

            // Filter candidates by the same program as the logged-in user
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('program_id', $user->program_id);
            });
        }

        if ($this->search) {
            $query->whereHas('users', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedElection) {
            $query->whereHas('elections', function ($q) {
                $q->where('id', $this->selectedElection);
            });
        }

        $this->candidates = $query->get();

        $this->hasStudentCouncilCandidate = Candidate::whereHas('election_positions.position.electionType', function ($q) {
            $q->where('name', 'Student Council Election');
        })->whereHas('elections', function ($q) {
            $q->where('id', $this->selectedElection);
        })->exists();

        $this->hasLocalCouncilCandidate = Candidate::whereHas('election_positions.position.electionType', function ($q) {
            $q->where('name', 'Local Council Election');
        })->whereHas('elections', function ($q) {
            $q->where('id', $this->selectedElection);
        })->exists();
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

        $this->latestElection = Election::with('election_type')->find($this->selectedElection);

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
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'voter');
                })
                ->whereDoesntHave('electionExcludedVoters', function ($query) use ($election) {
                    $query->where('election_id', $election->id);
                })
                ->count();

            $this->totalVoterVoted = User::where('campus_id', $election->campus_id)
//                ->whereHas('roles', function ($query) {
//                    $query->where('name', 'voter');
//                })
                ->whereDoesntHave('electionExcludedVoters', function ($query) use ($election) {
                    $query->where('election_id', $election->id);
                })
                ->whereHas('votes', function ($query) use ($election) {
                    $query->where('election_id', $election->id);
                })
                ->count();



            $this->totalCandidates = Candidate::where('election_id', $election->id)->count();
            $this->totalPositions = ElectionPosition::where('election_id', $election->id)->count();
        }
    }
    public function render()
    {
        if ($this->selectedElection) {
            $this->fetchElection();
            $this->fetchCandidates();
            $this->fetchVoterTally();
        }

        return view('evotar.livewire.dashboard.watcher-dashboard', [
            'candidates' => $this->candidates,
            'elections' => $this->elections,
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
