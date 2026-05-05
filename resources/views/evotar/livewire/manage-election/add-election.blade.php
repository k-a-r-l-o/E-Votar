
<div x-data="{ open: false }" x-cloak @election-created.window="open = false" @open-modal.window="open = true">
    <!-- Trigger Button -->
    <button @click="open = true" class="w-[120px] h-8 rounded bg-gradient-to-b from-gray-800 to-black text-white text-[12px] flex items-center justify-center gap-2 hover:drop-shadow hover:bg-gray-700 hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#FFFFFF">
            <path d="M444-444H240v-72h204v-204h72v204h204v72H516v204h-72v-204Z"/>
        </svg>
        Add Election
    </button>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="bg-white p-6 rounded shadow-md w-full mx-4 sm:mx-6 md:mx-8 lg:mx-10 xl:mx-12 overflow-y-auto max-h-[90vh]"
            :class="{ 'sm:w-[60%]': $wire.currentStep === 1 || $wire.currentStep === 2 }"
        >



        <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-2">
                <div>
                    <h2 class="text-sm font-bold text-left w-full sm:w-auto">Add Election</h2>
                    <p class="text-[10px] text-gray-500 italic">To add an election please fill out the required
                        information.</p>
                </div>

                <!-- Close Button (X) -->
            <button @click="open = false; $wire.call('resetForm')" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>

        </div>


            <!-- Election Details-->
            @if ($currentStep === 1)
                <form wire:submit.prevent="proceedToVoters"  enctype="multipart/form-data">
                    <div>
                        <div class="flex flex-col md:flex-row md:space-x-4">
                            <div class="flex-col w-full md:w-1/2">
                                <div class="mb-3">
                                    <label for="election_name" class="text-xs font-semibold block mb-1">
                                        Name (eg. Student and Local Election 2023)
                                    </label>
                                    <input id="election_name" type="text" placeholder="Student and Local Election 2023"
                                           class="border border-gray-300 text-xs rounded-lg px-4 py-2 w-full focus:ring-black focus:border-black"
                                           wire:model="election_name">

                                    @error('election_name')
                                    <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="election_type" class="text-xs font-semibold block mb-1">
                                        Election Type
                                    </label>
                                    <select name="election_type" id="election_type" wire:model.live="election_type"
                                            class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full focus:ring-black focus:border-black">
                                        <option value="" selected>Select election type</option>
                                        @foreach($electionTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('election_type')
                                    <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex-1 mb-3">
                                    <label for="election_campus" class="text-xs font-semibold block mb-1">Campus</label>
                                    <select name="election_campus" id="election_campus"
                                            class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full focus:ring-black focus:border-black "
                                            wire:model="election_campus">
                                        <option class="" value="" selected>Select campus for election</option>
                                        @foreach($campus as $camp)
                                            <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('election_campus')
                                    <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div x-data="{
                                                isDragging: false,
                                                previewUrl: @entangle('temporaryImageUrl'),
                                                updatePreview(event) {
                                                    const file = event.target.files[0];
                                                    if (file && file.type === 'image/png') {
                                                        this.previewUrl = URL.createObjectURL(file);
                                                    }
                                                }
                                            }"
                                     class="mt-4 w-full mb-1"
                                     wire:ignore>

                                    <label class="block text-xs font-semibold mb-1">Election Image</label>

                                    <!-- Drag and Drop Area -->
                                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 flex flex-col items-center justify-center"
                                         x-bind:class="{ 'border-green-500 bg-green-50': isDragging }"
                                         @dragover.prevent="isDragging = true"
                                         @dragleave.prevent="isDragging = false"
                                         @drop.prevent="isDragging = false;
                                            $refs.fileInput.files = event.dataTransfer.files;
                                             updatePreview({target: $refs.fileInput});
                                            $refs.fileInput.dispatchEvent(new Event('change'))"
                                         @click="$refs.fileInput.click()">

                                        <!-- File Preview -->
                                        <template x-if="previewUrl">
                                            <img :src="previewUrl" alt="Preview"
                                                 class="w-full max-w-xs h-40 object-contain rounded-lg shadow-md">
                                        </template>

                                        <!-- Upload Icon & Message -->
                                        <div x-show="!previewUrl" class="text-center flex justify-center items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16v4m10-4v4M5 12h14M12 3v13m-3-3l3-3 3 3" />
                                            </svg>
                                            <p class="text-sm text-gray-500">Drag & Drop or Click to Upload</p>
                                        </div>

                                        <!-- Hidden File Input -->
                                        <input type="file" class="hidden" id="electionImage" 
                                                wire:model.live="electionImage" x-ref="fileInput"
                                                @change="updatePreview($event)">

                                         <p class="text-[10px] text-gray-400 mt-2 italic text-center">
                                             Note: Only PNG images are supported for vote security.
                                         </p>


                                        <!-- Progress Bar -->
                                        <div wire:loading wire:target="electionImage" class="w-full mt-2">
                                            <div class="h-2 bg-gray-300 rounded-full">
                                                <div class="h-2 bg-red-500 rounded-full animate-pulse" style="width: 100%;"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Uploading...</p>
                                        </div>
                                    </div>
                                </div>
                                @error('electionImage')
                                <span class="text-red-500 text-[10px] mb-3 italic">{{ $message }}</span>
                                @enderror




                                <p class="text-[12px] font-medium">Election Period</p>
                                <div class="flex flex-col md:flex-row md:space-x-4 mb-4 border border-gray-300 rounded-md p-4">
                                    <div class="flex-1 mb-3 md:mb-0 min-w-0">
                                        <label for="election_start" class="text-[10px] block mb-1">From</label>
                                        <input id="election_start" type="datetime-local"
                                               class="border border-gray-300 text-xs rounded-md px-4 py-2 w-full focus:ring-black focus:border-black"
                                               wire:model="election_start">
                                        @error('election_start')
                                        <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <label for="election_end" class="text-[10px] block mb-1">To</label>
                                        <input id="election_end" type="datetime-local"
                                               class="border border-gray-300 text-xs rounded-md px-4 py-2 w-full focus:ring-black focus:border-black"
                                               wire:model="election_end">
                                        @error('election_end')
                                        <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="flex-col h-[530px] w-full md:w-1/2  overflow-auto">
                                <div class="mb-4">
                                    <p class="text-xs font-semibold block mb-1">Available Positions</p>

                                    <!-- Student Council Positions -->
                                    @if(!empty($studentCouncilPositions) && $election_type != 3)
                                        <p class="text-[11px] font-normal text-center mt-4 sm:mt-2 mb-2">Student Council Positions</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($studentCouncilPositions as $positionId => $positionName)
                                                @if(in_array($positionId, $selectedPositions))
                                                    <!-- Only show selected positions -->
                                                    <div class="border border-gray-300 rounded-lg p-4 flex justify-between items-center w-full">
                                                        <span class="text-[10px]">{{ $positionName }}</span>
                                                        <button type="button"
                                                                wire:click="removePosition({{ $positionId }})"
                                                                class="text-white px-2 py-1 rounded bg-red-500">
                                                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9 1L1 9M9 9L1 0.999998" stroke="white"
                                                                      stroke-width="2" stroke-linecap="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Local Council Positions -->
                                    @if(!empty($localCouncilPositions) && $election_type != 2)
                                        <p class="text-[11px] font-normal text-center mb-2 mt-4">Local Council Positions</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($localCouncilPositions as $positionId => $positionName)
                                                @if(in_array($positionId, $selectedPositions))
                                                    <!-- Only show selected positions -->
                                                    <div
                                                        class="border border-gray-300 rounded-lg p-4 flex justify-between items-center w-full">
                                                        <span class="text-[10px]">{{ $positionName }}</span>
                                                        <button type="button"
                                                                wire:click="removePosition({{ $positionId }})"
                                                                class="text-white px-2 py-1 rounded bg-red-500">
                                                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9 1L1 9M9 9L1 0.999998" stroke="white"
                                                                      stroke-width="2" stroke-linecap="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="mt-[-28px] pt-1 flex justify-end space-x-2 ">
                            <button type="button"
                                    @click="open = false; $wire.call('resetForm')"
                                    class="bg-white text-black text-[12px] border border-gray-300 h-7 px-4 py-1 rounded shadow-md hover:bg-gray-200 justify-center text-center hover:drop-shadow hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-black text-white px-6 py-1 h-7 rounded shadow-md hover:bg-gray-700 text-[12px] justify-center text-center hover:drop-shadow hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                                Proceed to Election Voter
                            </button>
                        </div>
                    </div>
                </form>

            @elseif ($currentStep === 2)
                <form wire:submit.prevent="submit">
                    <!-- Election Voters-->
                    <div x-data="{ showInfo: false }">
                        <div class="mb-4 w-[480px] relative flex">
                            <p class="text-[10px] font-semibold italic">Please select the programs you wish to exclude from this election. Note that programs excluded from voting will not be allowed to participate in the election process or influence the outcome.</p> <!-- Title with bold font -->
                            <!-- Info Button -->
                            <button type="button"
                                    @click="showInfo = !showInfo"
                                    class="text-gray-400 hover:text-gray-600 transition-colors"
                                    aria-label="More information">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div x-show="showInfo"
                                 x-transition
                                 class="absolute z-10 right-5 mt-1 w-64 bg-white border border-gray-200 rounded-lg shadow-lg p-3 text-xs">
                                <h4 class="font-medium text-gray-800 mb-1">Voting Configuration (Exclusion of Voter by Program)</h4>
                                <ul class="space-y-2">
                                    <li>
                                        <span class="font-medium">Exclusion Criteria:</span>
                                        <ul class="mt-1 pl-3 space-y-1">
                                            <li class="flex items-start">
                                                <span class="text-green-600 mr-1">✓</span>
                                                <span class="text-gray-600">Eligible: Programs that are included can vote and participate.</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-red-600 mr-1">✗</span>
                                                <span class="text-gray-600">Excluded: Programs that are excluded will not have voting rights.</span>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <div>
                            <div class="mb-2">
                                <p class="text-[12px] font-semibold"> Manage election voter here</p>
                            </div>
                            <div>
                                <div class="min-h-[300px]">
                                    <div class="bg-white text-black p-4">
                                        <div>
                                            @foreach($colleges as $college)
                                                <div class="flex items-center mb-2"> <!-- Flex for alignment -->
                                                    <input type="checkbox" wire:model.live="selectedColleges"
                                                           value="{{ $college->id }}" id="college-{{ $college->id }}" class="mr-2"> <!-- Margin-right for spacing -->
                                                    <label for="college-{{ $college->id }}" class="text-[12px] font-semibold">{{ $college->name }}</label> <!-- College name with semi-bold font -->
                                                </div>

                                                @if(in_array($college->id, $selectedColleges))
                                                    <div class="mt-2 pl-6 mb-4"> <!-- Padding-left for indentation -->
                                                        <strong class="text-[12px] font-semibold">Programs for {{ $college->name }}</strong>
                                                        @foreach($programsByCollege[$college->id] ?? [] as $program)
                                                            <div class="ml-6 mb-1"> <!-- Margin-bottom for spacing between programs -->
                                                                <input type="checkbox" wire:model="selectedPrograms"
                                                                       value="{{ $program->id }}"
                                                                       id="program-{{ $program->id }}" class="mr-2"> <!-- Margin-right for spacing -->
                                                                <label for="program-{{ $program->id }}" class="text-[12px]">{{ $program->name }}</label> <!-- Program name with regular font -->
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-3 flex justify-end space-x-2">
                            <button type="button" wire:click="backToStep1"
                                    class="bg-white text-black text-[12px] border border-gray-300 h-7 px-4 py-1 rounded shadow-md hover:bg-gray-200 justify-center text-center hover:drop-shadow hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                                Back
                            </button>
                            <button type="submit"
                                    class="bg-black text-white px-6 py-1 h-7 rounded shadow-md hover:bg-gray-700 text-[12px] justify-center text-center hover:drop-shadow hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110">
                                Save Election
                            </button>
                        </div>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>
