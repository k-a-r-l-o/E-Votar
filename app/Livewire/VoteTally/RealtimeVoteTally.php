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
    protected $listeners = ['candidate-created' => '$refresh'];
    public $candidates = [];
    public $filter;
    public $search = '';
    public $selectedElection;
    public $selectedFilter = 'tsc';
    public $elections;
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
            $election = Election::with('election_type')->find($this->selectedElection);
            if ($election) {
                if (in_array($election->election_type->name, ['Student Council Election', 'Local Council Election', 'Student and Local Council Election'])) {
                    $this->filter = 'Student and Local Council Election';
                } else {
                    $this->filter = $election->election_type->name;
                }
            }
        } else {
            $this->filter = 'Student and Local Council Election';
        }

        $this->fetchElection($this->filter);
        $this->selectedFilter = $this->filter;
        $this->councils = Council::all();
        $this->fetchCandidates();
        $this->fetchVoterTally();
    }

    public function updatedSearch(): void
    {
        $this->fetchElection($this->filter);
        $this->fetchCandidates();
        $this->fetchVoterTally();
    }

    public function updatedFilter($value): void
    {
        $this->selectedElection = null;
        $this->fetchElection($value);
        $this->fetchCandidates();
        $this->fetchVoterTally();
        $this->dispatch('updateChartData', $this->selectedElection);
    }

    public function updatedSelectedElection(): void
    {
        $election = Election::find($this->selectedElection);
        if ($election) {
            $this->selectedElectionName = $election->name;
            $this->selectedElectionCampus = $election->campus;
            
            session(['selectedElection' => $this->selectedElection]);

            $this->fetchElection($this->filter);
            $this->fetchCandidates();
            $this->fetchVoterTally();
            $this->dispatch('updateChartData', $this->selectedElection);
        }
    }

    public function fetchVoterTally(): void
    {
        $election = Election::find($this->selectedElection);
        if ($election) {
            $this->totalVoters = User::where('campus_id', $election->campus_id)->where('program_id')
//                ->whereHas('roles', function ($query) {
//                    $query->where('name', 'voter');
//                })
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
        $query = Candidate::with([
            'users', // Changed from 'users' (assuming one candidate belongs to one user)
            'users.program.council',
            'elections',
            'election_positions.position.electionType' // Fixed relationship name
        ])
            ->withCount('votes')
            ->whereHas('elections', function($q) {
                $q->where('elections.id', $this->selectedElection);
            });

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

        // Apply search filter
        if ($this->search) {
            $query->whereHas('users', function ($q) {
                $q->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%');
            });
        }

        // Apply election type filter
        if ($this->filter) {
            $query->whereHas('elections.election_type', function ($q) {
                $q->where('name', $this->filter);
            });
        }

        // Get results with proper ordering
        $this->candidates = $query
            ->orderBy('election_position_id')
            ->get();

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

    public function fetchElection($filter): void
    {
        $this->latestElection = Election::with('election_type')
            ->whereHas('election_type', function ($q) use ($filter) {
                $q->where('name', $filter);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $this->selectedElectionName = $this->latestElection ? $this->latestElection->name : null;
        $this->selectedElectionCampus = $this->latestElection ? $this->latestElection->campus : null;


        $this->hasStudentCouncilPositions = false;
        $this->hasLocalCouncilPositions = false;

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

        $this->elections = Election::with('election_type')
            ->whereHas('election_type', function ($q) use ($filter) {
                $q->where('name', $filter);
            })
            ->get();
    }

    public function exportVoteTally()
    {
        return Excel::download(new VoteTallyExport($this->search, $this->filter, $this->selectedElection), 'VOTE_TALLY_' .  strtoupper($this->latestElection->name) . '.xlsx');

    }

    public function render()
    {
        $this->fetchCandidates();
        return view('evotar.livewire.vote-tally.realtime-vote-tally', [
            'candidates' => $this->candidates,
            'elections' => $this->elections,
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
