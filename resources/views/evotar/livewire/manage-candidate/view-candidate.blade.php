<div x-data="{ enableEditDelete: false }">

    <style>
        .flip-card-container {
            perspective: 1000px;
            min-height: 320px;
        }

        .flip-card {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .flip-card-back {
            transform: rotateY(180deg);
        }

        .flip-card.flipped {
            transform: rotateY(180deg);
        }
    </style>
    <div class="hidden sm:block mb-4">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button wire:click="$set('filter', 'Student and Local Council Election')"
                        class=" whitespace-nowrap border-b-2 pb-1 px-1 text-[10px] font-medium {{ $filter === 'Student and Local Council Election' ? 'border-black text-black' : 'text-gray-500 hover:text-black' }}">
                    Student and Local Council Election
                </button>
                <button wire:click="$set('filter', 'Student Council Election')"
                        class="whitespace-nowrap border-b-2 pb-1 px-1 text-[10px] font-medium {{ $filter === 'Student Council Election' ? 'border-black text-black' : 'text-gray-500 hover:text-black' }}">
                    Student Council Election
                </button>
                <button wire:click="$set('filter', 'Local Council Election')"
                        class="whitespace-nowrap border-b-2 pb-1 px-1 text-[10px] font-medium {{ $filter === 'Local Council Election' ? 'border-black text-black' : 'text-gray-500 hover:text-black' }}">
                    Local Council Election
                </button>
                <button wire:click="$set('filter', 'Special Election')"
                        class="whitespace-nowrap border-b-2 pb-1 px-1 text-[10px] font-medium {{ $filter === 'Special Election' ? 'border-black text-black' : 'text-gray-500 hover:text-black' }}">
                    Special Election
                </button>
            </nav>
        </div>
    </div>
    <div class="flex w-full gap-4 min">
        <div id="Student and Local Council Election" class="w-full">
            <div class="flex-1 mb-3">
                <label for="candidate_election" class="text-xs font-semibold block mb-1">Select Election</label>
                <select name="selectedElection" id="candidate_election"
                        class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full "
                        wire:model.live="selectedElection">
                    <option value="" selected disabled>Select an election</option>
                    @if($selectedElection)
                        @foreach($elections as $election)
                            <option
                                value="{{ $election->id }}" {{ $election->id == $selectedElection ? 'selected' : '' }}>
                                {{ $election->name }} - {{ $election->campus->name }}
                                - {{$election->election_type->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="" selected>No Election Created Yet</option>
                    @endif
                </select>
                @error('selectedElection')
                <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                @enderror
            </div>
            <div class="bg-white shadow-md rounded p-6">
                <div
                    class="flex text-[12px] bg-white mt-0 p-5 rounded-md md:max-w-[800px] min-[90%]:max-w-[100%] lg:max-w-[900px] xl:w-[100%] xl:min-w-[100%] 2xl:max-w-[1190px]">
                    <div class="flex flex-col md:flex-row w-full items-center justify-between">

                        <div class="flex items-center justify-between flex-wrap md:flex-nowrap gap-2">
                            <div class="flex space-x-2 items-center mb-4 sm:mb-0">
                                <button
                                    class="bg-white border border-gray-300 rounded h-8 px-3 py-2 flex items-center space-x-1 hover:drop-shadow hover:bg-gray-200 hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110"
                                    wire:click="exportCandidate"
                                    wire:loading.attr="disabled">
                                    <svg wire:loading.remove wire:target="exportCandidate"
                                         xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                         width="20px" fill="#000000">
                                        <path
                                            d="M480-336 288-528l51-51 105 105v-342h72v342l105-105 51 51-192 192ZM263.72-192Q234-192 213-213.15T192-264v-72h72v72h432v-72h72v72q0 29.7-21.16 50.85Q725.68-192 695.96-192H263.72Z"/>
                                    </svg>
                                    <span wire:loading.remove wire:target="exportCandidate" class="text-[12px]">Export List of Candidates</span>
                                    <svg wire:loading wire:target="exportCandidate" class="animate-spin h-5 w-5 mr-3"
                                         viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span wire:loading wire:target="exportCandidate">Exporting...</span>
                                </button>
                            </div>

                            <div class="flex justify-end px-4 py-2">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" x-model="enableEditDelete" class="rounded form-checkbox h-4 w-4 text-black">
                                    <span class="text-black">Enable Edit/Delete</span>
                                </label>
                            </div>

                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Search Bar -->
                            <div class="relative w-full md:w-[250px]">
                                <x-input type="text" wire:model.live="search"
                                         class="rounded-md text-[12px] border bg-white text-black border-gray-300 h-8 pl-8 pr-4 focus:ring-1 focus:ring-black focus:border-black w-full"
                                         placeholder="Search candidates..." aria-label="Search"></x-input>
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                                <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M9.68208 10.7458C8.66576 11.5361 7.38866 12.0067 6.00167 12.0067C2.68704 12.0067 0 9.31891 0 6.00335C0 2.68779 2.68704 0 6.00167 0C9.31631 0 12.0033 2.68779 12.0033 6.00335C12.0033 7.39059 11.533 8.66794 10.743 9.6845L13.7799 12.7186C14.0731 13.0115 14.0734 13.4867 13.7806 13.7799C13.4878 14.0731 13.0128 14.0734 12.7196 13.7805L9.68208 10.7458ZM10.5029 6.00335C10.5029 8.49002 8.48765 10.5059 6.00167 10.5059C3.5157 10.5059 1.50042 8.49002 1.50042 6.00335C1.50042 3.51668 3.5157 1.50084 6.00167 1.50084C8.48765 1.50084 10.5029 3.51668 10.5029 6.00335Z"
                                                          fill="#000000"/>
                                                </svg>
                                            </span>
                            </div>
                            @can('create candidate')
                                <livewire:manage-candidate.add-candidates wire:key="add-candidate-button"/>
                            @endcan
                        </div>

                    </div>
                </div>

                <div class="w-full h-full">
                    <div class="container mx-auto px-4 py-4">
                        @if($selectedElection)
                            <!-- Student Council Section -->
                            @if($hasStudentCouncilPositions && $hasStudentCouncilCandidate)
                                <h2 class="text-[16px] font-bold uppercase text-center mb-6">{{ $selectedElectionCampus->name ?? 'No campus available' }}
                                    Student Council Candidates</h2>
                                <div id="studentCouncil"
                                     class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 px-4 py-4"
                                     wire:key="student-council-list">
                                    @foreach($candidates->where('election_positions.position.electionType.name', 'Student Council Election') as $candidate)
                                        <div wire:key="candidate-{{ $candidate->id }}" class=" relative mb-2">
                                            <div class="flip-card-container mt-[0px]" wire:key="cards-{{ $candidate->id}}">
                                                <div class="flip-card" onclick="this.classList.toggle('flipped')">

                                                    <!-- Front of the card -->
                                                    <div class="flip-card-front bg-white p-6 shadow-md min-h-[320px]">
                                                        <div class="flex justify-center items-center">
                                                            <p class="text-[12px]">Running for:
                                                                <span
                                                                    class="text-red-900 uppercase tracking-tighter font-semibold">
                                {{ $candidate->election_positions->position->name }}
                            </span>
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <div class="flex justify-end mt-2 mr-[15px]">
                                                                <img class="w-[85px]"
                                                                     src="{{ asset('storage/assets/icon/usep_logo_svg.png') }}"
                                                                     alt="">
                                                            </div>
                                                            <div class="mt-[-38px] flex justify-center">
                                                                <div class="border-2 border-black p-1 overflow-hidden w-[110px] max-h-[110px] flex items-center justify-center">
                                                                    <img class="w-[105px] max-h-[105px] object-cover"
                                                                         src="{{ asset('storage/' . ($candidate->users->profile_photo_path ?? 'assets/profile/default.jpg')) }}"
                                                                         alt="">
                                                                </div>
                                                            </div>

                                                            <div class="mt-2 text-center">
                                                                <div class="flex justify-center">
                                                                    <p class="text-black uppercase font-black text-[11px]">{{ $candidate->users->first_name }} {{ $candidate->users->middle_initial }}
                                                                        . {{ $candidate->users->last_name }}</p>
                                                                </div>
                                                                <p class="text-black capitalize font-semibold text-[10px]">{{ $candidate->users->year_level }}
                                                                    year</p>
                                                                <p class="text-black capitalize font-semibold text-[12px] leading-none">
                                                                    @php
                                                                        $programName = $candidate->users->program->name;
                                                                        $programName = str_starts_with($programName, 'Bachelor of Science') ? 'BS ' . substr($programName, strlen('Bachelor of Science')) : $programName;
                                                                    @endphp
                                                                    <span class="program-name !text-[12px]"
                                                                          title="{{ $programName }}">
                                    {{ $programName }}
                                </span>
                                                                </p>
                                                                <p class="text-black capitalize font-semibold text-[11px] leading-none">{{ optional($candidate->users->programMajor)->name ?? '' }}</p>
                                                                <p class="text-black mt-2 capitalize italic font-semibold text-[11px]">{{ $candidate->partyLists->name }}</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Back of the card with motto -->
                                                    <div
                                                        class="flip-card-back bg-white p-6 shadow-md min-h-[320px] flex items-center justify-center">
                                                        <div class="text-center p-4">
                                                            <h3 class="font-bold text-lg mb-2">ADVOCACY</h3>
                                                            <p class="italic text-sm">{{ $candidate->description ?? 'No motto provided' }}</p>
                                                            <p class="italic text-xs">-{{ $candidate->users->first_name . ' ' . substr($candidate->users->last_name, 0, 1) . '.'}}</p>
                                                            <p class="italic text-[10px]">
                                                                {{ Str::endsWith($candidate->election_positions->position->name, 'ent') ? Str::replaceLast('ent', 'ential', $candidate->election_positions->position->name) : $candidate->election_positions->position->name }} Aspirant.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="enableEditDelete" class="relative mt-[-40px] w-full" wire:key="edit-buttons-{{$candidate->id}}">
                                                <div class="flex justify-between px-4">
                                                    <!-- Edit Button -->
                                                    <livewire:manage-candidate.edit-candidate :candidateId="$candidate->id" wire:key="edit-{{$candidate->id}}" class="w-full"/>

                                                    <!-- Delete Button -->
                                                    <livewire:manage-candidate.delete-candidate :candidateId="$candidate->id" wire:key="delete-{{$candidate->id}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <!-- Local Councils Section -->
                            @if($hasLocalCouncilPositions && $hasLocalCouncilCandidate )
                                <h2 class="text-[16px] font-bold uppercase text-center mt-8 mb-4">Local Councils
                                    Candidates</h2>
                                @foreach($candidates->where('election_positions.position.electionType.name', 'Local Council Election')->groupBy('users.program.council.name') as $programName => $localCandidates)
                                    <h3 class="text-[12px] px-4 font-bold uppercase text-gray-700 mt-6 mb-8">{{ $programName }}
                                        Organization</h3>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 px-4 py-4 mt-4">
                                        @foreach($localCandidates as $candidate)
                                            <div wire:key="candidate-{{ $candidate->id }}" class="relative mb-2">
                                                <div class="flip-card-container mt-[-0px]">
                                                    <div class="flip-card" onclick="this.classList.toggle('flipped')">
                                                        <!-- Front of the card -->
                                                        <div class="flip-card-front bg-white p-6 shadow-md min-h-[320px]">
                                                            <div class="flex justify-center items-center">
                                                                <p class="text-[12px]">Running for:
                                                                    <span
                                                                        class="text-red-900 uppercase tracking-tighter font-semibold">
                                {{ $candidate->election_positions->position->name }}
                            </span>
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <div class="flex justify-end mt-2 mr-[15px]">
                                                                    <img class="w-[85px] "
                                                                         src="{{ asset('storage/assets/icon/usep_logo_svg.png') }}"
                                                                         alt="">
                                                                </div>
                                                                <div class="mt-[-38px] flex justify-center">
                                                                    <div class="border-2 border-black p-1 overflow-hidden w-[110px] max-h-[110px] flex items-center justify-center">
                                                                        <img class="w-[105px] max-h-[105px] object-cover"
                                                                             src="{{ asset('storage/' . ($candidate->users->profile_photo_path ?? 'assets/profile/default.jpg')) }}"
                                                                             alt="">
                                                                    </div>
                                                                </div>

                                                                <div class="mt-2 text-center">
                                                                    <div class="flex justify-center">
                                                                        <p class="text-black uppercase font-black text-[11px]">{{ $candidate->users->first_name }} {{ $candidate->users->middle_initial }}
                                                                            . {{ $candidate->users->last_name }}</p>
                                                                    </div>
                                                                    <p class="text-black capitalize font-semibold text-[10px]">{{ $candidate->users->year_level }}
                                                                        year</p>
                                                                    <p class="text-black capitalize font-semibold text-[12px] leading-none">
                                                                        @php
                                                                            $programName = $candidate->users->program->name;
                                                                            $programName = str_starts_with($programName, 'Bachelor of Science') ? 'BS ' . substr($programName, strlen('Bachelor of Science')) : $programName;
                                                                        @endphp
                                                                        <span class="program-name !text-[12px]"
                                                                              title="{{ $programName }}">
                                    {{ $programName }}
                                </span>
                                                                    </p>
                                                                    <p class="text-black capitalize font-semibold text-[11px] leading-none">{{ optional($candidate->users->programMajor)->name ?? '' }}</p>
                                                                    <p class="text-black mt-2 capitalize italic font-semibold text-[11px]">{{ $candidate->partyLists->name }}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Back of the card with motto -->
                                                        <div
                                                            class="flip-card-back bg-white p-6 shadow-md min-h-[320px] flex items-center justify-center">
                                                            <div class="text-center p-4">
                                                                <h3 class="font-bold text-lg mb-2">ADVOCACY</h3>
                                                                <p class="italic text-sm">
                                                                    {{ trim($candidate->description ?? '') !== '' ? $candidate->description : 'No motto provided' }}
                                                                </p>
                                                                <p class="italic text-xs">-{{ $candidate->users->first_name . ' ' . substr($candidate->users->last_name, 0, 1) . '.'}}</p>
                                                                <p class="italic text-[10px]">
                                                                    {{
                                                                        Str::endsWith($candidate->election_positions->position->name, 'ent')
                                                                            ? Str::replaceLast('ent', 'ential', $candidate->election_positions->position->name)
                                                                            : ($candidate->election_positions->position->name === 'Legislator'
                                                                                ? 'Legislative'
                                                                                : $candidate->election_positions->position->name)
                                                                    }} Aspirant.
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div x-show="enableEditDelete" class="relative mt-[-40px] w-full" wire:key="edit-buttons-{{$candidate->id}}">
                                                    <div class="flex justify-between px-4">
                                                        <!-- Edit Button -->
                                                        <livewire:manage-candidate.edit-candidate :candidateId="$candidate->id" wire:key="edit-{{$candidate->id}}" class="w-full"/>

                                                        <!-- Delete Button -->
                                                        <livewire:manage-candidate.delete-candidate :candidateId="$candidate->id" wire:key="delete-{{$candidate->id}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            @endif

                        @else
                                <div class="border border-gray-200 rounded-md p-8 text-center">
                                    <div class="flex justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 opacity-20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    </div>
                                    <h3 class="text-[14px] font-medium mb-2">No currently created election</h3>
                                </div>
                        @endif


                    </div>

                    <script>
                        function toggleDropdown(button) {
                            const dropdown = button.nextElementSibling;
                            dropdown.classList.toggle('hidden');
                        }
                    </script>
                </div>

            </div>
        </div>

    </div>

</div>
