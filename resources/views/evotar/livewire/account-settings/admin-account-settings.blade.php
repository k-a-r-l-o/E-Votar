<div>
    <div x-data="{ 
            activeTab: 'profile', 
            tabs: {{ json_encode($tabs) }},
            confirmPassword: '',
            passwordsMatch: false,
            getIcon(name) {
                const icons = {
                    profile: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'/></svg>`,
                    security: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'/></svg>`,
                    developer: `<svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'/></svg>`
                };
                return icons[name] || '';
            }
        }" class="min-h-screen p-2 sm:p-3 md:p-6">
        <div class="container-custom mx-auto">
            <!-- Header with Back Button -->
            <div class="mx-auto flex w-full">
                <!-- Left Section -->
                <div class="flex flex-col w-full">
                    <!-- Header Section -->
                    <div class="flex flex-row justify-between items-start mb-4">
                        <div class="text-left">
                            <h1 class="text-base font-semibold leading-6 text-gray-900">Account Settings</h1>
                            <p class="text-[11px] text-gray-500">Manage your personal information, security, and system preferences</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Horizontal Tabs for Mobile -->
            <div class="lg:hidden mb-4">
                <div class="bg-white rounded-xl overflow-hidden soft-shadow border border-gray-100">
                    <div class="horizontal-tabs flex">
                        <template x-for="(tab, index) in tabs" :key="index">
                            <button
                                @click="activeTab = tab.id"
                                :class="{
                                    'flex flex-col items-center justify-center py-3 px-4 transition-colors border-b-2 flex-1 min-w-[80px]': true,
                                    'text-gray-700 border-gray-600 font-medium': activeTab === tab.id,
                                    'text-gray-500 border-transparent hover:bg-[#f7f9fc]/50': activeTab !== tab.id
                                }"
                            >
                                <span x-html="getIcon(tab.icon)" class="mb-1"></span>
                                <span class="text-[10px] font-semibold uppercase tracking-wider" x-text="tab.label"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Main Content with Sidebar -->
            <div class="flex flex-col md:flex-row gap-4">

                <!-- Content Area -->
                <div class="flex-1">
                    <div>
                        <!-- Tab Navigation (Desktop Only) -->
                        <div class="hidden lg:flex space-x-2 mb-6 border-b border-gray-200">
                            @foreach ($tabs as $tab)
                                <button
                                    @click="activeTab = '{{ $tab['id'] }}'"
                                    class="px-4 py-2 text-sm font-medium rounded-t-lg transition-colors"
                                    :class="activeTab === '{{ $tab['id'] }}' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                >
                                    {{ $tab['label'] }}
                                </button>
                            @endforeach
                        </div>



                        <!-- Profile Tab -->
                        <div x-show="activeTab === 'profile'"
                             class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="bg-red-950 h-16"></div>

                            <!-- Profile Picture -->
                            <div class="flex flex-col items-center -mt-12 mb-6">
                                <div x-data="{ previewUrl: null }" class="relative h-24 w-24 rounded-full border-4 border-white shadow-sm bg-gray-100 overflow-hidden">
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" alt="Profile picture preview" class="w-full h-full object-cover" />
                                    </template>
                                    <template x-if="!previewUrl">
                                        @if ($temporaryProfileImageUrl)
                                            <img src="{{ $temporaryProfileImageUrl }}" alt="Profile picture" class="w-full h-full object-cover" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </template>
                                    <label for="profile-upload" class="absolute bottom-0 right-0 h-8 w-8 bg-indigo-600 rounded-full flex items-center justify-center cursor-pointer border-2 border-white hover:bg-indigo-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                                            <circle cx="12" cy="13" r="4" />
                                        </svg>
                                        <input id="profile-upload" wire:model="profileImage" type="file" accept="image/*" class="hidden"
                                            x-on:change="
                                                const file = $event.target.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = (e) => { previewUrl = e.target.result; };
                                                    reader.readAsDataURL(file);
                                                }
                                            "
                                        />
                                    </label>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="space-y-6">
                                <h2 class="text-base font-semibold text-gray-900">Personal Information</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label for="firstName" class="text-xs font-medium text-gray-700">First Name
                                            <span class="text-red-500">*</span></label>
                                        <input wire:model="firstName" id="firstName" type="text" required
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('firstName') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="lastName" class="text-xs font-medium text-gray-700">Last Name <span
                                                class="text-red-500">*</span></label>
                                        <input wire:model="lastName" id="lastName" type="text" required
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('lastName') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="middleInitial" class="text-xs font-medium text-gray-700">Middle
                                            Initial</label>
                                        <input wire:model="middleInitial" id="middleInitial" type="text" maxlength="1"
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('middleInitial') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="suffix" class="text-xs font-medium text-gray-700">Suffix</label>
                                        <select wire:model="suffix" id="suffix"
                                                class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                                            <option value="none">None</option>
                                            <option value="jr">Jr.</option>
                                            <option value="sr">Sr.</option>
                                            <option value="ii">II</option>
                                            <option value="iii">III</option>
                                            <option value="iv">IV</option>
                                        </select>
                                        @error('suffix') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="birthdate" class="text-xs font-medium text-gray-700">Birthdate <span
                                                class="text-red-500">*</span></label>
                                        <input wire:model="birthdate" id="birthdate" type="date" required
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('birthdate') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="gender" class="text-xs font-medium text-gray-700">Gender <span
                                                class="text-red-500">*</span></label>
                                        <select wire:model="gender" id="gender" required
                                                class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                                            <option value="">Select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Non-binary">Non-binary</option>
                                            <option value="Other">Other</option>
                                            <option value="Prefer-not">Prefer not to say</option>
                                        </select>
                                        @error('gender') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <h2 class="text-base font-semibold text-gray-900 pt-4">Contact Information</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label for="email" class="text-xs font-medium text-gray-700">Email Address <span
                                                class="text-red-500">*</span></label>
                                        <input wire:model="email" id="email" type="email" required
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('email') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="phone" class="text-xs font-medium text-gray-700">Phone Number <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">+63</span>
                                            <input wire:model="phone" id="phone" type="tel" required
                                                   placeholder="9XX XXX XXXX"
                                                   class="w-full h-9 pl-10 pr-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        </div>
                                        @error('phone') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button
                                        wire:click="saveProfile"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="px-6 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        {{-- Default label (only when not loading and not successful) --}}
                                        <span wire:loading.remove>Save Changes</span>

                                        {{-- Show while saving --}}
                                        <span wire:loading>Saving...</span>

                                    </button>

                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="activeTab === 'security'"
                             class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-base font-semibold text-gray-900 mb-6">Account & Security</h2>
                            <div class="max-w-lg space-y-4">
                                <div class="space-y-1">
                                    <label for="username" class="text-xs font-medium text-gray-700">Username</label>
                                    <input wire:model="username" id="username" type="text"
                                           class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                    @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label for="currentPassword" class="text-xs font-medium text-gray-700">Current
                                        Password</label>
                                    <input wire:model="currentPassword" id="currentPassword" type="password"
                                           class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                    @error('currentPassword') <p
                                        class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="space-y-1">
                                        <label for="newPassword" class="text-xs font-medium text-gray-700">New
                                            Password</label>
                                        <input wire:model="newPassword" id="newPassword" type="password"
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('newPassword') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label for="confirmPassword" class="text-xs font-medium text-gray-700">Confirm
                                            New Password</label>
                                        <input wire:model="confirmPassword" id="confirmPassword" type="password"
                                               x-model="confirmPassword"
                                               @input="passwordsMatch = confirmPassword === $wire.newPassword"
                                               class="w-full h-9 px-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm"/>
                                        @error('confirmPassword') <p
                                            class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                        <p x-show="confirmPassword"
                                           :class="passwordsMatch ? 'text-green-500' : 'text-red-500'"
                                           class="text-xs mt-1"
                                           x-text="passwordsMatch ? 'Passwords match' : 'Passwords don\'t match'"></p>
                                    </div>
                                </div>
                                <button
                                    wire:click="updatePassword"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="opacity-50 cursor-not-allowed"
                                    class="px-6 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{-- Show while NOT loading and NOT success --}}
                                    <span wire:loading.remove>Update Password</span>

                                    {{-- Show while loading --}}
                                    <span wire:loading>Updating...</span>

                                    {{-- Show after success --}}
{{--                                    <span x-show="saveStatus === 'success'" class="flex items-center gap-2">--}}
{{--                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"--}}
{{--                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                        <polyline points="20 6 9 17 4 12"/>--}}
{{--                                    </svg>--}}
{{--                                    Updated--}}
{{--                                </span>--}}
                                </button>

                            </div>
                        </div>

                        <!-- Developer Tab -->
                        <div x-show="activeTab === 'developer'"
                             class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-base font-semibold text-gray-900 mb-6">System Information</h2>
                            <div class="space-y-8">
                                <!-- Capstone Adviser -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Capstone Adviser</h3>
                                    <div
                                        class="flex flex-col items-center text-center p-4 bg-gray-50 rounded-lg max-w-xs mx-auto">
                                        <img alt="Archie A. Cenas" class="w-20 h-20 rounded-full object-cover"
                                             src="{{ asset('storage/assets/profile/capstone-adviser.jpg') }}"/>
                                        <h4 class="text-sm font-semibold text-gray-800 mt-3">Archie A. Cenas</h4>
                                        <p class="text-xs text-gray-600">Capstone Adviser</p>
                                    </div>
                                </div>

                                <!-- Development Team -->
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Development Team</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                        <div class="text-center">
                                            <img alt="Lorjohn M. Raña"
                                                 class="w-20 h-20 rounded-full object-cover mx-auto"
                                                 src="{{ asset('storage/assets/profile/raña.jpg') }}"/>
                                            <h4 class="text-sm font-semibold text-gray-800 mt-3">Lorjohn M. Raña</h4>
                                            <p class="text-xs text-gray-600">Full-stack Developer</p>
                                            <p class="text-xs text-gray-600">lorjohn143@gmail.com</p>
                                        </div>
                                        <div class="text-center">
                                            <img alt="Sweet Frachette L. Ang"
                                                 class="w-20 h-20 rounded-full object-cover mx-auto"
                                                 src="{{ asset('storage/assets/profile/ang.jfif') }}"/>
                                            <h4 class="text-sm font-semibold text-gray-800 mt-3">Sweet Frachette L.
                                                Ang</h4>
                                            <p class="text-xs text-gray-600">Front-end Developer</p>
                                            <p class="text-xs text-gray-600">sweetfrachettelaude@gmail.com</p>
                                        </div>
                                        <div class="text-center">
                                            <img alt="Kristine Mae L. Vargas"
                                                 class="w-20 h-20 rounded-full object-cover mx-auto"
                                                 src="{{ asset('storage/assets/profile/vargas.png') }}"/>
                                            <h4 class="text-sm font-semibold text-gray-800 mt-3">Kristine Mae L.
                                                Vargas</h4>
                                            <p class="text-xs text-gray-600">Front-end Developer</p>
                                            <p class="text-xs text-gray-600">krstnmvrgs04@gmail.com</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
