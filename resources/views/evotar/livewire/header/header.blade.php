<div class="flex flex-col w-full z-50" x-data="{ sidebarOpen: false, isActive: false }">
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center w-4/5 relative">
            <button class="mr-4 lg:hidden focus:outline-none" @click="sidebarOpen = true">
                <i class="fas fa-bars text-gray-500"></i>
            </button>
            <div class="flex flex-col justify-center lg:items-start">
                <p class="text-[10px]">University of Southeastern Philippines</p>
                <p class="text-[12px] font-semibold text-red-900">USeP COMMISSION ON ELECTIONS</p>
                <p class="text-[10px]">Impartiality, Transparency, Integrity</p>
            </div>
        </div>
        <div class="flex items-center justify-end me-2 space-x-4">
            <img class="w-8 h-8 rounded-full hidden md:block" height="32"
                src="{{ asset('storage/assets/logo/usep_logo.jpg') }}" alt="usep_logo" width="32" />
            <img class="w-8 h-8 rounded-full hidden md:block" height="32"
                src="{{ asset('storage/assets/logo/usg_logo.png') }}" alt="usg_logo" width="32" />
            <img class="w-8 h-8 rounded-full hidden md:block" height="32"
                src="{{ asset('storage/assets/logo/tsc_logo.png') }}" alt="tsc_logo" width="32" />
            <img class="w-8 h-8 rounded-full hidden md:block" height="32"
                src="{{ asset('storage/assets/logo/tsc_comelec_logo.png') }}" alt="tsc_comelec_logo" width="32" />
            <hr class="border-gray-300 hidden md:block" />
            <x-dropdown align="right" width="58" contentClasses="bg-white" dropdownClasses="border border-gray-200">
                <x-slot name="trigger">
                    <button class="focus:outline-none flex items-center space-x-2">
                        <img alt="Profile Picture" class="w-8 h-8 rounded-full" height="32"
                            src="{{ asset('storage/'.(auth()->user()->profile_photo_path ?? 'assets/profile/default.jpg')) }}"
                            width="32" />
                        <div class="hidden md:block">
                            <p
                                class="text-left text-gray-900 tracking-tight uppercase font-semibold text-[12px] whitespace-nowrap">
                                {{ auth()->user()->first_name }} {{ auth()->user()->middle_initial }}.
                                {{ auth()->user()->last_name }}
                            </p>
                            <p class="text-left text-gray-500 text-[10px] capitalize">
                                {{ auth()->user()->getRoleNames()->join(', ') }}
                            </p>
                        </div>

                        <span class="text-gray-900 font-semibold text-[12px] md:block hidden">
                            <svg width="12" height="6" viewBox="0 0 12 6" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0.68448 0.155061C0.872433 -0.0328917 1.16655 -0.0499784 1.3738 0.103801L1.43318 0.155061L6.00001 4.72165L10.5668 0.155062C10.7548 -0.0328913 11.0489 -0.0499779 11.2562 0.103802L11.3155 0.155062C11.5035 0.343014 11.5206 0.63713 11.3668 0.844385L11.3155 0.903763L6.37436 5.84494C6.1864 6.03289 5.89229 6.04998 5.68503 5.8962L5.62566 5.84494L0.68448 0.903762C0.477732 0.697014 0.477732 0.361809 0.68448 0.155061Z"
                                    fill="#808080" fill-opacity="0.55" />
                            </svg>
                        </span>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <ul class="py-1 text-[10px]">
                        <li class="hover:bg-gray-200">
                            <a href="{{ route('admin.account-settings') }}" class="flex items-center px-7 py-3 cursor-pointer text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-gray-600" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                        stroke="#1F2937" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M18.7273 14.7273C18.6063 15.0015 18.5702 15.3056 18.6236 15.6005C18.6771 15.8954 18.8177 16.1676 19.0273 16.3818L19.0818 16.4364C19.2509 16.6052 19.385 16.8057 19.4765 17.0265C19.568 17.2472 19.6151 17.4838 19.6151 17.7227C19.6151 17.9617 19.568 18.1983 19.4765 18.419C19.385 18.6397 19.2509 18.8402 19.0818 19.0091C18.913 19.1781 18.7124 19.3122 18.4917 19.4037C18.271 19.4952 18.0344 19.5423 17.7955 19.5423C17.5565 19.5423 17.3199 19.4952 17.0992 19.4037C16.8785 19.3122 16.678 19.1781 16.5091 19.0091L16.4545 18.9545C16.2403 18.745 15.9682 18.6044 15.6733 18.5509C15.3784 18.4974 15.0742 18.5335 14.8 18.6545C14.5311 18.7698 14.3018 18.9611 14.1403 19.205C13.9788 19.4489 13.8921 19.7347 13.8909 20.0273V20.1818C13.8909 20.664 13.6994 21.1265 13.3584 21.4675C13.0174 21.8084 12.5549 22 12.0727 22C11.5905 22 11.1281 21.8084 10.7871 21.4675C10.4461 21.1265 10.2545 20.664 10.2545 20.1818V20.1C10.2475 19.7991 10.1501 19.5073 9.97501 19.2625C9.79991 19.0176 9.55521 18.8312 9.27273 18.7273C8.99853 18.6063 8.69437 18.5702 8.39947 18.6236C8.10456 18.6771 7.83244 18.8177 7.61818 19.0273L7.56364 19.0818C7.39478 19.2509 7.19425 19.385 6.97353 19.4765C6.7528 19.568 6.51621 19.6151 6.27727 19.6151C6.03834 19.6151 5.80174 19.568 5.58102 19.4765C5.36029 19.385 5.15977 19.2509 4.99091 19.0818C4.82186 18.913 4.68775 18.7124 4.59626 18.4917C4.50476 18.271 4.45766 18.0344 4.45766 17.7955C4.45766 17.5565 4.50476 17.3199 4.59626 17.0992C4.68775 16.8785 4.82186 16.678 4.99091 16.5091L5.04545 16.4545C5.25503 16.2403 5.39562 15.9682 5.4491 15.6733C5.50257 15.3784 5.46647 15.0742 5.34545 14.8C5.23022 14.5311 5.03887 14.3018 4.79497 14.1403C4.55107 13.9788 4.26526 13.8921 3.97273 13.8909H3.81818C3.33597 13.8909 2.87351 13.6994 2.53253 13.3584C2.19156 13.0174 2 12.5549 2 12.0727C2 11.5905 2.19156 11.1281 2.53253 10.7871C2.87351 10.4461 3.33597 10.2545 3.81818 10.2545H3.9C4.2009 10.2475 4.49273 10.1501 4.73754 9.97501C4.98236 9.79991 5.16883 9.55521 5.27273 9.27273C5.39374 8.99853 5.42984 8.69437 5.37637 8.39947C5.3229 8.10456 5.18231 7.83244 4.97273 7.61818L4.91818 7.56364C4.74913 7.39478 4.61503 7.19425 4.52353 6.97353C4.43203 6.7528 4.38493 6.51621 4.38493 6.27727C4.38493 6.03834 4.43203 5.80174 4.52353 5.58102C4.61503 5.36029 4.74913 5.15977 4.91818 4.99091C5.08704 4.82186 5.28757 4.68775 5.50829 4.59626C5.72901 4.50476 5.96561 4.45766 6.20455 4.45766C6.44348 4.45766 6.68008 4.50476 6.9008 4.59626C7.12152 4.68775 7.32205 4.82186 7.49091 4.99091L7.54545 5.04545C7.75971 5.25503 8.03183 5.39562 8.32674 5.4491C8.62164 5.50257 8.9258 5.46647 9.2 5.34545H9.27273C9.54161 5.23022 9.77093 5.03887 9.93245 4.79497C10.094 4.55107 10.1807 4.26526 10.1818 3.97273V3.81818C10.1818 3.33597 10.3734 2.87351 10.7144 2.53253C11.0553 2.19156 11.5178 2 12 2C12.4822 2 12.9447 2.19156 13.2856 2.53253C13.6266 2.87351 13.8182 3.33597 13.8182 3.81818V3.9C13.8193 4.19253 13.906 4.47834 14.0676 4.72224C14.2291 4.96614 14.4584 5.15749 14.7273 5.27273C15.0015 5.39374 15.3056 5.42984 15.6005 5.37637C15.8954 5.3229 16.1676 5.18231 16.3818 4.97273L16.4364 4.91818C16.6052 4.74913 16.8057 4.61503 17.0265 4.52353C17.2472 4.43203 17.4838 4.38493 17.7227 4.38493C17.9617 4.38493 18.1983 4.43203 18.419 4.52353C18.6397 4.61503 18.8402 4.74913 19.0091 4.91818C19.1781 5.08704 19.3122 5.28757 19.4037 5.50829C19.4952 5.72901 19.5423 5.96561 19.5423 6.20455C19.5423 6.44348 19.4952 6.68008 19.4037 6.9008C19.3122 7.12152 19.1781 7.32205 19.0091 7.49091L18.9545 7.54545C18.745 7.75971 18.6044 8.03183 18.5509 8.32674C18.4974 8.62164 18.5335 8.9258 18.6545 9.2V9.27273C18.7698 9.54161 18.9611 9.77093 19.205 9.93245C19.4489 10.094 19.7347 10.1807 20.0273 10.1818H20.1818C20.664 10.1818 21.1265 10.3734 21.4675 10.7144C21.8084 11.0553 22 11.5178 22 12C22 12.4822 21.8084 12.9447 21.4675 13.2856C21.1265 13.6266 20.664 13.8182 20.1818 13.8182H20.1C19.8075 13.8193 19.5217 13.906 19.2778 14.0676C19.0339 14.2291 18.8425 14.4584 18.7273 14.7273Z"
                                        stroke="#1F2937" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Account Settings
                            </a>
                        </li>
                        <li class="hover:bg-gray-200">
                            <a href="{{ route('logout') }}" class="flex items-center px-7 py-3 cursor-pointer text-red-500"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg class="w-4 h-4 mr-2 text-gray-600" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18 8L22 12M22 12L18 16M22 12H9M15 4.20404C13.7252 3.43827 12.2452 3 10.6667 3C5.8802 3 2 7.02944 2 12C2 16.9706 5.8802 21 10.6667 21C12.2452 21 13.7252 20.5617 15 19.796"
                                        stroke="#1F2937" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </x-slot>
            </x-dropdown>
        </div>

        @php
            $rolesExceptVoter = \Spatie\Permission\Models\Role::where('name', '!=', 'voter')->pluck('name')->toArray();
        @endphp

        <div class="fixed inset-0 z-50 flex items-center justify-start bg-black bg-opacity-50" x-cloak
            x-show="sidebarOpen" @click.away="sidebarOpen = false" @click.self="sidebarOpen = false"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white w-64 h-full overflow-y-auto p-4 shadow-lg lg:hidden z-50">
                <div class="flex items-center justify-between mb-4">
                    <img class="w-40 h-8" height="32" src="{{ asset('storage/assets/logo/evotar_red_1.png') }}"
                        alt="evotar_logo" width="32" />
                </div>
                <ul class="py-2 px-2 w-full flex-row justify-center">
                    <li
                        class="group flex items-center space-x-2 w-full px-4 py-2 mb-2 rounded-md
                {{ request()->routeIs('admin.dashboard') || request()->routeIs('technical-officer.dashboard') || request()->routeIs('watcher.dashboard') ? 'bg-black text-white' : 'hover:bg-black hover:text-white' }}">
                        <div class="flex items-center space-x-1 ">
                            <svg class="icon w-[15px] h-[15px] mr-2 text-[#757575] fill-[#757575]
                        {{ request()->routeIs('admin.dashboard') || request()->routeIs('technical-officer.dashboard') || request()->routeIs('watcher.dashboard') ? 'text-white fill-white' : 'group-hover:text-white group-hover:fill-white' }}"
                                viewBox="0 0 17 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.5013 0.647461C7.73992 0.647461 6.99308 0.856085 6.34193 1.25067L2.17526 3.77567C1.56254 4.14697 1.0559 4.66999 0.70429 5.29421C0.352676 5.91843 0.167957 6.62276 0.167969 7.3392V12.5825C0.167969 13.6876 0.606956 14.7474 1.38836 15.5288C2.16976 16.3102 3.22957 16.7492 4.33463 16.7492H12.668C13.773 16.7492 14.8328 16.3102 15.6142 15.5288C16.3956 14.7474 16.8346 13.6876 16.8346 12.5825V7.33837C16.8345 6.6221 16.6497 5.91778 16.2981 5.29375C15.9465 4.66973 15.4399 4.14688 14.8274 3.77568L10.6607 1.25068C10.0095 0.856092 9.26269 0.647461 8.5013 0.647461ZM7.20569 2.67605C7.59638 2.4393 8.04447 2.31413 8.5013 2.31413C8.95813 2.31413 9.40622 2.4393 9.79691 2.67605L13.9636 5.20105C14.3311 5.42377 14.6351 5.73749 14.846 6.1119C15.057 6.48626 15.1678 6.90867 15.168 7.33837V12.5825C15.168 13.2456 14.9046 13.8815 14.4357 14.3503C13.9669 14.8191 13.331 15.0825 12.668 15.0825H11.8346V12.5825C11.8346 11.6985 11.4834 10.8506 10.8583 10.2255C10.2332 9.60039 9.38535 9.2492 8.5013 9.2492C7.61725 9.2492 6.7694 9.60039 6.14428 10.2255C5.51916 10.8506 5.16797 11.6985 5.16797 12.5825V15.0825H4.33463C3.67159 15.0825 3.03571 14.8191 2.56687 14.3503C2.09803 13.8815 1.83464 13.2456 1.83464 12.5825V7.3392C1.83463 6.90933 1.94546 6.48671 2.15643 6.11218C2.3674 5.73764 2.67138 5.42383 3.03901 5.20106L7.20569 2.67605ZM9.67981 11.404C9.99237 11.7166 10.168 12.1405 10.168 12.5825V15.0825H6.83463V12.5825C6.83463 12.1405 7.01023 11.7166 7.32279 11.404C7.63535 11.0915 8.05927 10.9159 8.5013 10.9159C8.94333 10.9159 9.36725 11.0915 9.67981 11.404Z"
                                    fill="currentColor" />
                            </svg>
                            <a href="
                                @if(auth()->user()->hasAnyRole('superadmin', 'admin'))
                                    {{ route('admin.dashboard') }}
                                @elseif(auth()->user()->hasRole('technical_officer'))
                                    {{ route('technical-officer.dashboard') }}
                                @elseif(auth()->user()->hasAnyRole('student-council-watcher', 'local-council-watcher'))
                                    {{ route('watcher.dashboard') }}
                                @else
                                    #
                                @endif
                            "
                                class="text-[12px] font-normal text-[#757575]
                            {{ request()->routeIs('admin.dashboard') || request()->routeIs('technical.dashboard') || request()->routeIs('watcher.dashboard') ? 'text-white' : 'group-hover:text-white' }}">
                                Dashboard
                            </a>

                        </div>
                    </li>

                    @can('view election')
                        <li x-data="{ isActive: false,  open: {{ request()->routeIs('admin.elections*') || request()->routeIs('admin.candidates*') || request()->routeIs('admin.council*') || request()->routeIs('admin.positions*') || request()->routeIs('admin.election.party.list*') ? 'true' : 'false' }} }"
                            class="relative group mb-2">
                            <!-- Parent Button -->
                            <button @click="open = !open"
                                class="flex justify-between items-center px-4 py-2 w-full rounded-md transition duration-200
                                                                                                             {{ request()->routeIs('admin.elections*') || request()->routeIs('admin.candidates*') || request()->routeIs('admin.positions*') || request()->routeIs('admin.election.party.list*') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                <div class="flex items-center space-x-1 ">
                                    <!-- SVG Icon -->
                                    <svg class="icon mr-1"
                                        :class="isActive ? 'text-white fill-white' : 'text-[#757575] fill-[#757575]'"
                                        width="23" height="18" viewBox="0 0 48 48" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M 14.994141 5 C 13.429434 5 12.032816 6.0554554 11.619141 7.5722656 C 11.268732 8.8555073 10.329738 11.023778 10.126953 13.15625 A 1.50015 1.50015 0 0 0 10.013672 13.615234 C 10.013672 13.615234 9.968054 14.199579 9.984375 14.917969 C 10.000643 15.634048 10.014781 16.468801 10.40625 17.371094 C 10.701839 18.100136 11.764116 20.363841 12.912109 22.841797 C 13.422922 23.944391 13.566138 24.269347 14 25.214844 L 14 40 L 5.5 40 A 1.50015 1.50015 0 1 0 5.5 43 L 42.5 43 A 1.50015 1.50015 0 1 0 42.5 40 L 34 40 L 34 30.867188 C 34.304901 30.290209 38.000655 23.294042 38.912109 21.287109 A 1.50015 1.50015 0 0 0 38.919922 21.267578 C 39.629446 19.645293 39.661313 17.938049 38.988281 16.507812 C 38.315248 15.077576 37.152348 13.934781 35.671875 12.628906 A 1.50015 1.50015 0 0 0 35.640625 12.601562 C 33.251248 10.608655 29.753029 7.132124 27.953125 5.734375 A 1.50015 1.50015 0 0 0 27.953125 5.7324219 C 27.340121 5.2573698 26.583675 5 25.808594 5 L 14.994141 5 z M 14.994141 8 L 25.808594 8 C 25.921513 8 26.026251 8.0365129 26.115234 8.1054688 C 27.434859 9.1306495 31.101222 12.717453 33.6875 14.878906 C 35.073027 16.101032 35.93497 17.065893 36.273438 17.785156 C 36.610633 18.501717 36.638787 18.987425 36.173828 20.054688 C 35.851458 20.763372 35.014258 22.331038 34 24.292969 L 34 18.5 A 1.50015 1.50015 0 0 0 32.5 17 L 21 17 A 1.50015 1.50015 0 0 0 19.613281 19.072266 C 20.861402 22.096481 22.15269 24.915731 22.347656 25.398438 C 22.820437 26.57186 22.268286 27.864662 21.09375 28.337891 C 19.920327 28.810672 18.627526 28.258521 18.154297 27.083984 C 17.865902 26.368575 16.789266 24.074031 15.634766 21.582031 C 14.480266 19.090031 13.247434 16.40134 13.173828 16.21875 A 1.50015 1.50015 0 0 0 13.158203 16.179688 C 13.121393 16.095337 12.997054 15.40772 12.984375 14.849609 C 12.973005 14.349047 12.996483 14.028485 13.001953 13.949219 A 1.50015 1.50015 0 0 0 13.017578 13.765625 C 13.046751 12.623826 14.0119 10.198881 14.513672 8.3613281 C 14.573996 8.1401384 14.756848 8 14.994141 8 z M 23.28125 20 L 31 20 L 31 30.5 L 31 40 L 17 40 L 17 30.337891 C 18.436094 31.46883 20.410735 31.847983 22.214844 31.121094 C 24.892309 30.042323 26.210078 26.95592 25.130859 24.277344 A 1.50015 1.50015 0 0 0 25.128906 24.275391 C 24.897894 23.703372 24.028298 21.717323 23.28125 20 z">
                                        </path>
                                    </svg>

                                    <span class="text-[12px] font-normal">Manage Election</span>
                                </div>
                                <!-- Dropdown Arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <ul x-show="open" x-collapse class="mt-1 ml-10 border-l-2 border-gray-300 space-y-1">
                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.elections') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.elections') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M480-564h192v-72H480v72Zm0 240h192v-72H480v72ZM360.21-528Q390-528 411-549.21t21-51Q432-630 410.79-651t-51-21Q330-672 309-650.79t-21 51Q288-570 309.21-549t51 21Zm0 240Q390-288 411-309.21t21-51Q432-390 410.79-411t-51-21Q330-432 309-410.79t-21 51Q288-330 309.21-309t51 21ZM216-144q-29.7 0-50.85-21.15Q144-186.3 144-216v-528q0-29.7 21.15-50.85Q186.3-816 216-816h528q29.7 0 50.85 21.15Q816-773.7 816-744v528q0 29.7-21.15 50.85Q773.7-144 744-144H216Zm0-72h528v-528H216v528Zm0-528v528-528Z" />
                                        </svg>
                                        <!-- Text -->
                                        Elections
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.candidates') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.candidates') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-3" width="16" height="16" viewBox="0 0 18 18"
                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14.825 13.1381C15.2577 12.6216 15.4964 11.9702 15.5 11.2964C15.5 10.5229 15.1927 9.781 14.6457 9.23401C14.0987 8.68703 13.3569 8.37974 12.5833 8.37974H12.35C13.0372 7.56307 13.4149 6.53044 13.4167 5.46308C13.4186 4.69353 13.2268 3.93589 12.8588 3.26001C12.4909 2.58413 11.9586 2.01178 11.3113 1.59574C10.6639 1.17969 9.92215 0.933352 9.1545 0.87943C8.38685 0.825509 7.61797 0.965743 6.91878 1.2872C6.21959 1.60866 5.6126 2.10099 5.15377 2.71879C4.69495 3.3366 4.39907 4.05998 4.29342 4.82224C4.18776 5.5845 4.27574 6.36109 4.54923 7.0804C4.82273 7.7997 5.27293 8.43857 5.85833 8.93808C4.28356 9.53993 2.92835 10.6053 1.97168 11.9934C1.01501 13.3815 0.501862 15.0272 0.5 16.7131C0.5 16.9341 0.587797 17.146 0.744078 17.3023C0.900358 17.4586 1.11232 17.5464 1.33333 17.5464C1.55435 17.5464 1.76631 17.4586 1.92259 17.3023C2.07887 17.146 2.16667 16.9341 2.16667 16.7131C2.16667 14.945 2.86905 13.2493 4.11929 11.999C5.36953 10.7488 7.06522 10.0464 8.83333 10.0464H9.95833C9.71423 10.5431 9.62048 11.1002 9.68857 11.6495C9.75667 12.1987 9.98366 12.7161 10.3417 13.1381C9.63173 13.5352 9.0405 14.1144 8.6289 14.816C8.2173 15.5176 8.00021 16.3163 8 17.1297C8 17.3508 8.0878 17.5627 8.24408 17.719C8.40036 17.8753 8.61232 17.9631 8.83333 17.9631C9.05435 17.9631 9.26631 17.8753 9.42259 17.719C9.57887 17.5627 9.66667 17.3508 9.66667 17.1297C9.66667 16.3562 9.97396 15.6143 10.5209 15.0673C11.0679 14.5204 11.8098 14.2131 12.5833 14.2131C13.3569 14.2131 14.0987 14.5204 14.6457 15.0673C15.1927 15.6143 15.5 16.3562 15.5 17.1297C15.5 17.3508 15.5878 17.5627 15.7441 17.719C15.9004 17.8753 16.1123 17.9631 16.3333 17.9631C16.5543 17.9631 16.7663 17.8753 16.9226 17.719C17.0789 17.5627 17.1667 17.3508 17.1667 17.1297C17.1665 16.3163 16.9494 15.5176 16.5378 14.816C16.1262 14.1144 15.5349 13.5352 14.825 13.1381ZM8.83333 8.37974C8.25647 8.37974 7.69256 8.20868 7.21292 7.8882C6.73328 7.56771 6.35944 7.11219 6.13868 6.57924C5.91793 6.04629 5.86017 5.45984 5.97271 4.89406C6.08525 4.32829 6.36304 3.80859 6.77094 3.40068C7.17884 2.99278 7.69854 2.71499 8.26432 2.60245C8.8301 2.48991 9.41654 2.54767 9.94949 2.76843C10.4824 2.98918 10.938 3.36302 11.2585 3.84266C11.5789 4.32231 11.75 4.88621 11.75 5.46308C11.75 6.23662 11.4427 6.97849 10.8957 7.52547C10.3487 8.07245 9.60688 8.37974 8.83333 8.37974ZM12.5833 12.5464C12.2518 12.5464 11.9339 12.4147 11.6995 12.1803C11.465 11.9459 11.3333 11.6279 11.3333 11.2964C11.3355 10.9656 11.4679 10.6489 11.7019 10.4149C11.9358 10.181 12.2525 10.0486 12.5833 10.0464C12.9149 10.0464 13.2328 10.1781 13.4672 10.4125C13.7016 10.6469 13.8333 10.9649 13.8333 11.2964C13.8333 11.6279 13.7016 11.9459 13.4672 12.1803C13.2328 12.4147 12.9149 12.5464 12.5833 12.5464Z" />
                                        </svg>
                                        <!-- Text -->
                                        Candidates
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.positions') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.positions') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M624-384q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM384-144v-63q0-28 14.5-51t38.5-35q43-21 90-32t97-11q50 0 97 11t90 32q24 12 38.5 35t14.5 51v63H384Zm73-72h334q-30-23-72-35.5T624-264q-53 0-95 12.5T457-216Zm167-240q20.4 0 34.2-13.8Q672-483.6 672-504q0-20.4-13.8-34.2Q644.4-552 624-552q-20.4 0-34.2 13.8Q576-524.4 576-504q0 20.4 13.8 34.2Q603.6-456 624-456Zm0-48Zm0 288ZM144-396v-72h288v72H144Zm0-300v-72h432v72H144Zm293 150H144v-72h326q-12 16-20.11 33.78Q441.77-566.44 437-546Z" />
                                        </svg>
                                        <!-- Text -->
                                        Position
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.council') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.council') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M346-72q-48 0-87.5-24.5T200-163q-14 15-34.5 23.5T121-131q-51 0-86.5-35T-1-251q0-39 23-71t61-44q-14-20-21.5-42T54-452q0-49 24.5-87.5T148-599q4 23 9 40t13 29q-21 12-32.5 31T126-457q0 42 34.5 69t93.5 32l23 41q-11 25-16.5 44.5T255-237q0 40 25.5 66.5T345-144q27 0 49-17.5t41-54q19-36.5 35-94T501-448l69 18q-15 93-36 160.5T485.5-158q-27.5 44-62 65T346-72ZM120-204q20 0 34-14t14-34q0-20-14-33.5T120-299q-20 0-34 13.5T72-252q0 20 14 34t34 14Zm289-133q-60-51-101.5-92.5t-67-76Q215-540 203.5-571T192-632q0-66 44.5-113T344-792q5 0 9.5.5t9.5 1.5q-2-7-2.5-13.5T360-817q0-50 35-84.5t85-34.5q51 0 85.5 34.5T600-816q0 7-.5 13.5T597-790q5-1 8-1.5t9-.5q56 0 99 37t53 93q-19-4-37.5-4t-37.5 4q-11-27-34-42.5T606-720q-25 0-47.5 15.5T504-648h-47q-28-38-52-55t-53-17q-38 0-63 25.5T264-630q0 20 9 41t31 48.5q22 27.5 60 64t96 88.5l-51 51Zm71-431q20 0 34-14t14-34q0-20-14-34t-34-14q-20 0-34 14t-14 34q0 20 14 34t34 14ZM624-71q-29 0-58-10t-53-29q16-23 24-37.5t13-27.5q15 14 34.5 22.5T624-144q35 0 58-24.5t23-62.5q0-16-5.5-35.5T683-315l23-40q57-4 92.5-32t35.5-70q0-41-30-64t-84-23q-36 0-91 14.5T480-482l-19-70q97-35 155.5-50T720-617q81 0 133.5 45T906-457q0 26-7.5 49T876-365q38 11 61 42t23 71q0 51-35 86.5T840-130q-23 0-43.5-8T759-162q-12 42-48.5 66.5T624-71Zm216-133q20 0 34-14t14-34q0-20-14-33.5T840-299q-20 0-34 13.5T792-252q0 20 14 34t34 14Zm-720-48Zm360-564Zm360 564Z" />
                                        </svg>
                                        <!-- Text -->
                                        Council
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.election.party.list') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.election.party.list') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M672-336q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-72q20.4 0 34.2-13.8Q720-435.6 720-456q0-20.4-13.8-34.2Q692.4-504 672-504q-20.4 0-34.2 13.8Q624-476.4 624-456q0 20.4 13.8 34.2Q651.6-408 672-408ZM432-49v-116.57q0-21.1 10-39.69 10-18.59 28-29.65 32-19.09 66.51-31.19Q571.03-278.19 607-284l65 78 65-78q36.31 5.81 70.65 17.9Q842-254 874-235q18 11 28 29.61 10 18.6 10 39.72V-49H432Zm72-72h145l-69-83q-20.42 5.24-39.71 13.62Q521-182 504-171v50Zm191 0h145v-50q-17-11-36.5-19.5T764-204l-69 83Zm-46 0Zm46 0Zm-479.25-23Q186-144 165-165.15T144-216v-528q0-29.7 21.15-50.85Q186.3-816 216-816h528q29.7 0 50.85 21.15Q816-773.7 816-744v161q-15-17-33-30t-39-21v-110H216v528h153q-5 12-7 24.67-2 12.66-2 25.33v22H215.75ZM288-600h257q26-23 59-35.5t68-12.5v-24H288v72Zm0 156h192q-2-18 1-36.5t9-35.5H288v72Zm0 156h132q14-11 30.45-18.7 16.44-7.7 33.55-14.3v-39H288v72Zm-72 72v-528 110-14 432Zm456-240Z" />
                                        </svg>
                                        <!-- Text -->
                                        Party List
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endcan

                    @can('view vote tally')
                        <li x-data="{  isActive: false, open: {{ request()->routeIs('admin.vote.tally') || request()->routeIs('admin.election.result') ? 'true' : 'false' }} }"
                            class="relative group mb-2">
                            <!-- Parent Button -->
                            <button @click="open = !open"
                                class="flex justify-between items-center px-4 py-2 w-full rounded-md transition duration-200
                                                                                                             {{ request()->routeIs('admin.vote.tally*') || request()->routeIs('admin.election.result*') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                <div class="flex items-center space-x-1 ">
                                    <!-- SVG Icon -->
                                    <svg class="icon mr-1"
                                        :class="isActive ? 'text-white fill-white' : 'text-[#757575] fill-[#757575]'"
                                        width="14" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"
                                        fill="currentColor">
                                        <path
                                            d="M120-120v-80l80-80v160h-80Zm160 0v-240l80-80v320h-80Zm160 0v-320l80 81v239h-80Zm160 0v-239l80-80v319h-80Zm160 0v-400l80-80v480h-80ZM120-327v-113l280-280 160 160 280-280v113L560-447 400-607 120-327Z" />
                                    </svg>
                                    <span class="text-[12px] font-normal">Election Monitoring</span>
                                </div>

                                <!-- Dropdown Arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <ul x-show="open" x-collapse class="mt-1 ml-10 border-l-2 border-gray-300 space-y-1">
                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.vote.tally') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.vote.tally') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M624-192v-288h144v288H624Zm-216 0v-576h144v576H408Zm-216 0v-384h144v384H192Z" />
                                        </svg>

                                        <!-- Text -->
                                        Vote Tally
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.election.result') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.election.result') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" width="17" height="17" viewBox="0 0 19 19"
                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.5838 3.89691L14.0228 6.70707L9.64941 0.873477C9.63182 0.849997 9.609 0.830939 9.58276 0.817813C9.55652 0.804686 9.52758 0.797852 9.49824 0.797852C9.4689 0.797852 9.43996 0.804686 9.41372 0.817813C9.38748 0.830939 9.36466 0.849997 9.34707 0.873477L4.97597 6.70707L0.412692 3.89691C0.279099 3.81488 0.105661 3.92504 0.126755 4.08207L1.91738 17.6899C1.94316 17.875 2.10254 18.018 2.29238 18.018H16.7088C16.8963 18.018 17.058 17.8774 17.0814 17.6899L18.8721 4.08207C18.8908 3.92504 18.7197 3.81488 18.5838 3.89691ZM15.6307 16.4149H3.36582L2.10488 6.81957L5.38144 8.83754L9.49941 3.34379L13.6174 8.83754L16.8939 6.81957L15.6307 16.4149ZM9.49941 9.34613C8.04394 9.34613 6.86035 10.5297 6.86035 11.9852C6.86035 13.4407 8.04394 14.6243 9.49941 14.6243C10.9549 14.6243 12.1385 13.4407 12.1385 11.9852C12.1385 10.5297 10.9549 9.34613 9.49941 9.34613ZM9.49941 13.1172C8.87597 13.1172 8.36972 12.611 8.36972 11.9852C8.36972 11.3618 8.87597 10.8532 9.49941 10.8532C10.1228 10.8532 10.6291 11.3594 10.6291 11.9852C10.6291 12.6086 10.1228 13.1172 9.49941 13.1172Z" />
                                        </svg>
                                        <!-- Text -->
                                        Election Result
                                    </a>
                                </li>
                            </ul>
                        </li>

                    @endcan

                    @can('view users')
                        <li x-data="{  isActive: false, open: {{ request()->routeIs('admin.voters') || request()->routeIs('admin.system.user') ? 'true' : 'false' }} }"
                            class="relative group mb-2">
                            <!-- Parent Button -->
                            <button @click="open = !open"
                                class="flex justify-between items-center px-4 py-2 w-full rounded-md transition duration-200
                                                                                                             {{ request()->routeIs('admin.voters*') || request()->routeIs('admin.system.user*') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                <div class="flex items-center space-x-1 ">

                                    <!-- SVG Icon -->
                                    <svg class="icon mr-1"
                                        :class="isActive ? 'text-white fill-white' : 'text-[#757575] fill-[#757575]'"
                                        xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="currentColor">
                                        <path
                                            d="M384-484.07q-63.59 0-107.86-44.27-44.27-44.27-44.27-107.86 0-63.58 44.27-107.74 44.27-44.15 107.86-44.15 63.59 0 107.86 44.15 44.27 44.16 44.27 107.74 0 63.59-44.27 107.86-44.27 44.27-107.86 44.27ZM87.87-179.8v-100.61q0-29.59 14.77-52.76 14.77-23.18 36.77-36.18 55-32 117.8-49.36Q320-436.07 384-436.07q11 0 22.53.5 11.54.5 23.49 1.5-7.48 17-12.77 39.79-5.29 22.78-7.77 42.21l-24.9-1q-52.19 0-103.95 13.41-51.76 13.4-98.76 41.21-4.95 2.89-7.98 7.83-3.02 4.93-3.02 11.4v16.42h247.65q5.33 21.26 15.67 43.03 10.35 21.77 23.03 39.97H87.87Zm554.72 43.93-12.24-57.2q-13.05-4.52-25.07-11.4-12.02-6.88-22.3-15.16l-56.2 17.48-34.87-60.26 41.96-40.72q-4.24-12.8-3.62-27.45.62-14.64 3.62-27.44l-41.96-39.96L526.78-459l55.2 16.48q10.28-9.28 22.8-16.66 12.52-7.39 25.57-10.91l13.24-57.19h69.98l13.23 57.19q13.05 4.52 25.69 11.29 12.64 6.76 22.92 16.28L830.37-458l35.11 60.02-41.2 38.96q2.24 14.04 2.12 28.5-.12 14.45-3.12 27.39l42.2 39.72-35.11 60.26-55.96-16.48q-10.28 8.28-22.42 15.66-12.14 7.38-25.19 10.9l-14.23 57.2h-69.98Zm36.32-125.5q28.83 0 49.35-20.85 20.52-20.86 20.52-49.69t-20.73-49.35q-20.74-20.52-49.57-20.52-28.83 0-49.47 20.73-20.64 20.74-20.64 49.57 0 28.83 20.85 49.47 20.86 20.64 49.69 20.64ZM384.2-567.07q28.6 0 48.77-20.36 20.16-20.37 20.16-48.97 0-28.6-20.37-48.64-20.36-20.05-48.96-20.05t-48.77 20.3q-20.16 20.3-20.16 48.81 0 28.6 20.37 48.76 20.36 20.15 48.96 20.15Zm-.2-69.13Zm34.52 373.4Z" />
                                    </svg>

                                    <span class="text-[12px] font-normal">Manage System User</span>
                                </div>

                                <!-- Dropdown Arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <ul x-show="open" x-collapse class="mt-1 ml-10 border-l-2 border-gray-300 space-y-1">
                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.voters') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.voters') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 -960 960 960" fill="currentColor">
                                            <path
                                                d="M80-160v-112q0-33 17-62t47-44q51-26 115-44t141-18q30 0 58.5 3t55.5 9l-70 70q-11-2-21.5-2H400q-71 0-127.5 17T180-306q-9 5-14.5 14t-5.5 20v32h250l80 80H80Zm542 16L484-282l56-56 82 82 202-202 56 56-258 258ZM400-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm10 240Zm-10-320q33 0 56.5-23.5T480-640q0-33-23.5-56.5T400-720q-33 0-56.5 23.5T320-640q0 33 23.5 56.5T400-560Zm0-80Z" />
                                        </svg>
                                        <!-- Text -->
                                        Voter (Election Voter)
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.system.user') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.system.user') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 -960 960 960" fill="currentColor">
                                            <path
                                                d="M672-288q25 0 42.5-17.5T732-348q0-25-17.5-42.5T672-408q-25 0-42.5 17.5T612-348q0 25 17.5 42.5T672-288Zm-.09 120q32.47 0 59.28-15.81 26.81-15.81 43.19-41.81Q751-239 725.28-245.5q-25.72-6.5-53.5-6.5t-53.28 7q-25.5 7-48.88 19.38 16.38 26 43.1 41.81Q639.44-168 671.91-168ZM480-116.77q-123.77-30.39-207.88-143.77Q188-373.92 188-515v-215.15l292-112.31 292 112.31v220.61q-17-7.69-29.39-11.77-12.38-4.07-22.61-5.69v-168.38L480-787l-240 91.62V-515q0 55.15 15 105.81 15 50.65 42.85 93.69 27.84 43.04 66.96 76.58 39.11 33.54 85.96 56.46l1.16-.39q4.92 13 17.03 29.66 12.12 16.65 26.19 32.11-1.53.77-7.57 2.16-6.04 1.38-7.58 2.15Zm191.77.77q-71.69 0-121.73-50.27Q500-216.53 500-288.23q0-71.69 50.27-121.73Q600.53-460 672.23-460q71.69 0 121.73 50.27Q844-359.47 844-287.77q0 71.69-50.27 121.73Q743.47-116 671.77-116ZM480-474.23Z" />
                                        </svg>
                                        <!-- Text -->
                                        System user (Admins)
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @canany(['view colleges', 'view programs', 'view majors'])
                        <li x-data="{ isActive: false, open: {{ request()->routeIs('admin.college') || request()->routeIs('admin.program') || request()->routeIs('admin.program.major*') ? 'true' : 'false' }} }"
                            class="relative group mb-2">
                            <!-- Parent Button -->
                            <button @click="open = !open"
                                class="flex justify-between items-center px-4 py-2 w-full rounded-md transition duration-200
                                                                                                             {{ request()->routeIs('admin.college*') || request()->routeIs('admin.program*') || request()->routeIs('admin.program.major*') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                <div class="flex items-center space-x-1">

                                    <!-- SVG Icon -->
                                    <svg class="icon mr-1 w-4 h-4"
                                        :class="isActive ? 'text-white fill-white' : 'text-[#757575] fill-[#757575]'"
                                        fill="currentColor" viewBox="0 0 32 32" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <title>school</title>
                                            <path
                                                d="M30 20.75h-5.75v-7.596l5.129 2.931c0.178 0.104 0.393 0.165 0.621 0.165 0.69 0 1.25-0.56 1.25-1.25 0-0.462-0.251-0.865-0.623-1.082l-0.006-0.003-12.932-7.389c0.373-0.014 0.728-0.064 1.070-0.148l-0.036 0.007c0.291-0.057 0.626-0.089 0.969-0.089 0.363 0 0.717 0.036 1.059 0.106l-0.034-0.006c0.061 0.011 0.131 0.017 0.203 0.017 0.69 0 1.25-0.56 1.25-1.25v0-2.812c0-0 0-0 0-0 0-0.592-0.412-1.088-0.964-1.217l-0.008-0.002c-0.453-0.117-0.973-0.185-1.509-0.185-0.541 0-1.067 0.069-1.568 0.198l0.043-0.010c-0.254 0.059-0.546 0.093-0.845 0.093-0.13 0-0.259-0.006-0.386-0.019l0.016 0.001c-0.225-0.28-0.566-0.459-0.948-0.463h-0.001c-0.69 0-1.25 0.56-1.25 1.25v0 4.275l-13.37 7.64c-0.379 0.219-0.63 0.623-0.63 1.085 0 0.69 0.56 1.25 1.25 1.25 0.228 0 0.442-0.061 0.626-0.168l-0.006 0.003 5.13-2.931v7.596h-5.75c-0.69 0-1.25 0.56-1.25 1.25v8c0 0.69 0.56 1.25 1.25 1.25h28c0.69-0.001 1.249-0.56 1.25-1.25v-8c-0.001-0.69-0.56-1.249-1.25-1.25h-0zM18.791 3.557c0.246-0.069 0.528-0.109 0.819-0.109 0.021 0 0.042 0 0.063 0.001l-0.003-0v0.352c-0.555 0.010-1.090 0.068-1.608 0.171l0.058-0.009c-0.258 0.076-0.554 0.119-0.86 0.119-0.001 0-0.002 0-0.003 0h-0.007v-0.336c0.547-0 1.078-0.069 1.586-0.197l-0.045 0.010zM3.25 23.25h4.5v5.5h-4.5zM10.25 22v-10.275l5.75-3.286 5.75 3.286v17.025h-1.5v-4.75c-0.001-0.69-0.56-1.249-1.25-1.25h-6c-0.69 0-1.25 0.56-1.25 1.25v4.75h-1.5zM14.25 28.75v-3.5h3.5v3.5zM28.75 28.75h-4.5v-5.5h4.5zM16 20.25c2.347 0 4.25-1.903 4.25-4.25s-1.903-4.25-4.25-4.25c-2.347 0-4.25 1.903-4.25 4.25v0c0.002 2.346 1.904 4.247 4.25 4.25h0zM16 14.25c0.966 0 1.75 0.784 1.75 1.75s-0.784 1.75-1.75 1.75c-0.966 0-1.75-0.784-1.75-1.75v0c0.001-0.966 0.784-1.749 1.75-1.75h0z">
                                            </path>
                                        </g>
                                    </svg>
                                    <span class="text-[12px] font-normal">Manage University</span>
                                </div>

                                <!-- Dropdown Arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <ul x-show="open" x-collapse class="mt-1 ml-10 border-l-2 border-gray-300 space-y-1">
                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.college') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.college') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">
                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" fill="currentColor" height="16" width="20" version="1.1"
                                            id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 325.262 325.262"
                                            xml:space="preserve">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path
                                                    d="M276.397,325.262c-3.161,0-6.154-1.885-7.416-4.994l-22.086-54.433c-12.342-30.408-26.366-47.908-44.821-54.943 l-31.442,43.087v40.753c0,4.418-3.582,8-8,8s-8-3.582-8-8v-40.756l-31.441-43.084c-18.458,7.033-32.48,24.532-44.823,54.944 l-22.085,54.432c-1.661,4.093-6.323,6.065-10.421,4.405c-4.094-1.661-6.066-6.326-4.405-10.421L63.54,259.82 c8.52-20.99,17.513-35.853,28.301-46.774c14.288-14.464,31.952-21.798,52.502-21.798h36.575c20.55,0,38.214,7.334,52.502,21.798 c10.788,10.922,19.781,25.784,28.301,46.773l22.086,54.434c1.661,4.094-0.312,8.76-4.405,10.421 C278.416,325.073,277.398,325.262,276.397,325.262z M140.415,207.354l22.217,30.443l22.217-30.443 c-1.29-0.071-2.601-0.106-3.93-0.106h-36.575C143.015,207.248,141.705,207.283,140.415,207.354z M162.63,168.944 c-29.225,0-53-23.776-53-53.002c0-7.418,1.495-14.566,4.448-21.279l-1.436-41.021L72.921,41.777 c-3.389-1.012-5.711-4.129-5.711-7.665s2.322-6.653,5.711-7.665l87.422-26.111c1.492-0.447,3.086-0.447,4.578,0l87.233,26.055 c0.826,0.225,1.599,0.578,2.296,1.037c1.045,0.688,1.909,1.608,2.528,2.679c0.28,0.483,0.511,0.998,0.686,1.539 c0.254,0.781,0.39,1.612,0.39,2.467c0,0.059-0.001,0.117-0.002,0.176V67.61c0,4.418-3.582,8-8,8s-8-3.582-8-8V44.851l-29.413,8.785 l-1.314,41.351c2.859,6.625,4.308,13.665,4.308,20.955C215.632,145.167,191.856,168.944,162.63,168.944z M127.156,105.374 c-1.014,3.4-1.525,6.938-1.525,10.568c0,20.403,16.598,37.002,37,37.002c20.403,0,37.002-16.599,37.002-37.002 c0-3.522-0.482-6.959-1.439-10.271c-11.318,1.898-22.556,2.76-35.561,2.76C150.426,108.43,139.24,107.477,127.156,105.374z M129.911,89.612c11.159,1.938,21.479,2.818,32.721,2.818c12.053,0,22.435-0.79,32.847-2.529l0.998-31.438l-31.556,9.425 c-1.492,0.447-3.086,0.447-4.578,0l-31.521-9.414L129.911,89.612z M122.89,40.003l39.742,11.87l39.761-11.875 c0.094-0.03,0.188-0.059,0.283-0.085l19.425-5.802L162.632,16.35l-59.469,17.762l19.402,5.795 C122.674,39.937,122.782,39.969,122.89,40.003z">
                                                </path>
                                            </g>
                                        </svg>
                                        <!-- Text -->
                                        Manage College
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.program') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.program') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M480-190.16 236-311.39v-216.92L89.54-600 480-792.92 870.46-600v279.69h-52v-254.46L724-528.31v216.92L480-190.16ZM480-465l270.62-135L480-735 209.38-600 480-465Zm0 216.54 192-96v-157.85l-192 95.16-192-95.16v157.85l192 96ZM480-465Zm0 63.31Zm0 0Z" />
                                        </svg>

                                        <!-- Text -->
                                        Manage Program
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.program.major') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.program.major') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" width="20" height="20" viewBox="0 0 16 16" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            fill="currentColor">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                            </g>
                                            <g id="SVGRepo_iconCarrier">
                                                <path fill="757575"
                                                    d="M12.61 8.41c-0.53-0.079-1.008-0.223-1.454-0.424 2.104-1.876 4.424-3.536 4.454-3.556l0.1-0.070 0.060-0.11c0.177-0.367 0.281-0.797 0.281-1.252 0-0.901-0.407-1.707-1.046-2.244-0.523-0.482-1.219-0.776-1.983-0.776-0.538 0-1.043 0.146-1.476 0.4l-0.126 0.133c-1.578 2.181-3.182 4.099-4.908 5.899-1.836 1.638-3.87 3.195-6.018 4.592l-0.394 0.248v0.23c-0.077 0.314-0.122 0.675-0.122 1.046 0 0.97 0.304 1.87 0.822 2.609 0.507 0.53 1.237 0.87 2.045 0.87 0.055 0 0.109-0.002 0.162-0.005 0.026 0.002 0.065 0.003 0.104 0.003 0.701 0 1.317-0.36 1.674-0.905 0.245-0.308 2.065-2.608 4.005-4.708 0.268 0.464 0.476 1.003 0.594 1.575 0.032 0.249 0.046 0.496 0.046 0.747 0 0.823-0.158 1.61-0.445 2.331l1.685-2.043 1.33 1c-0.041-1.174-0.243-2.286-0.584-3.336-0.227-0.416-0.542-0.845-0.915-1.214 0.406 0.346 0.871 0.643 1.372 0.874 0.94 0.338 1.989 0.572 3.076 0.672l-0.949-1.266 2-1.73c-0.83 0.273-1.785 0.431-2.777 0.431-0.216 0-0.43-0.007-0.642-0.022zM12.16 1.18c0.246-0.123 0.536-0.194 0.842-0.194 0.506 0 0.966 0.196 1.309 0.516 0.441 0.356 0.721 0.897 0.721 1.504 0 0.242-0.045 0.474-0.126 0.688-0.486 0.307-2.346 1.717-4.146 3.307-0.055-0.521-0.302-0.975-0.668-1.298-0.28-0.239-0.643-0.384-1.039-0.384-0.068 0-0.135 0.004-0.201 0.012 1.568-1.771 2.978-3.691 3.308-4.151zM2.7 11.81c0.073-0.051 0.164-0.082 0.262-0.082 0.014 0 0.027 0.001 0.040 0.002l0.068-0c0.179 0.052 0.334 0.142 0.461 0.261l-0.871 0.719c-0.081-0.165-0.128-0.358-0.128-0.563 0-0.052 0.003-0.103 0.009-0.153 0.027-0.077 0.084-0.144 0.158-0.183zM4 14.5c-0.175 0.306-0.499 0.508-0.871 0.508-0.046 0-0.090-0.003-0.134-0.009-0.046 0.006-0.106 0.008-0.167 0.008-0.515 0-0.981-0.209-1.318-0.548-0.365-0.54-0.583-1.206-0.583-1.922 0-0.251 0.027-0.495 0.077-0.73l0.706-0.457c-0.094 0.14-0.164 0.304-0.199 0.481-0.007 0.076-0.010 0.154-0.010 0.234 0 0.642 0.202 1.237 0.545 1.724l0.354 0.44 1.7-1.4c0.066 0.209 0.104 0.45 0.104 0.7 0 0.351-0.075 0.685-0.21 0.985zM4.86 12.050c-0.345-0.6-0.889-1.053-1.54-1.274-0.071-0.012-0.13-0.016-0.19-0.016s-0.119 0.004-0.177 0.010c-0.046-0.007-0.106-0.011-0.168-0.011s-0.122 0.004-0.182 0.011c1.489-1.018 2.766-2.003 3.988-3.052 0.398 0.071 0.812 0.25 1.131 0.533 0.297 0.313 0.48 0.739 0.48 1.209 0 0.032-0.001 0.063-0.002 0.094-1.14 1.226-2.25 2.536-3 3.506-0.054-0.379-0.177-0.719-0.357-1.023z">
                                                </path>
                                            </g>
                                        </svg>

                                        <!-- Text -->
                                        Manage Major
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcanany

                    @can('view website management')
                        <li x-data="{ isActive: false, open: {{ request()->routeIs('admin.announcement') || request()->routeIs('admin.feedback') ? 'true' : 'false' }} }"
                            class="relative group mb-2">
                            <!-- Parent Button -->
                            <button @click="open = !open"
                                class="flex justify-between items-center px-4 py-2 w-full rounded-md transition duration-200
                                                                                                             {{ request()->routeIs('admin.announcement*') || request()->routeIs('admin.feedback*') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                <div class="flex items-center space-x-1 ">
                                    <!-- SVG Icon -->
                                    <svg class="icon mr-1"
                                        :class="isActive ? 'text-white fill-white' : 'text-[#757575] fill-[#757575]'"
                                        xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="currentColor">
                                        <path
                                            d="M480-96q-79 0-149-30t-122.5-82.5Q156-261 126-331T96-480q0-80 30-149.5t82.5-122Q261-804 331-834t149-30q80 0 149.5 30t122 82.5Q804-699 834-629.5T864-480q0 79-30 149t-82.5 122.5Q699-156 629.5-126T480-96Zm0-75q17-17 34-63.5T540-336H420q9 55 26 101.5t34 63.5Zm-91-10q-14-30-24.5-69T347-336H204q29 57 77 97.5T389-181Zm182 0q60-17 108-57.5t77-97.5H613q-7 47-17.5 86T571-181ZM177-408h161q-2-19-2.5-37.5T335-482q0-18 .5-35.5T338-552H177q-5 19-7 36.5t-2 35.5q0 18 2 35.5t7 36.5Zm234 0h138q2-20 2.5-37.5t.5-34.5q0-17-.5-35t-2.5-37H411q-2 19-2.5 37t-.5 35q0 17 .5 35t2.5 37Zm211 0h161q5-19 7-36.5t2-35.5q0-18-2-36t-7-36H622q2 19 2.5 37.5t.5 36.5q0 18-.5 35.5T622-408Zm-9-216h143q-29-57-77-97.5T571-779q14 30 24.5 69t17.5 86Zm-193 0h120q-9-55-26-101.5T480-789q-17 17-34 63.5T420-624Zm-216 0h143q7-47 17.5-86t24.5-69q-60 17-108 57.5T204-624Z" />
                                    </svg>
                                    <span class="text-[12px] font-normal">Manage Website</span>
                                </div>

                                <!-- Dropdown Arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <ul x-show="open" x-collapse class="mt-1 ml-10 border-l-2 border-gray-300 space-y-1">
                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.announcement') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.announcement') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M720-444v-72h144v72H720Zm41 276-118-82 42-59 118 82-42 59Zm-77-483-41-59 118-82 41 59-118 82ZM192-192v-192h-24q-29.7 0-50.85-21.15Q96-426.3 96-456v-48q0-29.7 21.15-50.85Q138.3-576 168-576h139l221-132v456L313-384h-25v192h-96Zm264-189v-200l-129 77H168v48h166l122 75Zm120 18v-234q23 22 35.5 53t12.5 64q0 33-12.5 64T576-363ZM312-481Z" />
                                        </svg>
                                        <!-- Text -->
                                        Announcement
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.feedback') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.feedback') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                        <!-- SVG Icon -->
                                        <svg class="icon mr-2" xmlns="http://www.w3.org/2000/svg" height="20px"
                                            viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                            <path
                                                d="M479.79-360q15.21 0 25.71-10.29t10.5-25.5q0-15.21-10.29-25.71t-25.5-10.5q-15.21 0-25.71 10.29t-10.5 25.5q0 15.21 10.29 25.71t25.5 10.5ZM444-480h72v-264h-72v264ZM96-96v-696q0-29.7 21.15-50.85Q138.3-864 168-864h624q29.7 0 50.85 21.15Q864-821.7 864-792v480q0 29.7-21.15 50.85Q821.7-240 792-240H240L96-96Zm114-216h582v-480H168v522l42-42Zm-42 0v-480 480Z" />
                                        </svg>

                                        <!-- Text -->
                                        Feedback
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @if(auth()->user()->hasRole('technical_officer'))
                        <li x-data="{isActive: false}"
                            class="group flex items-center space-x-2 w-full px-4 py-2 mb-2 rounded-md
                                                                                                    {{ request()->routeIs('technical-officer.active.user') ? 'bg-black text-white' : 'hover:bg-black hover:text-white' }}">
                            <div class="flex items-center space-x-1 ">
                                <svg class="icon w-[15px] h-[15px] mr-2 text-[#757575] fill-[#757575]
                                                                                                    {{ request()->routeIs('technical-officer.active.user') ? 'text-white fill-white' : 'group-hover:text-white group-hover:fill-white' }}"
                                    width="18" height="18" viewBox="0 0 18 18" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.825 13.1381C15.2577 12.6216 15.4964 11.9702 15.5 11.2964C15.5 10.5229 15.1927 9.781 14.6457 9.23401C14.0987 8.68703 13.3569 8.37974 12.5833 8.37974H12.35C13.0372 7.56307 13.4149 6.53043 13.4167 5.46308C13.4186 4.69353 13.2268 3.93589 12.8588 3.26001C12.4909 2.58413 11.9586 2.01178 11.3113 1.59574C10.6639 1.17969 9.92215 0.933352 9.1545 0.87943C8.38684 0.825509 7.61797 0.965743 6.91878 1.2872C6.21959 1.60866 5.6126 2.10099 5.15377 2.71879C4.69495 3.3366 4.39907 4.05998 4.29342 4.82224C4.18776 5.5845 4.27574 6.36109 4.54923 7.0804C4.82273 7.7997 5.27293 8.43857 5.85833 8.93808C4.28356 9.53993 2.92835 10.6053 1.97168 11.9934C1.01501 13.3815 0.501862 15.0272 0.5 16.7131C0.5 16.9341 0.587797 17.146 0.744078 17.3023C0.900358 17.4586 1.11232 17.5464 1.33333 17.5464C1.55435 17.5464 1.76631 17.4586 1.92259 17.3023C2.07887 17.146 2.16667 16.9341 2.16667 16.7131C2.16667 14.945 2.86905 13.2493 4.11929 11.999C5.36953 10.7488 7.06522 10.0464 8.83333 10.0464H9.95833C9.71423 10.5431 9.62048 11.1002 9.68857 11.6495C9.75667 12.1987 9.98366 12.7161 10.3417 13.1381C9.63173 13.5352 9.0405 14.1144 8.6289 14.816C8.2173 15.5176 8.00021 16.3163 8 17.1297C8 17.3508 8.0878 17.5627 8.24408 17.719C8.40036 17.8753 8.61232 17.9631 8.83333 17.9631C9.05435 17.9631 9.26631 17.8753 9.42259 17.719C9.57887 17.5627 9.66667 17.3508 9.66667 17.1297C9.66667 16.3562 9.97396 15.6143 10.5209 15.0673C11.0679 14.5204 11.8098 14.2131 12.5833 14.2131C13.3569 14.2131 14.0987 14.5204 14.6457 15.0673C15.1927 15.6143 15.5 16.3562 15.5 17.1297C15.5 17.3508 15.5878 17.5627 15.7441 17.719C15.9004 17.8753 16.1123 17.9631 16.3333 17.9631C16.5543 17.9631 16.7663 17.8753 16.9226 17.719C17.0789 17.5627 17.1667 17.3508 17.1667 17.1297C17.1665 16.3163 16.9494 15.5176 16.5378 14.816C16.1262 14.1144 15.5349 13.5352 14.825 13.1381ZM8.83333 8.37974C8.25647 8.37974 7.69256 8.20868 7.21292 7.8882C6.73328 7.56771 6.35944 7.11219 6.13868 6.57924C5.91793 6.04628 5.86017 5.45984 5.97271 4.89406C6.08525 4.32829 6.36304 3.80859 6.77094 3.40068C7.17884 2.99278 7.69854 2.71499 8.26432 2.60245C8.8301 2.48991 9.41654 2.54767 9.94949 2.76843C10.4824 2.98918 10.938 3.36302 11.2585 3.84266C11.5789 4.32231 11.75 4.88621 11.75 5.46308C11.75 6.23662 11.4427 6.97849 10.8957 7.52547C10.3487 8.07245 9.60688 8.37974 8.83333 8.37974ZM12.5833 12.5464C12.2518 12.5464 11.9339 12.4147 11.6995 12.1803C11.465 11.9459 11.3333 11.6279 11.3333 11.2964C11.3355 10.9656 11.4679 10.6489 11.7019 10.4149C11.9358 10.181 12.2525 10.0486 12.5833 10.0464C12.9149 10.0464 13.2328 10.1781 13.4672 10.4125C13.7016 10.6469 13.8333 10.9649 13.8333 11.2964C13.8333 11.6279 13.7016 11.9459 13.4672 12.1803C13.2328 12.4147 12.9149 12.5464 12.5833 12.5464Z"
                                        fill="currentColor" />
                                </svg>

                                <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('technical-officer.active.user') : '#' }}"
                                    class="text-[12px] font-normal text-[#757575]
                                                                                                        {{ request()->routeIs('technical-officer.active.user') ? 'text-white' : 'group-hover:text-white' }}">
                                    Active Users
                                </a>
                            </div>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('technical_officer'))
                        <li x-data="{isActive: false}"
                            class="group flex items-center space-x-2 w-full px-4 py-2 mb-2 rounded-md
                                                                                                {{ request()->routeIs('technical-officer.ip.records') ? 'bg-black text-white' : 'hover:bg-black hover:text-white' }}">
                            <div class="flex items-center space-x-1 ">

                                <svg class="icon w-[15px] h-[15px] mr-2 text-[#757575] fill-[#757575]
                                                                                                        {{ request()->routeIs('technical-officer.ip.records') ? 'text-white fill-white' : 'group-hover:text-white group-hover:fill-white' }}"
                                    width="15" height="19" viewBox="0 0 15 19" fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.16667 6.91569C3.70643 6.91569 3.33333 7.28879 3.33333 7.74902C3.33333 8.20926 3.70643 8.58236 4.16667 8.58236H10.8333C11.2936 8.58236 11.6667 8.20926 11.6667 7.74902C11.6667 7.28879 11.2936 6.91569 10.8333 6.91569H4.16667Z"
                                        fill="currentColor" />
                                    <path
                                        d="M4.16667 10.249C3.70643 10.249 3.33333 10.6221 3.33333 11.0824C3.33333 11.5426 3.70643 11.9157 4.16667 11.9157H7.5C7.96024 11.9157 8.33333 11.5426 8.33333 11.0824C8.33333 10.6221 7.96024 10.249 7.5 10.249H4.16667Z"
                                        fill="currentColor" />
                                    <path
                                        d="M4.16667 13.5824C3.70643 13.5824 3.33333 13.9555 3.33333 14.4157C3.33333 14.8759 3.70643 15.249 4.16667 15.249H10.8333C11.2936 15.249 11.6667 14.8759 11.6667 14.4157C11.6667 13.9555 11.2936 13.5824 10.8333 13.5824H4.16667Z"
                                        fill="currentColor" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.899 3.69701C14.8319 3.5347 14.7335 3.38725 14.6093 3.2631L11.9863 0.640135C11.7365 0.390015 11.3976 0.249335 11.0441 0.249023H1.33333C0.979711 0.249023 0.640573 0.389499 0.390524 0.639548C0.140476 0.889596 0 1.22873 0 1.58236V17.249C0 17.4241 0.0344879 17.5975 0.101494 17.7593C0.168499 17.921 0.266711 18.068 0.390524 18.1918C0.514334 18.3156 0.661319 18.4139 0.823089 18.4809C0.984861 18.5479 1.15824 18.5824 1.33333 18.5824H13.6667C13.8418 18.5824 14.0151 18.5479 14.1769 18.4809C14.3387 18.4139 14.4857 18.3156 14.6095 18.1918C14.7333 18.068 14.8315 17.921 14.8985 17.7593C14.9655 17.5975 15 17.4241 15 17.249V4.20761L14.1667 4.20736L15 4.2094L15 4.20761C15.0002 4.03243 14.9659 3.85893 14.899 3.69701ZM10 3.99902C10 4.68938 10.5596 5.24902 11.25 5.24902H13.3333V16.9157H1.66667V1.91569H10V3.99902ZM12.5715 3.58236L11.6667 2.67753V3.58236H12.5715Z"
                                        fill="currentColor" />
                                </svg>

                                <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('technical-officer.ip.records') : '#' }}"
                                    class="text-[12px] font-normal text-[#757575]
                                                                                                            {{ request()->routeIs('technical-officer.ip.records') ? 'text-white' : 'group-hover:text-white' }}">
                                    IP Records
                                </a>
                            </div>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('technical_officer'))
                        <li x-data="{isActive: false}"
                            class="group flex items-center space-x-2 w-full px-4 py-2 mb-2 rounded-md
                                                                                                {{ request()->routeIs('technical-officer.database.backup') ? 'bg-black text-white' : 'hover:bg-black hover:text-white' }}">
                            <div class="flex items-center space-x-1 ">

                                <svg class="icon w-[15px] h-[15px] mr-2 text-[#757575] fill-[#757575]
                                                                                                                {{ request()->routeIs('technical-officer.database.backup') ? 'text-white fill-white' : 'group-hover:text-white group-hover:fill-white' }}"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M12 0C6.48 0 2 2.24 2 5v14c0 2.76 4.48 5 10 5s10-2.24 10-5V5c0-2.76-4.48-5-10-5zm0 2c4.41 0 8 1.79 8 4s-3.59 4-8 4-8-1.79-8-4 3.59-4 8-4zm0 18c-4.41 0-8-1.79-8-4v-2c1.68 1.25 4.39 2 8 2s6.32-.75 8-2v2c0 2.21-3.59 4-8 4zm0-6c-4.41 0-8-1.79-8-4V8c1.68 1.25 4.39 2 8 2s6.32-.75 8-2v2c0 2.21-3.59 4-8 4z" />
                                    <path d="M15 13h-2v3.59l-1.3-1.29-1.4 1.41 4 4 4-4-1.4-1.41-1.3 1.29V13z" />
                                </svg>

                                <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('technical-officer.database.backup') : '#' }}"
                                    class="text-[12px] font-normal text-[#757575]
                                                                                                            {{ request()->routeIs('technical-officer.database.backup') ? 'text-white' : 'group-hover:text-white' }}">
                                    Database Backup
                                </a>
                            </div>
                        </li>
                    @endif

                    @can('view system logs')
                        <li x-data="{ isActive: false, open: {{ request()->routeIs('admin.system.logs') ? 'true' : 'false' }} }"
                            class="relative group mb-2">
                            <!-- Parent Button -->
                            <button @click="open = !open"
                                class="flex justify-between items-center px-4 py-2 w-full rounded-md transition duration-200
                                                                                                             {{ request()->routeIs('admin.system.logs*') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                <div class="flex items-center space-x-1 ">
                                    <!-- SVG Icon -->
                                    <svg class="icon mr-1"
                                        :class="isActive ? 'text-white fill-white' : 'text-[#757575] fill-[#757575]'"
                                        xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                        width="20px" fill="currentColor">
                                        <path
                                            d="M479.52-135.87q-143.35 0-243.74-100.27Q135.39-336.41 135.63-480h82.76q.24 107.8 76.78 184.47 76.55 76.66 184.35 76.66 107.81 0 184.47-76.66Q740.65-372.2 740.65-480t-76.66-184.47q-76.66-76.66-184.47-76.66-59.13 0-109.76 23.57-50.64 23.56-85.83 65.49h100.31V-576H137.78v-245.98h75.59v126.41q47.2-59.52 115.93-94.04 68.74-34.52 150.22-34.52 71.44 0 134.12 27.2 62.69 27.2 109.15 73.66 46.47 46.46 73.66 109.15 27.2 62.69 27.2 134.12t-27.2 134.12q-27.19 62.69-73.66 109.15-46.46 46.46-109.15 73.66-62.68 27.2-134.12 27.2Zm98.81-206.22-136-136V-672h75.58v162L632.2-395.96l-53.87 53.87Z" />
                                    </svg>
                                    <span class="text-[12px] font-normal">Logs</span>
                                </div>

                                <!-- Dropdown Arrow -->
                                <svg class="w-4 h-4 transform transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <ul x-show="open" x-collapse class="mt-1 ml-10 border-l-2 border-gray-300 space-y-1">
                                <li>
                                    <a href="{{ auth()->user()->hasAnyRole($rolesExceptVoter) ? route('admin.system.logs') : '#' }}"
                                        class="flex items-center block px-3 py-2 text-[11px] transition duration-200 rounded-md
                                                                                                                {{ request()->routeIs('admin.system.logs') ? 'bg-black text-white' : 'text-[#757575] hover:bg-black hover:text-white' }}">

                                        <!-- SVG Icon -->

                                        <svg class="icon mr-2" width="16" height="16" viewBox="0 0 21 21"
                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <mask id="path-1-outside-1_1463_1168" maskUnits="userSpaceOnUse" x="-0.5"
                                                y="-0.583984" width="22" height="22" fill="currentColor">
                                                <rect fill="currentColor" x="-0.5" y="-0.583984" width="22" height="22" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6.3715 0.483923C5.61942 0.6864 5.06685 1.2581 4.91017 1.99577C4.87593 2.15711 4.85902 3.0397 4.85902 4.66797C4.85902 6.5735 4.84674 7.10292 4.80218 7.11638C3.58256 7.48464 2.87435 7.88855 2.13095 8.63982C-0.0514548 10.8453 -0.0424975 14.3842 2.15105 16.5773C2.88358 17.3097 3.59783 17.7139 4.80218 18.0776C4.83865 18.0886 4.85902 18.184 4.85902 18.3439C4.85902 19.3225 5.4571 20.105 6.39096 20.3482C6.61544 20.4067 7.48105 20.416 12.6641 20.416C16.9457 20.416 18.7461 20.4013 18.9177 20.3649C19.4598 20.2498 20.0142 19.8354 20.2509 19.3684C20.5112 18.8547 20.4999 19.1822 20.4999 12.1651C20.4999 5.20439 20.5087 5.4742 20.2682 4.99251C20.1357 4.72693 16.2433 0.826143 15.9535 0.668444C15.4751 0.408097 15.5849 0.413552 10.908 0.416825C7.35906 0.41928 6.56802 0.430963 6.3715 0.483923ZM14.3163 3.13917C14.3163 4.17696 14.335 4.8614 14.3675 5.01437C14.5263 5.76209 15.1507 6.38639 15.8985 6.54518C16.0515 6.57764 16.7363 6.59632 17.7748 6.59632H19.41L19.398 12.6538L19.386 18.7113L19.2781 18.8848C19.2187 18.9803 19.0872 19.1132 18.9859 19.1803L18.8015 19.3023H12.6795H6.55742L6.3731 19.1803C6.12907 19.0188 5.99508 18.7913 5.96343 18.4848L5.93751 18.234L6.22805 18.2337C6.59684 18.2334 7.28295 18.1286 7.72349 18.0053C8.53986 17.7769 9.46358 17.224 10.1118 16.576C11.798 14.89 12.2434 12.3384 11.2248 10.1993C10.9252 9.5701 10.5996 9.10864 10.1104 8.61945C9.23361 7.74285 8.19121 7.21748 6.95054 7.02687C6.71297 6.99041 6.39074 6.96041 6.23442 6.96027L5.95024 6.96L5.95052 4.6302C5.95065 3.13021 5.96789 2.23862 5.99894 2.12697C6.05782 1.91494 6.34827 1.61459 6.55092 1.55618C6.63935 1.53067 8.25673 1.5114 10.5084 1.50894L14.3163 1.50485V3.13917ZM17.3951 3.51812C18.4276 4.55046 19.2723 5.42083 19.2723 5.45233C19.2723 5.49752 18.9307 5.50675 17.6468 5.49606C16.0263 5.48261 16.0208 5.48224 15.8478 5.37469C15.7523 5.31536 15.6193 5.18389 15.5523 5.08261L15.4303 4.89841L15.4168 3.26982C15.4061 1.98345 15.4153 1.64123 15.4605 1.64123C15.492 1.64123 16.3625 2.48582 17.3951 3.51812ZM9.56493 5.54761C9.36396 5.63303 9.24666 5.81951 9.24666 6.05358C9.24666 6.24455 9.26785 6.29756 9.39529 6.42498L9.54393 6.57359H10.8608H12.1776L12.3262 6.42498C12.4542 6.29706 12.4749 6.24492 12.4749 6.05081C12.4749 5.85674 12.4541 5.80451 12.3263 5.67663L12.1777 5.52802L10.9168 5.51847C10.2233 5.51325 9.61495 5.52634 9.56493 5.54761ZM6.88079 8.12021C8.70241 8.43784 10.098 9.74112 10.5548 11.5514C10.6713 12.0131 10.6916 13.0377 10.5931 13.4834C10.2813 14.8945 9.40602 16.0314 8.14652 16.6612C6.96681 17.2513 5.53962 17.2903 4.33614 16.7654C1.9489 15.7243 0.909415 12.8923 2.06684 10.5829C2.96942 8.78192 4.92836 7.77981 6.88079 8.12021ZM13.2024 8.45734C13.0017 8.5419 12.8841 8.72883 12.8841 8.96299C12.8841 9.15396 12.9053 9.20697 13.0327 9.33439L13.1813 9.483H15.2257H17.27L17.4186 9.33439C17.5466 9.20647 17.5673 9.15433 17.5673 8.96022C17.5673 8.7661 17.5465 8.71396 17.4187 8.58604L17.27 8.43743L15.2817 8.42825C14.188 8.42316 13.2524 8.43629 13.2024 8.45734ZM5.87359 9.95805C5.62051 10.1123 5.6106 10.161 5.5941 11.3241L5.57895 12.3924L5.08822 12.8945C4.57812 13.4165 4.50301 13.5492 4.56307 13.8225C4.60244 14.0019 4.87039 14.2335 5.03839 14.2335C5.19743 14.2335 5.41591 14.061 6.14357 13.361L6.67787 12.847L6.67782 11.5275C6.67773 10.0835 6.67682 10.0782 6.40442 9.93732C6.22628 9.84522 6.04718 9.85222 5.87359 9.95805ZM13.9298 11.3666C13.729 11.4515 13.6116 11.6383 13.6116 11.8724C13.6116 12.0634 13.6327 12.1164 13.7602 12.2438L13.9088 12.3924H15.5894H17.27L17.4186 12.2438C17.5466 12.1159 17.5673 12.0637 17.5673 11.8696C17.5673 11.6755 17.5465 11.6234 17.4187 11.4954L17.2701 11.3468L15.6454 11.3375C14.7518 11.3324 13.9799 11.3455 13.9298 11.3666ZM13.2024 14.2762C13.0017 14.3607 12.8841 14.5476 12.8841 14.7818C12.8841 14.9728 12.9053 15.0258 13.0327 15.1532L13.1813 15.3018H15.2257H17.27L17.4186 15.1532C17.5466 15.0253 17.5673 14.9731 17.5673 14.779C17.5673 14.5849 17.5465 14.5328 17.4187 14.4049L17.27 14.2563L15.2817 14.2471C14.188 14.242 13.2524 14.2551 13.2024 14.2762Z" />
                                            </mask>
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.3715 0.483923C5.61942 0.6864 5.06685 1.2581 4.91017 1.99577C4.87593 2.15711 4.85902 3.0397 4.85902 4.66797C4.85902 6.5735 4.84674 7.10292 4.80218 7.11638C3.58256 7.48464 2.87435 7.88855 2.13095 8.63982C-0.0514548 10.8453 -0.0424975 14.3842 2.15105 16.5773C2.88358 17.3097 3.59783 17.7139 4.80218 18.0776C4.83865 18.0886 4.85902 18.184 4.85902 18.3439C4.85902 19.3225 5.4571 20.105 6.39096 20.3482C6.61544 20.4067 7.48105 20.416 12.6641 20.416C16.9457 20.416 18.7461 20.4013 18.9177 20.3649C19.4598 20.2498 20.0142 19.8354 20.2509 19.3684C20.5112 18.8547 20.4999 19.1822 20.4999 12.1651C20.4999 5.20439 20.5087 5.4742 20.2682 4.99251C20.1357 4.72693 16.2433 0.826143 15.9535 0.668444C15.4751 0.408097 15.5849 0.413552 10.908 0.416825C7.35906 0.41928 6.56802 0.430963 6.3715 0.483923ZM14.3163 3.13917C14.3163 4.17696 14.335 4.8614 14.3675 5.01437C14.5263 5.76209 15.1507 6.38639 15.8985 6.54518C16.0515 6.57764 16.7363 6.59632 17.7748 6.59632H19.41L19.398 12.6538L19.386 18.7113L19.2781 18.8848C19.2187 18.9803 19.0872 19.1132 18.9859 19.1803L18.8015 19.3023H12.6795H6.55742L6.3731 19.1803C6.12907 19.0188 5.99508 18.7913 5.96343 18.4848L5.93751 18.234L6.22805 18.2337C6.59684 18.2334 7.28295 18.1286 7.72349 18.0053C8.53986 17.7769 9.46358 17.224 10.1118 16.576C11.798 14.89 12.2434 12.3384 11.2248 10.1993C10.9252 9.5701 10.5996 9.10864 10.1104 8.61945C9.23361 7.74285 8.19121 7.21748 6.95054 7.02687C6.71297 6.99041 6.39074 6.96041 6.23442 6.96027L5.95024 6.96L5.95052 4.6302C5.95065 3.13021 5.96789 2.23862 5.99894 2.12697C6.05782 1.91494 6.34827 1.61459 6.55092 1.55618C6.63935 1.53067 8.25673 1.5114 10.5084 1.50894L14.3163 1.50485V3.13917ZM17.3951 3.51812C18.4276 4.55046 19.2723 5.42083 19.2723 5.45233C19.2723 5.49752 18.9307 5.50675 17.6468 5.49606C16.0263 5.48261 16.0208 5.48224 15.8478 5.37469C15.7523 5.31536 15.6193 5.18389 15.5523 5.08261L15.4303 4.89841L15.4168 3.26982C15.4061 1.98345 15.4153 1.64123 15.4605 1.64123C15.492 1.64123 16.3625 2.48582 17.3951 3.51812ZM9.56493 5.54761C9.36396 5.63303 9.24666 5.81951 9.24666 6.05358C9.24666 6.24455 9.26785 6.29756 9.39529 6.42498L9.54393 6.57359H10.8608H12.1776L12.3262 6.42498C12.4542 6.29706 12.4749 6.24492 12.4749 6.05081C12.4749 5.85674 12.4541 5.80451 12.3263 5.67663L12.1777 5.52802L10.9168 5.51847C10.2233 5.51325 9.61495 5.52634 9.56493 5.54761ZM6.88079 8.12021C8.70241 8.43784 10.098 9.74112 10.5548 11.5514C10.6713 12.0131 10.6916 13.0377 10.5931 13.4834C10.2813 14.8945 9.40602 16.0314 8.14652 16.6612C6.96681 17.2513 5.53962 17.2903 4.33614 16.7654C1.9489 15.7243 0.909415 12.8923 2.06684 10.5829C2.96942 8.78192 4.92836 7.77981 6.88079 8.12021ZM13.2024 8.45734C13.0017 8.5419 12.8841 8.72883 12.8841 8.96299C12.8841 9.15396 12.9053 9.20697 13.0327 9.33439L13.1813 9.483H15.2257H17.27L17.4186 9.33439C17.5466 9.20647 17.5673 9.15433 17.5673 8.96022C17.5673 8.7661 17.5465 8.71396 17.4187 8.58604L17.27 8.43743L15.2817 8.42825C14.188 8.42316 13.2524 8.43629 13.2024 8.45734ZM5.87359 9.95805C5.62051 10.1123 5.6106 10.161 5.5941 11.3241L5.57895 12.3924L5.08822 12.8945C4.57812 13.4165 4.50301 13.5492 4.56307 13.8225C4.60244 14.0019 4.87039 14.2335 5.03839 14.2335C5.19743 14.2335 5.41591 14.061 6.14357 13.361L6.67787 12.847L6.67782 11.5275C6.67773 10.0835 6.67682 10.0782 6.40442 9.93732C6.22628 9.84522 6.04718 9.85222 5.87359 9.95805ZM13.9298 11.3666C13.729 11.4515 13.6116 11.6383 13.6116 11.8724C13.6116 12.0634 13.6327 12.1164 13.7602 12.2438L13.9088 12.3924H15.5894H17.27L17.4186 12.2438C17.5466 12.1159 17.5673 12.0637 17.5673 11.8696C17.5673 11.6755 17.5465 11.6234 17.4187 11.4954L17.2701 11.3468L15.6454 11.3375C14.7518 11.3324 13.9799 11.3455 13.9298 11.3666ZM13.2024 14.2762C13.0017 14.3607 12.8841 14.5476 12.8841 14.7818C12.8841 14.9728 12.9053 15.0258 13.0327 15.1532L13.1813 15.3018H15.2257H17.27L17.4186 15.1532C17.5466 15.0253 17.5673 14.9731 17.5673 14.779C17.5673 14.5849 17.5465 14.5328 17.4187 14.4049L17.27 14.2563L15.2817 14.2471C14.188 14.242 13.2524 14.2551 13.2024 14.2762Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.3715 0.483923C5.61942 0.6864 5.06685 1.2581 4.91017 1.99577C4.87593 2.15711 4.85902 3.0397 4.85902 4.66797C4.85902 6.5735 4.84674 7.10292 4.80218 7.11638C3.58256 7.48464 2.87435 7.88855 2.13095 8.63982C-0.0514548 10.8453 -0.0424975 14.3842 2.15105 16.5773C2.88358 17.3097 3.59783 17.7139 4.80218 18.0776C4.83865 18.0886 4.85902 18.184 4.85902 18.3439C4.85902 19.3225 5.4571 20.105 6.39096 20.3482C6.61544 20.4067 7.48105 20.416 12.6641 20.416C16.9457 20.416 18.7461 20.4013 18.9177 20.3649C19.4598 20.2498 20.0142 19.8354 20.2509 19.3684C20.5112 18.8547 20.4999 19.1822 20.4999 12.1651C20.4999 5.20439 20.5087 5.4742 20.2682 4.99251C20.1357 4.72693 16.2433 0.826143 15.9535 0.668444C15.4751 0.408097 15.5849 0.413552 10.908 0.416825C7.35906 0.41928 6.56802 0.430963 6.3715 0.483923ZM14.3163 3.13917C14.3163 4.17696 14.335 4.8614 14.3675 5.01437C14.5263 5.76209 15.1507 6.38639 15.8985 6.54518C16.0515 6.57764 16.7363 6.59632 17.7748 6.59632H19.41L19.398 12.6538L19.386 18.7113L19.2781 18.8848C19.2187 18.9803 19.0872 19.1132 18.9859 19.1803L18.8015 19.3023H12.6795H6.55742L6.3731 19.1803C6.12907 19.0188 5.99508 18.7913 5.96343 18.4848L5.93751 18.234L6.22805 18.2337C6.59684 18.2334 7.28295 18.1286 7.72349 18.0053C8.53986 17.7769 9.46358 17.224 10.1118 16.576C11.798 14.89 12.2434 12.3384 11.2248 10.1993C10.9252 9.5701 10.5996 9.10864 10.1104 8.61945C9.23361 7.74285 8.19121 7.21748 6.95054 7.02687C6.71297 6.99041 6.39074 6.96041 6.23442 6.96027L5.95024 6.96L5.95052 4.6302C5.95065 3.13021 5.96789 2.23862 5.99894 2.12697C6.05782 1.91494 6.34827 1.61459 6.55092 1.55618C6.63935 1.53067 8.25673 1.5114 10.5084 1.50894L14.3163 1.50485V3.13917ZM17.3951 3.51812C18.4276 4.55046 19.2723 5.42083 19.2723 5.45233C19.2723 5.49752 18.9307 5.50675 17.6468 5.49606C16.0263 5.48261 16.0208 5.48224 15.8478 5.37469C15.7523 5.31536 15.6193 5.18389 15.5523 5.08261L15.4303 4.89841L15.4168 3.26982C15.4061 1.98345 15.4153 1.64123 15.4605 1.64123C15.492 1.64123 16.3625 2.48582 17.3951 3.51812ZM9.56493 5.54761C9.36396 5.63303 9.24666 5.81951 9.24666 6.05358C9.24666 6.24455 9.26785 6.29756 9.39529 6.42498L9.54393 6.57359H10.8608H12.1776L12.3262 6.42498C12.4542 6.29706 12.4749 6.24492 12.4749 6.05081C12.4749 5.85674 12.4541 5.80451 12.3263 5.67663L12.1777 5.52802L10.9168 5.51847C10.2233 5.51325 9.61495 5.52634 9.56493 5.54761ZM6.88079 8.12021C8.70241 8.43784 10.098 9.74112 10.5548 11.5514C10.6713 12.0131 10.6916 13.0377 10.5931 13.4834C10.2813 14.8945 9.40602 16.0314 8.14652 16.6612C6.96681 17.2513 5.53962 17.2903 4.33614 16.7654C1.9489 15.7243 0.909415 12.8923 2.06684 10.5829C2.96942 8.78192 4.92836 7.77981 6.88079 8.12021ZM13.2024 8.45734C13.0017 8.5419 12.8841 8.72883 12.8841 8.96299C12.8841 9.15396 12.9053 9.20697 13.0327 9.33439L13.1813 9.483H15.2257H17.27L17.4186 9.33439C17.5466 9.20647 17.5673 9.15433 17.5673 8.96022C17.5673 8.7661 17.5465 8.71396 17.4187 8.58604L17.27 8.43743L15.2817 8.42825C14.188 8.42316 13.2524 8.43629 13.2024 8.45734ZM5.87359 9.95805C5.62051 10.1123 5.6106 10.161 5.5941 11.3241L5.57895 12.3924L5.08822 12.8945C4.57812 13.4165 4.50301 13.5492 4.56307 13.8225C4.60244 14.0019 4.87039 14.2335 5.03839 14.2335C5.19743 14.2335 5.41591 14.061 6.14357 13.361L6.67787 12.847L6.67782 11.5275C6.67773 10.0835 6.67682 10.0782 6.40442 9.93732C6.22628 9.84522 6.04718 9.85222 5.87359 9.95805ZM13.9298 11.3666C13.729 11.4515 13.6116 11.6383 13.6116 11.8724C13.6116 12.0634 13.6327 12.1164 13.7602 12.2438L13.9088 12.3924H15.5894H17.27L17.4186 12.2438C17.5466 12.1159 17.5673 12.0637 17.5673 11.8696C17.5673 11.6755 17.5465 11.6234 17.4187 11.4954L17.2701 11.3468L15.6454 11.3375C14.7518 11.3324 13.9799 11.3455 13.9298 11.3666ZM13.2024 14.2762C13.0017 14.3607 12.8841 14.5476 12.8841 14.7818C12.8841 14.9728 12.9053 15.0258 13.0327 15.1532L13.1813 15.3018H15.2257H17.27L17.4186 15.1532C17.5466 15.0253 17.5673 14.9731 17.5673 14.779C17.5673 14.5849 17.5465 14.5328 17.4187 14.4049L17.27 14.2563L15.2817 14.2471C14.188 14.242 13.2524 14.2551 13.2024 14.2762Z"
                                                stroke="currentColor" stroke-width="0.4"
                                                mask="url(#path-1-outside-1_1463_1168)" />
                                        </svg>

                                        <!-- Text -->
                                        System Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @php
                        $election = session('selectedElection') ? \App\Models\Election::find(session('selectedElection')) : null;
                        $isActive = '#';
                        if ($election) {
                            $isActive = request()->routeIs('dashboard', ['slug' => $election->slug]);
                        }
                    @endphp

                    <li class="group flex items-center mt-4 border border-red-800 w-full px-4 py-2 mb-2 rounded-md transition-all duration-200
                        {{ $isActive ? 'bg-black text-white shadow-md' : 'hover:bg-black hover:text-black' }}
                        ">
                        <div class="flex items-center space-x-1 w-full">

                            <!-- Link Text with Admin Indicator -->
                            <div class="flex justify-between items-center w-full">
                                @if($election)
                                    <a href="{{ route('dashboard', ['slug' => $election->slug]) }}"
                                        class="text-xs font-medium transition-colors duration-200
                                                                                                              {{ $isActive ? 'text-white' : 'text-red-700 group-hover:text-white' }}">
                                        Vote Now
                                    </a>


                                    <span
                                        class="text-[10px] font-semibold px-2 py-0.5 rounded-full
                                                                                                              {{ $isActive ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700' }}">
                                        Admin Access
                                    </span>

                                @else
                                    <span class="text-xs font-normal text-gray-400 cursor-not-allowed">
                                        Vote Now (No Election)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </li>


                </ul>

            </div>
        </div>
    </div>

    <!-- Global Election Selector Row -->
    <div class="flex items-center justify-between w-full bg-red-200 rounded-lg p-2">
        <div class="me-2">Selected Election:</div>
        <div
            class="flex items-center space-x-2 bg-gray-50 border border-gray-200 rounded-lg p-0 w-full shadow-sm transition-all hover:border-indigo-300">
            <select id="globalElection" wire:model.live="selectedElection"
                class="bg-transparent border-none text-[12px] font-semibold text-gray-700 focus:ring-0 cursor-pointer w-full p-1">
                @foreach($elections as $election)
                    <option value="{{ $election->id }}">
                        {{ $election->name }} - {{ $election->election_type->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>