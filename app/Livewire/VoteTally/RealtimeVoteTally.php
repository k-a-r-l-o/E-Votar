<?php

namespace App\Livewire\VoteTally;

use App\Exports\ElectionsExport;
use App\Exports\VoteTallyExport;
use App\Models\Candidate;
use App\Models\Council;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\User;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class RealtimeVoteTally extends Component
{
    protected $listeners = [
        'candidate-created' => '$refresh',
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
    public $councils;

    public $hasLocalCouncilCandidate = false;
    public $hasStudentCouncilCandidate = false;


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
            
            session(['selectedElection' => $this->selectedElection]);

            $this->fetchElection();
            $this->fetchCandidates();
            $this->fetchVoterTally();
            $this->dispatch('updateChartData', $this->selectedElection);
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
        }
    }

    public function fetchCandidates(): void
    {
        if (!$this->selectedElection) {
            $this->candidates = collect();
            return;
        }

        $query = Candidate::with([
            'users',
            'users.program.council',
            'elections',
            'election_positions.position.electionType'
        ])
            ->withCount('votes')
            ->where('election_id', $this->selectedElection);

        // Check if the logged-in user has the 'local-council-watcher' role
        $user = auth()->user();
        if ($user && $user->hasRole('local-council-watcher')) {
            $query->whereHas('election_positions.position.electionType', function ($q) {
                $q->where('name', 'Local Council Election');
            });

            $query->whereHas('users', function ($q) use ($user) {
                $q->where('program_id', $user->program_id);
            });
        }

        if ($this->search) {
            $query->whereHas('users', function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%');
            });
        }

        $this->candidates = $query
            ->orderBy('election_position_id')
            ->get();

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

    public function exportVoteTally()
    {
        return Excel::download(new VoteTallyExport($this->search, null, $this->selectedElection), 'VOTE_TALLY_' .  strtoupper($this->latestElection->name) . '.xlsx');
    }

    public function render()
    {
        if ($this->selectedElection) {
            $this->fetchElection();
            $this->fetchCandidates();
            $this->fetchVoterTally();
        }

        return view('evotar.livewire.vote-tally.realtime-vote-tally', [
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
