@php
    use App\Models\Candidate;
    use App\Models\ElectionPosition;
    use Illuminate\Support\Facades\DB;
@endphp
<div class="w-full px-10 min-h-screen"
     x-data="{
        selectedCandidates: {},
        collectVotes() {
            let inputs = document.querySelectorAll('input[name^=selected_candidate]:checked');
            inputs.forEach(input => {
                this.selectedCandidates[input.name] = input.value;
            });
            return this.selectedCandidates;
        },
        loadingConfirm: false
     }">
    <div class="mb-4">
        <h2 class="text-center uppercase text-[18px] font-bold">{{ $election->name }}</h2>
        <p class="text-center text-[16px] font-semibold text-gray-700">
            {{ $currentStage === 'student' ? 'STUDENT COUNCIL CANDIDATES' : 'LOCAL COUNCIL CANDIDATES' }}
        </p>
        <p class="text-center text-[12px] font-bold text-black tracking-wide uppercase">
            @if ($currentStage === 'student')
                {{ auth()->user()->campus->name . " student council" }}
            @endif
        </p>
        <p class="text-center text-[12px] font-bold text-black tracking-wide uppercase">
            @if ($currentStage === 'local')
                @php
                    $council = DB::table('councils')
                                 ->where('id', auth()->user()->program->council_id)
                                 ->first();
                @endphp
                {{ $council ? $council->name : 'No council available' }}
            @endif
        </p>
    </div>

    <div class="grid grid-cols-1 gap-4 mt-4">
        @foreach ($positions as $position)
            @for ($i = 1; $i <= $position->num_winners; $i++)
                <div class="border px-4 py-4 rounded-lg w-full" wire:key="{{ $currentStage }}_{{ $position->id }}_{{ $i }}">
                    <h3 class="uppercase font-semibold text-center text-[14px] mb-4">
                        {{ $position->name }} (Vote {{ $i }})
                    </h3>

                    @php
                        $hasCandidates = $position->candidates->count() > 0;
                        $currentSelection = $selectedCandidates["selected_candidate_{$position->id}_{$i}"] ?? ($hasCandidates ? 'abstain' : '');
                    @endphp

                    <div x-data="{
                        selectedId: '{{ $currentSelection }}',
                        selectCandidate(candidateId) {
                            this.selectedId = candidateId;
                            // Uncheck all other radios in this group
                            document.querySelectorAll(`input[name='selected_candidate_{{ $position->id }}_{{ $i }}']`).forEach(radio => {
                                radio.checked = (radio.value === candidateId);
                            });
                        }
                    }" class="w-full">

                        @if($hasCandidates)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                <!-- Abstain Option -->
                                <div @click="selectCandidate('abstain')"
                                     :class="{'border-2 border-green-500 bg-green-50': selectedId === 'abstain', 'border border-gray-200': selectedId !== 'abstain'}"
                                     class="cursor-pointer rounded-lg p-4 transition-all duration-200 hover:shadow-md">
                                    <div class="flex flex-col items-center justify-center h-full">
                                        <div class="mb-2 flex justify-center">
                                            <div class="border-2 border-red-200 rounded-full p-1">
                                                <img class="w-[80px] h-[80px] object-cover rounded-full"
                                                     src="{{ asset('storage/assets/image/Abstain.jpg') }}"
                                                     alt="Abstain from voting">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-center mb-2">
                                            <svg class="w-5 h-5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"></path>
                                            </svg>
                                            <h3 class="text-red-600 uppercase font-bold text-[12px]">
                                                Abstain From Voting
                                            </h3>
                                        </div>
                                        <p class="text-gray-700 text-[10px] text-center">
                                            Choose not to vote for any candidate in this position
                                        </p>
                                    </div>
                                    <input type="radio" name="selected_candidate_{{ $position->id }}_{{ $i }}"
                                           x-model="selectedId" value="abstain" class="hidden"
                                           :checked="selectedId === 'abstain'">
                                </div>

                                <!-- Candidate Options -->
                                @foreach($position->candidates as $candidate)
                                    <div @click="selectCandidate('{{ $candidate->id }}')"
                                         :class="{'border-2 border-green-500 bg-green-50': selectedId === '{{ $candidate->id }}', 'border border-gray-200': selectedId !== '{{ $candidate->id }}'}"
                                         class="cursor-pointer rounded-lg p-4 transition-all duration-200 hover:shadow-md">
                                        <div class="flex flex-col items-center justify-center h-full">
                                            <div class="mb-2 flex justify-center">
                                                <div class="border-2 border-red-200 rounded-full p-1">
                                                    <img class="w-[80px] h-[80px] object-cover rounded-full"
                                                         src="{{ $candidate->users->profile_photo_path ? asset('storage/'.$candidate->users->profile_photo_path) : asset('storage/assets/profile/default.jpg') }}"
                                                         alt="{{ $candidate->users->first_name }} {{ $candidate->users->last_name }} profile photo">
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-center justify-center mb-1 mx-2 space-y-1">
                                                <h3 class="text-green-600 uppercase font-bold text-[12px] text-center">
                                                    @php
                                                        // Decrypt the name server-side for initial load
                                                        $decrypted = $this->decryptUserData($candidate->users);
                                                        $fullName = $decrypted['first_name'] . ' ' .
                                                                   ($decrypted['middle_initial'] ? $decrypted['middle_initial'] . '. ' : '') .
                                                                   $decrypted['last_name'] .
                                                                   ($decrypted['extension'] ? ' ' . $decrypted['extension'] : '');
                                                    @endphp
                                                    {{ $fullName }}
                                                </h3>
                                                <p class="text-gray-700 capitalize text-[9px] px-2">
                                                    <span>{{ $candidate->users->year_level }} year</span>
                                                </p>
                                                <p class="text-gray-700 capitalize text-[10px] px-2 leading-none">
                                                    <span class="program-name !text-[10px]">{{ $candidate->users->program->name }}</span>
                                                </p>
                                                <p class="text-gray-700 capitalize text-[10px] px-2 leading-none">
                                                    <span>{{ $candidate->users->program_major->name ?? '' }}</span>
                                                </p>
                                                <p class="text-black capitalize px-2 text-[10px]">
                                                    <span>{{ $candidate->party_lists->name ?? '' }}</span>
                                                </p>
                                                <div class="mt-2 px-2 max-w-[250px]">
                                                    <p class="text-[8px] italic text-center leading-tight overflow-hidden text-ellipsis whitespace-nowrap"
                                                       title="{{ $candidate->description ?: 'No motto/advocacy provided' }}">
                                                        {{ $candidate->description ? '"'.$candidate->description.'"' : 'No motto/advocacy provided' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="radio" name="selected_candidate_{{ $position->id }}_{{ $i }}"
                                               x-model="selectedId" value="{{ $candidate->id }}" class="hidden"
                                               :checked="selectedId === '{{ $candidate->id }}'">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                No candidates available for this position. You may proceed to the next.
                            </div>
                            <input type="hidden" name="selected_candidate_{{ $position->id }}_{{ $i }}" value="">
                        @endif
                    </div>
                </div>
            @endfor
        @endforeach
    </div>

    <!-- Navigation Buttons -->
    <div class="text-center mt-4 mb-4 w-full flex flex-col sm:flex-row justify-end items-end gap-2">
        @if ($showProceedButton && $currentStage === 'local')
            <div x-data="{ loading: false }" class="w-full sm:w-auto">
                <button
                    @click="loading = true; collectVotes();
                $wire.addSelections(selectedCandidates)
                .then(() => $wire.goBackToStudentCouncilElection())
                .catch(() => { /* Handle error here */ })
                .finally(() => loading = false);"
                    x-bind:disabled="loading"
                    class="text-white text-[12px] px-4 py-2 rounded w-full sm:w-[250px] bg-gray-500 flex items-center justify-center">
                    <span x-show="!loading">Back to Student Council Election</span>
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-5 h-5 ml-2"></span>
                </button>
            </div>
        @endif

        <div x-data="{ loading: false }" class="w-full sm:w-auto">
            @if ($showProceedButton && $currentStage === 'student')
                <button
                    @click="loading = true; collectVotes();
                $wire.addSelections(selectedCandidates)
                .then(() => $wire.proceedToLocalCouncilElection())
                .catch(() => { /* Handle error here */ })
                .finally(() => loading = false);"
                    x-bind:disabled="loading"
                    class="text-white px-4 py-2 text-[12px] rounded w-full sm:w-[280px] bg-black flex items-center justify-center">
                    <span x-show="!loading">Proceed to Local Council Election</span>
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full hover:bg-gray-700 w-5 h-5 ml-2"></span>
                </button>
            @else
                <button
                    @click="loading = true; collectVotes();
                $wire.addSelections(selectedCandidates)
                .then(() => $wire.showSummary())
                .catch(() => { /* Handle error here */ })
                .finally(() => loading = false);"
                    x-bind:disabled="loading"
                    class="text-white px-4 py-2 text-[12px] rounded w-full sm:w-[280px] bg-green-600 flex items-center justify-center">
                    <span x-show="!loading">Submit Vote</span>
                    <span x-show="loading" class="animate-spin border-2 border-white border-t-transparent rounded-full w-5 h-5 ml-2"></span>
                </button>
            @endif
        </div>
    </div>

    <!-- Duplicate Error Modal -->
    <div x-show="$wire.showDuplicateErrorModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
         x-cloak>
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-[550px] mx-4"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <!-- Header -->
            <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex items-start">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-red-800">Duplicate Votes Detected</h3>
                    <p class="text-sm text-red-600 mt-1" x-text="$wire.duplicateError"></p>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-4">
                <div class="flex items-center bg-red-50/50 rounded-lg p-4 border border-red-100">
                    <svg class="h-5 w-5 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-700">
                        Please review your selections. You cannot vote for the same candidate in multiple positions.
                    </p>
                </div>

                <div class="mt-4 text-sm text-gray-600">
                    <p>To fix this:</p>
                    <ul class="list-disc pl-5 space-y-1 mt-2">
                        <li>Select different candidates for each position</li>
                        <li>Or choose "Abstain" if you don't want to vote</li>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                <button @click="$wire.showDuplicateErrorModal = false;"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Review Votes
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Modal -->
    <div x-show="$wire.showSummaryModal"
         class="fixed inset-0 bg-black flex items-center justify-center z-50"
         x-cloak
         style="background-image: url('{{ asset('storage/assets/image/bg-image-voted.png') }}'); background-size: contain">
        <div class="bg-white p-6 rounded shadow-md w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
            <h2 class="text-[18px] font-bold mb-2 text-center uppercase">University of Southeastern Philippines Tagum-Unit</h2>
            <div class="flex justify-between items-center mb-1 md:w-1/2 mx-auto ">
                <img src="{{ asset('storage/assets/logo/usep_logo.jpg') }}" class="w-[45px]" alt="usep-logo">
                <h2 class="text-[16px] font-black text-center uppercase">Summary of Votes</h2>
                <img src="{{ asset('storage/assets/logo/usg_logo.png') }}" class="w-[45px]" alt="usg-logo">
            </div>
            <div>
                <h2 class="text-[14px] font-normal mb-2 text-center uppercase">COMMISSION ON ELECTIONS</h2>
            </div>

            @php
                $allPositions = ElectionPosition::with('position')->where('election_id', $election->id)->get();
                $studentCouncilPositions = $allPositions->filter(function ($ep) {
                    return optional($ep->position->electionType)->name === 'Student Council Election';
                });
                $localCouncilPositions = $allPositions->filter(function ($ep) {
                    return optional($ep->position->electionType)->name === 'Local Council Election';
                });
                $studentCouncilVotes = [];
                $localCouncilVotes = [];
                $abstentions = [];

                foreach ($selectedCandidates as $key => $value) {
                    $parts = explode('_', str_replace('selected_candidate_', '', $key));
                    $positionId = $parts[0];
                    $slot = $parts[1] ?? null;

                    if ($value === 'abstain') {
                        $abstentions[$positionId][$slot] = true;
                    } else {
                        $candidate = Candidate::with('users', 'election_positions.position')->find($value);
                        if ($candidate) {
                            $type = optional($candidate->election_positions->position->electionType)->name;
                            if ($type === 'Student Council Election') {
                                $studentCouncilVotes[$positionId][$slot] = $candidate;
                            } else {
                                $localCouncilVotes[$positionId][$slot] = $candidate;
                            }
                        }
                    }
                }
            @endphp

                <!-- Student Council Candidates -->
            <div class="w-full">
                @if(!empty($studentCouncilVotes) || !empty($abstentions))
                    <h3 class="text-[14px] font-bold text-center mt-2 mb-4">TAGUM STUDENT COUNCIL</h3>
                @endif
                <div class="text-left w-full px-12">
                    <ul class="mb-4">
                        @foreach ($studentCouncilPositions as $electionPosition)
                            @php
                                $positionId = $electionPosition->position_id;
                                $positionVotes = $studentCouncilVotes[$positionId] ?? [];
                                $positionAbstentions = $abstentions[$positionId] ?? [];
                                $numWinners = $electionPosition->position->num_winners ?? 1;
                            @endphp
                            <li class="mb-2">
                                <div class="flex flex-col sm:flex-row justify-between">
                                    <p class="font-semibold">{{ optional($electionPosition->position)->name ?? 'Unknown Position' }}:</p>
                                    <div class="flex flex-col items-end w-full sm:w-2/3  space-y-1">
                                        @for ($i = 1; $i <= $numWinners; $i++)
                                            @if(isset($positionAbstentions[$i]))
                                                <span class="text-red-600 font-medium">(Abstained)</span>
                                            @elseif(isset($positionVotes[$i]))
                                                <div class="text-right">
                                                    {{ $positionVotes[$i]->users->first_name }} {{ $positionVotes[$i]->users->last_name }}
                                                </div>
                                            @else
                                                <span class="text-gray-500">No Candidate for this position</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Local Council Candidates -->
            <div class="w-full">
                @if(!empty($localCouncilVotes) || !empty($abstentions))
                    <h3 class="text-[14px] font-bold text-center mt-2 mb-4 uppercase">
                        {{ $council->name ?? 'No council available' }}
                    </h3>
                @endif
                <div class="text-left w-full px-12">
                    <ul class="mb-4">
                        @foreach ($localCouncilPositions as $electionPosition)
                            @php
                                $positionId = $electionPosition->position_id;
                                $positionVotes = $localCouncilVotes[$positionId] ?? [];
                                $positionAbstentions = $abstentions[$positionId] ?? [];
                                $numWinners = $electionPosition->position->num_winners ?? 1;
                            @endphp
                            <li class="mb-2">
                                <div class="flex flex-col sm:flex-row justify-between">
                                    <p class="font-semibold w-1/3">
                                        {{ optional($electionPosition->position)->name ?? 'Unknown Position' }}:
                                    </p>
                                    <div class="flex flex-col items-end  w-full sm:w-2/3  space-y-1">
                                        @for ($i = 1; $i <= $numWinners; $i++)
                                            @if(isset($positionAbstentions[$i]))
                                                <span class="text-red-600 font-medium">(Abstained)</span>
                                            @elseif(isset($positionVotes[$i]))
                                                <div class="text-right">
                                                    {{ $positionVotes[$i]->users->first_name }} {{ $positionVotes[$i]->users->last_name }}
                                                </div>
                                            @else
                                                <span class="text-gray-500">No Candidate for this position</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div>
                <div class="mt-4 flex justify-end">
                    <button
                        @click="loadingConfirm = true; $wire.submitVotes().finally(() => loadingConfirm = false)"
                        x-bind:disabled="loadingConfirm"
                        class="px-4 py-2 text-[12px] bg-green-600 text-white rounded flex items-center justify-center min-w-[100px]">
                        <span x-show="!loadingConfirm">Confirm</span>
                        <span x-show="loadingConfirm" class="animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                    </button>
                    <button @click="$wire.showSummaryModal = false"
                            class="ml-2 px-4 py-2 text-[12px] bg-gray-500 text-white rounded">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function collectVotes() {
            window.selectedCandidates = {};
            document.querySelectorAll('input[name^="selected_candidate_"]').forEach(input => {
                window.selectedCandidates[input.name] = input.value;
            });
        }
    </script>
</div>
