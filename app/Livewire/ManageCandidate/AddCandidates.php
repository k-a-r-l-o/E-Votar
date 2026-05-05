<?php

namespace App\Livewire\ManageCandidate;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\PartyList;
use App\Models\User;
use Livewire\Component;

class AddCandidates extends Component
{

    protected $listeners = ['candidate-created' => '$refresh'];
    public $search = '';
    public $users = [];
    public $elections = [];
    public $positions = [];
    public $partyLists = [];
    public $selectedUser = null;
    public $selectedElection = null;
    public $candidate_position = null;
    public $candidate_party_list = null;
    public $candidate_description = '';

    public function mount(): void
    {
        $this->fetchElection();
        $this->fetchPartyList();
    }
    public function updatedSearch(): void
    {
        $this->users = User::role('voter')
        ->where(function ($query) {
            $query->when($this->search, function ($query) {
                $matchingUserIds = User::searchEncrypted($this->search, ['first_name', 'last_name', 'student_id'])
                    ->pluck('id');

                $query->whereIn('id', $matchingUserIds);
            });
        })
            ->take(10)
            ->get();
    }

    public function fetchElection(): void
    {
        $this->elections = Election::whereNotIn('status', ['ongoing', 'paused', 'completed'])->get();
    }

    public function fetchPositions(): void
    {
        if ($this->selectedElection) {
            $this->positions = ElectionPosition::where('election_id', $this->selectedElection)->get();
        } else {
            $this->positions = [];
        }
    }
    public function updatedSelectedElection($value): void
    {
        $this->selectedElection = $value;
        $this->fetchPositions();
    }

    public function fetchPartyList(): void
    {
        $this->partyLists = PartyList::query()->get();
    }

    public function selectUser($userId): void
    {
        $user = User::find($userId);

        if ($user) {
            $this->selectedUser = $user->id;
            $this->search = $user->first_name . ' ' . $user->middle_initial . '. ' . $user->last_name . ' - ' . $user->year_level . ' - ' . $user->program->name;
            $this->users = [];
        }
    }

    public function submit(): void
    {
        $this->validate([
            'selectedUser' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    // Check if user is already a candidate in this election
                    $existing = Candidate::where('user_id', $value)
                        ->where('election_id', $this->selectedElection)
                        ->exists();

                    if ($existing) {
                        $fail('This user is already running for a position in this election.');
                    }
                }
            ],
            'selectedElection' => 'required|exists:elections,id',
            'candidate_position' => 'required|exists:election_positions,id',
            'candidate_party_list' => 'required|exists:party_lists,id',
        ]);

        Candidate::create([
            'user_id' => $this->selectedUser,
            'election_id' => $this->selectedElection,
            'election_position_id' => $this->candidate_position,
            'party_list_id' => $this->candidate_party_list,
            'description' => $this->candidate_description,
        ]);

        $this->dispatch('candidate-created');
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->search = '';
        $this->selectedUser = null;
        $this->selectedElection = null;
        $this->candidate_position = null;
        $this->candidate_party_list = null;
        $this->candidate_description = '';
        $this->positions = [];
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('evotar.livewire.superadmin.add-candidates', ['elections' => $this->elections, 'positions' => $this->positions, 'partyLists' => $this->partyLists]);
    }
}
