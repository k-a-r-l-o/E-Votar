<?php

namespace App\Livewire\ManageVoter;

use App\Models\program_major;
use App\Models\User;
use App\Models\Campus;
use App\Models\College;
use App\Models\Program;
use App\Services\ActivityLogger;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class EditVoter extends Component
{
    public $search = '';
    public $userId;
    public $users;
    public $user;
    public $first_name, $middle_initial, $last_name, $extension, $gender, $birth_date, $email, $phone_number, $year_level, $student_id, $campus_id,
        $college_id, $program_id, $program_major_id, $username;

    // Options for dropdowns
    public $campuses = [];
    public $colleges = [];
    public $programs = [];
    public $programMajors = [];

    protected $listeners  = [ 'deactivated-user' => '$refresh', 'activated-user' => '$refresh'];

    public function mount($userId): void
    {
        // Fetch the user
        $this->user = User::find($userId);

        if ($this->user) {
            $this->userId = $this->user->id;
            $this->first_name = $this->user->first_name;
            $this->middle_initial = $this->user->middle_initial;
            $this->last_name = $this->user->last_name;
            $this->extension = $this->user->extension;
            $this->gender = $this->user->gender;
            $this->birth_date = $this->user->birth_date;
            $this->email = $this->user->email;
            $this->phone_number = $this->user->phone_number;
            $this->year_level = $this->user->year_level;
            $this->student_id = $this->user->student_id;
            $this->campus_id = $this->user->campus_id;
            $this->college_id = $this->user->college_id;
            $this->program_id = $this->user->program_id;
            $this->program_major_id = $this->user->program_major_id;
            $this->username = $this->user->username;

            // Fetch initial options for dropdowns
            $this->campuses = Campus::all(); // Fetch all campuses
            $this->colleges = College::where('campus_id', $this->campus_id)->get(); // Fetch colleges based on campus_id
            $this->programs = Program::where('college_id', $this->college_id)->get(); // Fetch programs based on college_id
            $this->programMajors = program_major::where('program_id', $this->program_id)->get(); // Fetch program majors based on program_id
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    public function updatedCampusId($value): void
    {
        $this->colleges = College::where('campus_id', $value)->get();
        $this->college_id = null;
        $this->program_id = null;
        $this->program_major_id = null;
        $this->programs = [];
        $this->programMajors = [];
    }

    public function updatedCollegeId($value): void
    {
        $this->programs = Program::where('college_id', $value)->get();
        $this->program_id = null;
        $this->program_major_id = null;
        $this->programMajors = [];
    }

    public function updatedProgramId($value): void
    {
        $this->programMajors = program_major::where('program_id', $value)->get();

        if ($this->program_major_id) {
            $programMajorExists = program_major::where('id', $this->program_major_id)
                ->where('program_id', $value)
                ->exists();

            if (!$programMajorExists) {
                $this->program_major_id = null;
            }
        }
    }

    public function updatedSearch(): void
    {
        $this->users = User::query()
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
            })
            ->whereDoesntHave('roles', fn ($query) => $query->whereIn('name', ['superadmin', 'admin', 'watcher', 'technical-officer']))
            ->take(5)
            ->get();
    }

    // Edit Voter Function
    public function editVoter(): void
    {
        // Validate the input fields
        $this->validate([
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:1',
            'last_name' => 'required|string|max:255',
            'extension' => 'nullable|string|max:10',
            'gender' => 'required|string|in:Male,Female,non-binary,prefer-not-to-say',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone_number' => 'nullable|string|max:20',
            'year_level' => 'required|string',
            'student_id' => 'required|string|max:50|unique:users,student_id,' . $this->userId,
            'campus_id' => 'required|exists:campuses,id',
            'college_id' => 'required|exists:colleges,id',
            'program_id' => 'required|exists:programs,id',
            'program_major_id' => [
                'nullable',
                Rule::exists('program_majors', 'id')->where('program_id', $this->program_id),
            ],
        ]);

        // Find the user by ID
        $user = User::find($this->userId);

        if ($user) {
            // Update the user details
            $user->update([
                'first_name' => $this->first_name,
                'middle_initial' => $this->middle_initial,
                'last_name' => $this->last_name,
                'extension' => $this->extension,
                'gender' => $this->gender,
                'birth_date' => $this->birth_date,
                'email' => $this->email,
                'phone_number' => preg_replace('/[^0-9]/', '', $this->phone_number),
                'year_level' => $this->year_level,
                'student_id' => $this->student_id,
                'campus_id' => $this->campus_id,
                'college_id' => $this->college_id,
                'program_id' => $this->program_id,
                'program_major_id' => $this->program_major_id,
            ]);



            session()->flash('success', 'Voter updated successfully.');
            $this->dispatch('voter-updated');
        } else {
            session()->flash('error', 'User not found.');
        }
    }


    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('evotar.livewire.manage-voter.edit-voter', ['user' => $this->user]);
    }
}
