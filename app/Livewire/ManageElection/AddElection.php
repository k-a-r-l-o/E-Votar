<?php

namespace App\Livewire\ManageElection;

use App\Events\TableUpdated;
use App\Events\UserLoggedIn;
use App\Models\Campus;
use App\Models\College;
use App\Models\Election;
use App\Models\election_type;
use App\Models\ElectionPosition;
use App\Models\Position;
use App\Models\Program;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Log;

class AddElection extends Component
{
    use WithFileUploads;
    public $election_name;
    public $election_type;
    public $election_campus;
    public $election_start;
    public $election_end;
    public $positions = [];
    public $studentCouncilPositions = [];
    public $localCouncilPositions = [];
    public $selectedPositions = [];
    public $currentStep = 1;
    public $voters = [];
    public $selectedVoters = [];
    public $selectedColleges = [];
    public $selectedPrograms = [];
    public $programsByCollege = [];
    public $colleges = [];
    public $search = '';
    public $status = '';

    public $electionImage;
    public $temporaryImageUrl;


    protected $messages = [
        'selectedPositions.required' => 'Please select at least one position.',
        'electionImage.max' => 'The election image must not exceed 12MB.',
        'electionImage.mimes' => 'Only PNG images are allowed to ensure the security of vote receipts.',
    ];
    protected $rules = [
        'election_name' => 'required|string|max:255',
        'election_type' => 'required',
        'election_campus' => 'required',
        'election_start' => 'required|date',
        'election_end' => 'required|date|after:election_start',
        'selectedPositions' => 'required|array|min:1',
        'electionImage' => 'required|mimes:png|image|max:12288',
    ];

    public function mount(): void
    {
        $this->fetchVoters();
    }

    public function updatedElectionCampus($value): void
    {
        $this->fetchVoters();
        $this->fetchCollege($this->election_campus);
    }

    public function updatedSelectedColleges(): void
    {
        $this->programsByCollege = [];
        foreach ($this->selectedColleges as $collegeId) {
            $this->programsByCollege[$collegeId] = Program::where('college_id', $collegeId)->get();
        }
    }

    public function updatedElectionImage(): void
    {
        $this->validateOnly('electionImage');
        try {
            $this->temporaryImageUrl = $this->electionImage->temporaryUrl();
        } catch (\Exception $e) {
            $this->addError('electionImage', 'Failed to process image. Please try another file.');
            $this->reset('electionImage', 'temporaryImageUrl');
        }
    }

    public function toggleProgramSelection($programId): void
    {
        if (in_array($programId, $this->selectedPrograms)) {
            $this->selectedPrograms = array_diff($this->selectedPrograms, [$programId]);
        } else {
            $this->selectedPrograms[] = $programId;
        }
    }

    public function excludeAllUsersFromSelectedPrograms($electionId): void
    {
        // Fetch the IDs of users to be excluded
        $excludedUserIds = User::whereIn('program_id', $this->selectedPrograms)->pluck('id')->toArray();

        // Insert the excluded users into the ElectionExcludedVoter table
        foreach ($excludedUserIds as $userId) {
            \App\Models\ElectionExcludedVoter::updateOrCreate(
                ['user_id' => $userId], // Unique identifier for the user
                ['election_id' => $electionId] // Use the passed election ID
            );
        }

        // Update the selected voters array
        $this->selectedVoters = $excludedUserIds;
    }

    public function removePosition($positionId): void
    {
        $this->selectedPositions = array_diff($this->selectedPositions, [$positionId]);
    }

    public function updatedElectionType($value): void
    {
        $this->selectedPositions = [];

        if ($value == 1) {
            $this->studentCouncilPositions = Position::where('election_type_id', '2')->pluck('name', 'id');
            $this->localCouncilPositions = Position::where('election_type_id', '3')->pluck('name', 'id');
            $this->positions = $this->studentCouncilPositions->merge($this->localCouncilPositions);
            $this->positions = array_combine(range(1, count($this->positions)), array_values($this->positions->toArray()));
        } elseif ($value == 2) {
            $this->studentCouncilPositions = Position::where('election_type_id', '2')->pluck('name', 'id');
            $this->positions = $this->studentCouncilPositions->toArray();
        } elseif ($value == 3) {
            $this->localCouncilPositions = Position::where('election_type_id', '3')->pluck('name', 'id');
            $this->positions = $this->localCouncilPositions->toArray();
        } else {
            $this->studentCouncilPositions = [];
            $this->localCouncilPositions = [];
        }

        $this->selectedPositions = array_keys($this->positions);
    }

    public function fetchVoters(): void
    {
        $this->voters = User::query()
            ->when($this->election_campus, fn($query) => $query->where('campus_id', $this->election_campus))
            ->when($this->selectedColleges, fn($query) => $query->whereIn('college_id', $this->selectedColleges))
            ->when($this->selectedPrograms, fn($query) => $query->whereIn('program_id', $this->selectedPrograms))
            ->get();
    }

    public function fetchCollege($campusId): void
    {
        $this->colleges = College::where('campus_id', $campusId)->get();
    }

    public function proceedToVoters(): void
    {
        $this->validate();

        $this->currentStep = 2;
    }

    public function submit(): void
    {
        if ($this->election_start <= now()) {
            $this->status = 'ongoing';
        } else {
            $this->status = 'pending';
        }

        $this->validate();

        // Store the image if provided
        $imagePath = $this->electionImage
            ? $this->electionImage->store('elections/images', 'public')
            : null;


        // Create the election
        $election = Election::create([
            'name' => $this->election_name,
            'type' => $this->election_type,
            'campus_id' => $this->election_campus,
            'date_started' => $this->election_start,
            'date_ended' => $this->election_end,
            'status' => $this->status,
            'image_path' => $imagePath

        ]);

        $this->excludeAllUsersFromSelectedPrograms($election->id);


        foreach ($this->selectedPositions as $positionId) {
            $position = Position::find($positionId);
            if ($position) {
                $electionPosition = new ElectionPosition();
                $electionPosition->election_id = $election->id;
                $electionPosition->position_id = $position->id;
                $electionPosition->save();
            }
        }

//        event(new TableUpdated());
        $this->dispatch('election-created');
        $this->reset();
    }

    public function backToStep1(): void
    {
        $this->currentStep = 1;
    }

    public function resetForm(): void
    {
        $this->reset();
    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
    {
        $electionTypes = election_type::all();
        $campus = Campus::all();

        return view('evotar.livewire.manage-election.add-election', [
            'colleges' => $this->colleges,
            'programsByCollege' => $this->programsByCollege,
            'campus' => $campus,
            'electionTypes' => $electionTypes,
        ]);
    }
}
