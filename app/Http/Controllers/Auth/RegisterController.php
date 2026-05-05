<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully!']);
    }

    public function registerFace(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username',
            'face_descriptor' => 'required|json',
        ]);

        $user = User::where('username', $request->username)->first();
        $user->face_descriptor = json_encode($request->face_descriptor);
        $user->save();

        return response()->json(['message' => 'Face registered successfully!']);
    }

    public function viewFacialRegistration($id)
    {
        $user = User::findOrFail($id);
        return view('evotar.auth.facial-registration', compact('user'));
    }

    protected function superAdminExists(): bool
    {
        return Role::where('name', 'superadmin')->whereHas('users')->exists();
    }
    protected function validateInput(Request $request)
    {
        return Validator::make($request->all(), $this->validationRules(), $this->validationMessages())->validate();
    }

    protected function validationRules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:3',
            'last_name' => 'required|string|max:255',
            'extension' => 'max:5',
            'gender' => 'required',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(),
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:25',
            'year_level' => 'required|string|max:25',
            'student_id' => 'required|string|max:255|regex:/^\d{4}-\d{5}$/',
            'campus' => 'required|integer|exists:campuses,id',
            'college' => 'required|integer|exists:colleges,id',
            'program' => 'required|integer|exists:programs,id',
            'program_major' => 'nullable|integer|exists:program_majors,id',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'middle_initial.required' => 'M.I is required.',
            'last_name.required' => 'Last name is required.',
            'gender.required' => 'Gender is required.',
            'birth_date.required' => 'Birth date is required.',
            'birth_date.before_or_equal' => 'You must be at least 18 years old.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone_number.required' => 'Phone number is required.',
            'year_level.required' => 'Year level is required.',
            'student_id.required' => 'Student ID is required.',
            'student_id.regex' => 'Student ID must be in the format YYYY-XXXXX.',
            'campus.required' => 'Campus selection is required.',
            'campus.exists' => 'The selected campus does not exist.',
            'college.required' => 'College selection is required.',
            'college.exists' => 'The selected college does not exist.',
            'program.required' => 'Program selection is required.',
            'program.exists' => 'The selected program does not exist.',
            'program_major.required' => 'Program major selection is required.',
            'program_major.exists' => 'The selected program major does not exist.',
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'confirm_password.required' => 'Please confirm your password.',
            'confirm_password.same' => 'Passwords do not match.',
        ];
    }

    protected function createUser(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_initial' => $data['middle_initial'],
            'last_name' => $data['last_name'],
            'extension' => $data['extension'],
            'gender' => $data['gender'],
            'birth_date' => $data['birth_date'],
            'email' => $data['email'],
            'phone_number' => preg_replace('/[^0-9]/', '', $data['phone_number']),
            'year_level' => $data['year_level'],
            'student_id' => $data['student_id'],
            'campus_id' => $data['campus'],
            'college_id' => $data['college'],
            'program_id' => $data['program'],
            'program_major_id' => $data['program_major'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);

        if (!$this->superAdminExists()){
            $this->assignRole($user, 'superadmin');
        }

        return $user;
    }

    protected function assignRole(User $user, string $role): void
    {
        if ($user->assignRole($role)) {
            Log::info("Role '{$role}' assigned successfully.");
        } else {
            Log::info("Role '{$role}' assignment failed.");
        }
    }

    /**
     * @throws Exception
     */
    public function registerVoter(Request $request)
    {
        $this->validateInput($request);
        $user = $this->createVoter($request->all());

        session()->flash('registered', true);
        // Get the previous URL and pass it as a query parameter
        $previousUrl = url()->previous();

        return redirect()->route('voter.facial.registration.get', [
            'id' => $user->id,
            'return_url' => $previousUrl,
        ]);

    }

    /**
     * @throws Exception
     */
    public function createVoter(array $data)
    {
        // Check for existing user before creating a new one
        $this->checkForExistingUser($data);

        // Create the user
        $user = $this->createUser($data);

        // Find the role by name
        $role = Role::where('name', 'voter')->first();

        // Handle the case where the role is not found
        if (!$role) {
            throw new \Exception("Role 'voter' not found.");
        }

        // Assign the role to the user
        $this->assignRole($user, $role->name);

        // Sync permissions with the role
        $permissions = $role->permissions;
        $user->syncPermissions($permissions);

        return $user;
    }

    /**
     * @throws Exception
     */
    protected function checkForExistingUser(array $data)
    {
        $existingUser = User::where('email', $data['email'])
            ->orWhere('username', $data['username'])
            ->orWhere('student_id', $data['student_id'])
            ->first();

        if ($existingUser) {
            throw new Exception('A user with this email, username, or student ID already exists.');
        }
    }

    public function uploadFace(Request $request) {
        $imageData = $request->input('image');
        $userId = $request->input('user_id');

        // Validate user
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Convert base64 to image file
        list($type, $imageData) = explode(';', $imageData);
        list(, $imageData) = explode(',', $imageData);
        $imageData = base64_decode($imageData);

        $filename = 'face_' . time() . '.png';
        $path = 'private/assets/profile/' . $filename;

        // Store image privately
        Storage::put($path, $imageData, 'private');

        // Update user face_id_path
        $user->face_descriptor = $filename;
        $user->save();

        return response()->json(['message' => 'Face captured successfully!', 'path' => $path]);
    }



}
