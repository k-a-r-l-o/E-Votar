<div x-data="{ open: false }" x-cloak @candidate-created.window="open = false">
    <!-- Trigger Button -->
    <button @click="open = true"
            class="w-[130px] mr-2 rounded py-[6px] px-2 bg-black text-white text-[12px] hover:bg-gray-700">
        Add Candidate
    </button>
    <style>
        table td, th {
            font-size: 10px !important;
        }

        tr {
            height: 15px;
            line-height: 15px;
        }

        td, th {
            padding: 0;
        }

    </style>
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
            class="bg-white p-6 rounded  shadow-md w-11/12 sm:w-2/5 max-h-[700px] overflow-y-auto"
        >

            <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-2">
                <div>
                    <h2 class="text-sm font-bold text-left w-full sm:w-auto">Add Candidate</h2>
                    <p class="text-[10px] text-gray-500 italic">To add a candidate please provide the required
                        information. note that candidates should be a valid voter.</p>
                </div>
                <!-- Close Button (X) -->
                <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form wire:submit.prevent="submit">
                <div>
                    <div class="flex space-x-4 w-full">
                        <div x-data="{ isOpen: false }" class="flex-col w-full">
                            <div class="mb-3 relative">
                                <label for="candidate_name" class="text-xs font-semibold block mb-1">
                                    Name of Candidate (Note!: User Should Be A Voter)
                                </label>
                                <input
                                    type="text"
                                    id="candidate_name"
                                    placeholder="Search for a user"
                                    class="border border-gray-300 text-xs rounded-lg px-4 py-2 w-full"
                                    wire:model.live="search"
                                    x-on:focus="isOpen = true"
                                    x-on:blur="setTimeout(() => isOpen = false, 200)"
                                    autocomplete="off"
                                />
                                <div x-show="isOpen && search.length > 0" class="flex z-10 bg-white border border-gray-300 rounded-lg w-full max-h-[300px] overflow-auto mt-[5px] shadow-lg">
                                    <div class="w-full">
                                        @if (!empty($users))
                                            @forelse ($users as $user)
                                                <div
                                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                                    wire:click="selectUser({{ $user->id }})"
                                                    x-on:click="isOpen = false"
                                                >
                                                    {{ $user->first_name }} {{ $user->middle_initial }}. {{ $user->last_name }}
                                                    - ({{ $user->student_id }}) - {{ $user->year_level }} {{ $user->program->name }}
                                                </div>
                                            @empty
                                                <li class="px-4 py-2 text-gray-500">No results found.</li>
                                            @endforelse
                                        @endif
                                    </div>
                                </div>

                                @error('selectedUser')
                                <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex-1 mb-3">
                                <label for="candidate_election" class="text-xs font-semibold block mb-1">Select Election</label>
                                <select name="candidate_election" id="candidate_election"
                                        class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full "
                                        wire:model.live="selectedElection">
                                    <option value="" selected>Select an election</option>
                                    @foreach($elections as $election)
                                        <option value="{{ $election->id }}">{{ $election->name }} - {{ $election->campus->name }} - {{$election->election_type->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedElection')
                                <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex-1 mb-3">
                                <label for="candidate_position" class="text-xs font-semibold block mb-1">Available Position</label>
                                <select name="candidate_position" id="candidate_position"
                                        class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full "
                                        wire:model="candidate_position">
                                    <option value="" selected>Select available election position</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->position->name }} - {{ $position->position->electionType->name }}</option>
                                    @endforeach
                                </select>
                                @error('candidate_position')
                                <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex-1 mb-3">
                                <label for="candidate_party_list" class="text-xs font-semibold block mb-1">Party list</label>
                                <select name="candidate_party_list" id="candidate_party_list"
                                        class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full "
                                        wire:model="candidate_party_list">
                                    <option value="" selected>Select a party list</option>
                                    @foreach($partyLists as $partyList)
                                        <option value="{{ $partyList->id }}">{{ $partyList->name }}</option>
                                    @endforeach
                                </select>
                                @error('candidate_party_list')
                                <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex-1 mb-3">
                                <label for="candidate_description" class="text-xs font-semibold block mb-1">About the Candidate (eg. Advocacy, Virtue)</label>
                                <textarea name="candidate_description" id="candidate_description"
                                        class="border-gray-300 text-xs rounded-lg px-4 py-2 w-full min-h-[100px]" style="resize: none"
                                        wire:model="candidate_description"></textarea>
                                @error('candidate_party_list')
                                <span class="text-red-500 text-[10px] italic">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 pt-3 flex justify-end space-x-2">
                        <button type="button"
                                class="bg-gray-300 text-gray-700 text-[12px] h-7 px-4 py-1 rounded shadow-md hover:bg-gray-400 justify-center text-center"
                                @click="open = false">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-black text-white px-6 py-1 h-7 rounded shadow-md hover:bg-gray-700 text-[12px] justify-center text-center">
                            Add Candidate
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
