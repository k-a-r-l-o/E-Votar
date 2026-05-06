<?php

namespace App\Livewire\AccountSettings;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminAccountSettings extends Component
{
    use WithFileUploads;

    public $firstName;
    public $middleInitial;
    public $lastName;
    public $suffix = 'none';
    public $birthdate;
    public $gender;
    public $email;
    public $phone;
    public $username;
    public $profileImage;
    public $temporaryProfileImageUrl;

    // Security fields
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;
    public $passwordStrength = 0;
    public $passwordRequirements = [
        'length' => false,
        'uppercase' => false,
        'lowercase' => false,
        'number' => false,
        'special' => false,
    ];

    public $formErrors = [];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $user = Auth::user();

        // Initialize fields
        $this->firstName = $user->first_name;
        $this->middleInitial = $user->middle_initial;
        $this->lastName = $user->last_name;
        $this->suffix = $user->extension ?? 'none';
        $this->birthdate = $user->birth_date ? Carbon::parse($user->birth_date)->format('Y-m-d') : null;
        $this->gender = $user->gender;
        $this->email = $user->email;
        $this->phone = $user->phone_number;
        $this->username = $user->username;

        if ($user->profile_photo_path) {
            $this->temporaryProfileImageUrl = Storage::url($user->profile_photo_path);
        }
    }

    public function updatedProfileImage()
    {
        $this->validate([
            'profileImage' => 'image|max:20480'

        ]);

        $this->temporaryProfileImageUrl = $this->profileImage->temporaryUrl();
    }

    public function saveProfile()
    {
        $user = Auth::user();

        $this->validate([
            'firstName' => 'required|string|max:255',
            'middleInitial' => 'nullable|string|max:1',
            'lastName' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Non-binary,Other,Prefer-not',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|regex:/^9\d{2}\d{3}\d{4}$/',
            'profileImage' => 'image|max:20480',
        ]);

        // Handle profile image upload
        if ($this->profileImage) {
            $path = $this->profileImage->store('profile-images', 'public');
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
        }

        $user->update([
            'first_name' => $this->firstName,
            'middle_initial' => $this->middleInitial,
            'last_name' => $this->lastName,
            'suffix' => $this->suffix === 'none' ? null : $this->suffix,
            'birth_date' => $this->birthdate,
            'gender' => $this->gender,
            'email' => $this->email,
            'phone_number' => $this->phone,
            'username' => $this->username,
            'profile_photo_path' => $path ?? $user->profile_photo_path,
        ]);

        $this->js("pushNotification('success', 'Profile Updated', 'Your profile has been updated successfully.')");
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $this->validate([
            'currentPassword' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'newPassword' => [
                'required',
                'different:currentPassword',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'confirmPassword' => 'required|same:newPassword',
            'username' => 'required',
        ]);

        $user->update([
            'username' => $this->username,
            'password' => Hash::make($this->newPassword)
        ]);

        // Reset fields
        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);
        $this->passwordStrength = 0;
        $this->passwordRequirements = array_fill_keys(array_keys($this->passwordRequirements), false);

        $this->js("pushNotification('success', 'Password Updated', 'Your password has been changed successfully.')");
    }

    public function updatedNewPassword($value)
    {
        // Update password requirements and strength
        $this->passwordRequirements = [
            'length' => strlen($value) >= 8,
            'uppercase' => preg_match('/[A-Z]/', $value) === 1,
            'lowercase' => preg_match('/[a-z]/', $value) === 1,
            'number' => preg_match('/[0-9]/', $value) === 1,
            'special' => preg_match('/[^A-Za-z0-9]/', $value) === 1,
        ];

        $metCount = count(array_filter($this->passwordRequirements));
        $this->passwordStrength = $metCount * 20;
    }

    public function getPasswordStrengthLabelProperty()
    {
        if ($this->passwordStrength === 0) return ['label' => 'No password', 'color' => 'gray'];
        if ($this->passwordStrength <= 20) return ['label' => 'Very weak', 'color' => '#f87171'];
        if ($this->passwordStrength <= 40) return ['label' => 'Weak', 'color' => '#fb923c'];
        if ($this->passwordStrength <= 60) return ['label' => 'Medium', 'color' => '#facc15'];
        if ($this->passwordStrength <= 80) return ['label' => 'Strong', 'color' => '#4ade80'];
        return ['label' => 'Very strong', 'color' => '#22c55e'];
    }

    public function getPasswordRequirementsMetProperty()
    {
        return !in_array(false, $this->passwordRequirements, true);
    }

    public function getCanUpdatePasswordProperty()
    {
        return $this->currentPassword &&
            $this->newPassword &&
            $this->confirmPassword &&
            $this->passwordRequirementsMet &&
            ($this->newPassword === $this->confirmPassword);
    }

    public function render()
    {
        return view('evotar.livewire.account-settings.admin-account-settings', [
            'tabs' => [
                ['id' => 'profile', 'label' => 'My Profile', 'icon' => 'profile'],
                ['id' => 'security', 'label' => 'Security', 'icon' => 'security'],
                ['id' => 'developer', 'label' => 'Developer', 'icon' => 'developer'],
            ],
        ]);
    }
}
