@php use Illuminate\Support\Facades\DB; @endphp
<div class="flex flex-col items-start space-y-4 w-full px-0">
    <div class="flex items-center justify-between w-full mb-3">
        <h1 class="text-lg font-bold text-gray-800">Real-time Vote Tally</h1>
    </div>
    <div class=" w-full" x-data="{ tab: 'per-position' }">
        <div class="flex items-center space-x-2">
                        <span class="text-gray-500 text-[11px]">
                         View as:
                        </span>
            <div class="flex border border-gray-300 rounded-lg overflow-hidden text-[11px] w-auto">
                <div
                    :class="{ 'bg-black text-white font-bold': tab === 'per-position', 'text-gray-800': tab !== 'per-position' }"
                    @click="tab = 'per-position'" class="px-4 py-2 cursor-pointer">
                    Per Position
                </div>
                <div
                    :class="{ 'bg-black text-white font-bold': tab === 'graphical', 'text-gray-800': tab !== 'graphical' }"
                    @click="tab = 'graphical'" class="px-4 py-2 cursor-pointer">
                    Graphical
                </div>
                <div
                    :class="{ 'bg-black text-white font-bold': tab === 'tabulated', 'text-gray-800': tab !== 'tabulated' }"
                    @click="tab = 'tabulated'" class="px-4 py-2 cursor-pointer">
                    Tabulated
                </div>
            </div>
            <div class="flex items-center justify-between flex-wrap md:flex-nowrap gap-2">
                <button
                    class="bg-white border border-gray-300 rounded h-8 px-3 py-2 flex items-center space-x-1 hover:drop-shadow hover:bg-gray-200 hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110"
                    wire:click="exportVoteTally"
                    wire:loading.attr="disabled">
                    <svg wire:loading.remove wire:target="exportVoteTally" xmlns="http://www.w3.org/2000/svg"
                         height="20px" viewBox="0 -960 960 960"
                         width="20px" fill="#000000">
                        <path
                            d="M480-336 288-528l51-51 105 105v-342h72v342l105-105 51 51-192 192ZM263.72-192Q234-192 213-213.15T192-264v-72h72v72h432v-72h72v72q0 29.7-21.16 50.85Q725.68-192 695.96-192H263.72Z"/>
                    </svg>
                    <span wire:loading.remove wire:target="exportVoteTally" class="text-[12px]">Export Vote Tallying Result</span>
                    <svg wire:loading wire:target="exportVoteTally" class="animate-spin h-5 w-5 mr-3"
                         viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading wire:target="exportVoteTally">Exporting...</span>
                </button>
            </div>
        </div>
        <div class="mt-4 w-full">
            <div x-show="tab === 'per-position'">
                <div class="bg-white shadow-md rounded p-6">

                    <div class="w-full">
                        <div class="container mx-auto px-4 py-4">
                            @if($selectedElection)
                                <!-- Student Council Section -->
                                @if(!auth()->user()->hasRole('local-council-watcher'))
                                    @if($hasStudentCouncilPositions && $hasStudentCouncilCandidate)
                                        <h2 class="text-[16px] font-bold uppercase text-center mb-4">{{ $selectedElectionCampus->name ?? 'No campus available' }}
                                            Student Council Candidates</h2>
                                        <div id="studentCouncil"
                                             class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 px-4 py-4"
                                             wire:key="student-council-list" wire:poll="$refresh">
                                            @foreach($candidates->where('election_positions.position.electionType.name', 'Student Council Election')->groupBy('election_positions.position.name') as $position => $candidatesForPosition)
                                                @php
                                                    $positionId = $candidatesForPosition->first()->election_positions->position->id;
                                                    $abstainCount = \App\Models\AbstainVote::where('election_id', $selectedElection)
                                                        ->where('position_id', $positionId)
                                                        ->count();
                                                    $totalVotes = $candidatesForPosition->sum('votes_count') + $abstainCount;
                                                    $abstainPercentage = $totalVotes > 0 ? round(($abstainCount / $totalVotes) * 100) : 0;
                                                @endphp

                                                    <!-- Position Header Card -->
                                                <div class="md:col-span-2 lg:col-span-3 xl:col-span-4">
                                                    <div
                                                        class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-black">
                                                        <h3 class="text-[14px] font-bold text-gray-800">{{ $position }}</h3>
                                                        <div class="flex justify-between items-center mt-2">
                                                            <div class="flex space-x-4">
                                                            <span class="text-[12px] text-gray-600">
                                                                <span
                                                                    class="font-semibold">{{ $candidatesForPosition->count() }}</span> Candidates
                                                            </span>
                                                                <span class="text-[12px] text-gray-600">
                                                                <span class="font-semibold">{{ $totalVotes }}</span> Total Votes
                                                            </span>
                                                            </div>
                                                            <span
                                                                class="text-[12px] bg-black-100 text-black-800 px-2 py-1 rounded-full">
                        {{ $abstainCount }} Abstention{{ $abstainCount != 1 ? 's' : '' }}
                    </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Abstain Card -->
                                                <div wire:key="abstain-{{ $positionId }}"
                                                     class="bg-white rounded-lg shadow-md overflow-hidden border border-red-100">
                                                    <div class="bg-red-600 px-4 py-2">
                                                        <h4 class="text-white font-bold text-[12px] uppercase tracking-wider flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                                 viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
                                                            </svg>
                                                            Abstain Votes
                                                        </h4>
                                                    </div>
                                                    <div class="p-5 text-center">
                                                        <div
                                                            class="text-2xl font-bold text-red-600 mb-2">{{ $abstainCount }}</div>
                                                        <div class="text-xs text-gray-500 mb-4">VOTERS ABSTAINED</div>

                                                        <div class="relative pt-1 mb-4">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                            <span class="text-xs font-semibold inline-block text-red-600">
                                {{ $abstainPercentage }}%
                            </span>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-200">
                                                                <div style="width:{{ $abstainPercentage }}%"
                                                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                                                            </div>
                                                        </div>

                                                        <div class="text-xs text-gray-500 italic">
                                                            Voters chose not to select any candidate for this position
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Candidate Cards -->
                                                @foreach($candidatesForPosition->sortByDesc('votes_count') as $candidate)
                                                    @php
                                                        $votePercentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100) : 0;
                                                        $isLeading = $candidatesForPosition->max('votes_count') == $candidate->votes_count;
                                                    @endphp

                                                    <div wire:key="candidate-{{ $candidate->id }}"
                                                         class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-200 hover:shadow-lg">
                                                        <!-- Ribbon for leading candidate -->
{{--                                                        @if($isLeading)--}}
{{--                                                            <div class="absolute top-0 right-0">--}}
{{--                                                                <div--}}
{{--                                                                    class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 transform rotate-45 translate-x-8 translate-y-4 w-32 text-center">--}}
{{--                                                                    CURRENT LEADER--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        @endif--}}

                                                        <!-- Candidate Photo -->
                                                        <div
                                                            class="relative h-40 bg-gray-100 flex items-center justify-center">
                                                            <div
                                                                class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-30"></div>
                                                            <img class="h-full w-full object-contain"
                                                                 src="{{ $candidate->users->profile_photo_path ? asset('storage/' . $candidate->users->profile_photo_path) : asset('storage/assets/profile/default.jpg') }}"
                                                                 alt="Candidate photo">
                                                            <div class="absolute bottom-0 left-0 p-3">
                                                                <span
                                                                    class="bg-black text-white text-xs px-2 py-1 rounded-full">
                                                                    {{ $candidate->election_positions->position->name }}
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <!-- Candidate Info -->
                                                        <div class="p-5">
                                                            <h3 class="text-[14px] uppercase text-center font-bold text-gray-800 mb-1">
                                                                {{ $candidate->users->first_name }}
                                                                @if($candidate->users->middle_initial)
                                                                    {{ $candidate->users->middle_initial }}.
                                                                @endif
                                                                {{ $candidate->users->last_name }}
                                                            </h3>

                                                            <div
                                                                class=" text-center uppercase text-[10px] text-gray-600 mb-3">
                                                                <div>
                                                                    <span>{{ $candidate->users->year_level }}</span>
                                                                </div>
                                                                <div>
                                                                   <span>
                                                                        @php
                                                                            $programName = $candidate->users->program->name;
                                                                            echo str_starts_with($programName, 'Bachelor of Science')
                                                                                ? 'BS ' . substr($programName, strlen('Bachelor of Science'))
                                                                                : $programName;
                                                                        @endphp
                                                                </span>
                                                                </div>
                                                                <div>
                                                                    <span>
                                                                        {{ optional($candidate->users->programMajor)->name ?? '' }}
                                                                </span>
                                                                </div>
                                                            </div>

                                                            <!-- Vote Count -->
                                                            <div class="mb-4">
                                                                <div class="flex justify-between items-center mb-1">
                                                                    <span
                                                                        class="text-[12px] font-semibold text-gray-700">VOTES</span>
                                                                    <span class="text-sm font-bold text-black">
                                                                        {{ $candidate->votes_count }} ({{ $votePercentage }}%)
                                                                    </span>
                                                                </div>
                                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                                    <div class="bg-black h-2 rounded-full"
                                                                         style="width: {{ $votePercentage }}%"></div>
                                                                </div>
                                                            </div>

                                                            <!-- Party List -->
                                                            @if($candidate->partyLists)
                                                                <div
                                                                    class="flex items-center justify-between text-[12px] font-semibold text-gray-700">
                                                                    <img
                                                                        src="{{ $candidate->partyLists?->logo_path ? asset('storage/' . $candidate->partyLists->logo_path) : asset('storage/assets/logo/default-logo.jpg') }}"
                                                                        alt="{{ $candidate->partyLists?->name ?? 'Party Logo' }}"
                                                                        class="w-10 h-10 object-contain rounded-full"
                                                                    />
                                                                    {{ $candidate->partyLists->name }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                                <!-- Local Councils Section -->
                                @if(!auth()->user()->hasRole('student-council-watcher'))
                                    @if($hasLocalCouncilPositions && $hasLocalCouncilCandidate)
                                        <div class="mt-12 mb-6">
                                            <h2 class="text-[16px] font-bold uppercase text-center mb-4">Local Council
                                                Candidates</h2>
                                        </div>

                                        @foreach($candidates->where('election_positions.position.electionType.name', 'Local Council Election')->groupBy('users.program.council.name') as $councilName => $councilCandidates)
                                            @php
                                                $councilId = $councilCandidates->first()->users->program->council->id;
                                                $programId = $councilCandidates->first()->users->program_id;

                                                // Get distinct voters count for this council's program
                                                $totalCouncilVotes = DB::table('votes')
                                                    ->join('users', 'users.id', '=', 'votes.user_id')
                                                    ->where('users.program_id', $programId)
                                                    ->where('votes.election_id', $selectedElection)
                                                    ->distinct('votes.user_id')
                                                    ->count('votes.user_id');

                                              $totalCouncilAbstain = \App\Models\AbstainVote::where('election_id', $selectedElection)
                                                    ->whereHas('position', function($q) {
                                                        $q->whereHas('electionType', function($q) {
                                                            $q->where('name', 'Local Council Election');
                                                        });
                                                    })
                                                    ->whereHas('user.program.council', function($q) use ($councilId) {
                                                        $q->where('id', $councilId);
                                                    })
                                                    ->distinct('user_id')
                                                    ->count('user_id');
                                            @endphp
                                                <!-- Council Header -->
                                            <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-black mb-6">
                                                <div class="flex justify-between items-center flex-wrap gap-4">
                                                    <h3 class="text-[14px] font-bold text-gray-800">{{ $councilName }}
                                                        Council</h3>
                                                    <div class="flex flex-wrap gap-3">
                                                        <span
                                                            class="text-[11px] bg-green-100 text-green-800 px-3 py-1 rounded-full">
                                                            {{ $councilCandidates->count() }} Candidates
                                                        </span>
                                                        <span
                                                            class="text-[11px] bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                                                            {{ $totalCouncilVotes }} Votes Cast
                                                        </span>
                                                        <span
                                                            class="text-[11px] bg-red-100 text-red-800 px-3 py-1 rounded-full">
                                                            {{ $totalCouncilAbstain }} Total Abstentions
                                                        </span>
                                                        <span
                                                            class="text-[11px] bg-gray-100 text-gray-800 px-3 py-1 rounded-full">
                                                            {{ $totalCouncilVotes }} Total Votes
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Group by position within council -->
                                            @foreach($councilCandidates->groupBy('election_positions.position.name') as $positionName => $positionCandidates)
                                                @php
                                                    $positionId = $positionCandidates->first()->election_positions->position->id;
                                                    $positionAbstainCount = \App\Models\AbstainVote::where('election_id', $selectedElection)
                                                        ->where('position_id', $positionId)
                                                        ->count();

                                                    $totalPositionVotes = $positionCandidates->sum('votes_count') + $positionAbstainCount;
                                                    $abstainPercentage = $totalPositionVotes > 0 ? round(($positionAbstainCount / $totalPositionVotes) * 100) : 0;
                                                @endphp

                                                <div class="mb-8">
                                                    <!-- Position Header -->
                                                    <div
                                                        class="bg-white p-4 rounded-lg shadow-sm mb-4 border-b-2 border-red-200">
                                                        <h4 class="text-[14px] font-semibold text-gray-700">
                                                            {{ $positionName }} Position
                                                            <span class="text-[12px] font-normal text-gray-500 ml-2">
                                                                ({{ $positionCandidates->count() }} candidates, {{ $positionAbstainCount }} abstentions)
                                                            </span>
                                                        </h4>
                                                    </div>

                                                    <div
                                                        class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 px-4 py-4">
                                                        <!-- Position Abstain Card -->
                                                        <div wire:key="local-abstain-{{ $positionId }}"
                                                             class="bg-white rounded-lg shadow-md overflow-hidden border border-red-100">
                                                            <div class="bg-red-600 px-4 py-2">
                                                                <h4 class="text-white font-bold text-[12px] uppercase tracking-wider flex items-center">
                                                                    <svg class="w-4 h-4 mr-2" fill="none"
                                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                              stroke-linejoin="round" stroke-width="2"
                                                                              d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/>
                                                                    </svg>
                                                                    Position Abstentions
                                                                </h4>
                                                            </div>
                                                            <div class="p-5 text-center">
                                                                <div
                                                                    class="text-2xl font-bold text-red-600 mb-2">{{ $positionAbstainCount }}</div>
                                                                <div class="text-[11px] text-gray-500 mb-4">
                                                                    OF {{ $totalPositionVotes }} TOTAL VOTES
                                                                </div>

                                                                <div class="relative pt-1 mb-4">
                                                                    <div class="flex items-center justify-between">
                                                                        <div>
                                                                            <span class="text-xs font-semibold inline-block text-red-600">
                                                                                {{ $abstainPercentage }}%
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-200">
                                                                        <div style="width:{{ $abstainPercentage }}%"
                                                                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Local Council Candidates for this Position -->
                                                        @foreach($positionCandidates->sortByDesc('votes_count') as $candidate)
                                                            @php
                                                                $votePercentage = $totalPositionVotes > 0 ? round(($candidate->votes_count / $totalPositionVotes) * 100) : 0;
                                                                $isLeading = $positionCandidates->max('votes_count') == $candidate->votes_count;
                                                            @endphp

                                                            <div wire:key="local-candidate-{{ $candidate->id }}"
                                                                 class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-200 hover:shadow-lg">
                                                                <!-- Candidate Photo -->
                                                                <div
                                                                    class="relative h-40 bg-gray-100 flex items-center justify-center">
                                                                    <div
                                                                        class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent opacity-30"></div>
                                                                    <img class="h-full w-full object-contain"
                                                                         src="{{ $candidate->users->profile_photo_path ? asset('storage/'.$candidate->users->profile_photo_path) : asset('storage/assets/profile/default.jpg') }}"
                                                                         alt="Candidate photo">
                                                                    <div class="absolute bottom-0 left-0 p-3">
                                                                <span
                                                                    class="bg-black text-white text-xs px-2 py-1 rounded-full">
                                                                    {{ $candidate->election_positions->position->name }}
                                                                </span>
                                                                    </div>
                                                                </div>

                                                                <!-- Candidate Info -->
                                                                <div class="p-5">
                                                                    <h3 class="text-[14px] uppercase text-center font-bold text-gray-800 mb-1">
                                                                        {{ $candidate->users->first_name }}
                                                                        @if($candidate->users->middle_initial)
                                                                            {{ $candidate->users->middle_initial }}.
                                                                        @endif
                                                                        {{ $candidate->users->last_name }}
                                                                    </h3>

                                                                    <div
                                                                        class=" text-center uppercase text-[10px] text-gray-600 mb-3">
                                                                        <div>
                                                                            <span>{{ $candidate->users->year_level }}</span>
                                                                        </div>
                                                                        <div>
                                                                   <span>
                                                                        @php
                                                                            $programName = $candidate->users->program->name;
                                                                            echo str_starts_with($programName, 'Bachelor of Science')
                                                                                ? 'BS ' . substr($programName, strlen('Bachelor of Science'))
                                                                                : $programName;
                                                                        @endphp
                                                                </span>
                                                                        </div>
                                                                        <div>
                                                                    <span>
                                                                        {{ optional($candidate->users->programMajor)->name ?? '' }}
                                                                </span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Vote Count -->
                                                                    <div class="mb-4">
                                                                        <div
                                                                            class="flex justify-between items-center mb-1">
                                                                            <span
                                                                                class="text-[12px] font-semibold text-gray-700">VOTES</span>
                                                                            <span class="text-sm font-bold text-black">
                                                                        {{ $candidate->votes_count }} ({{ $votePercentage }}%)
                                                                    </span>
                                                                        </div>
                                                                        <div
                                                                            class="w-full bg-gray-200 rounded-full h-2">
                                                                            <div class="bg-black h-2 rounded-full"
                                                                                 style="width: {{ $votePercentage }}%"></div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Party List -->
                                                                    @if($candidate->partyLists)
                                                                        <div
                                                                            class="flex items-center justify-between text-[12px] font-semibold text-gray-700">
                                                                            <img
                                                                                src="{{ $candidate->partyLists?->logo_path ? asset('storage/' . $candidate->partyLists->logo_path) : asset('storage/assets/logo/default-logo.jpg') }}"
                                                                                alt="{{ $candidate->partyLists?->name ?? 'Party Logo' }}"
                                                                                class="w-10 h-10 object-contain rounded-full"
                                                                            />
                                                                            {{ $candidate->partyLists->name }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                @endif
                            @else
                                <div class="border border-gray-200 rounded-md p-8 text-center">
                                    <div class="flex justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-12 w-12 text-gray-500 opacity-20" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-[14px] font-medium mb-2">No currently created election</h3>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div x-show="tab === 'graphical'" wire:poll="$refresh">

            <div>
                <!-- Student Council Chart -->
                @if(!auth()->user()->hasRole('local-council-watcher'))
                    @if ($hasStudentCouncilPositions)
                        <livewire:charts.vote-chart-student-council :electionId="$selectedElection"/>
                    @endif
                @endif

                <!-- Local Council Chart -->
                @if(!auth()->user()->hasRole('student-council-watcher'))
                    @if ($hasLocalCouncilPositions)
                        <livewire:charts.vote-chart-local-council :electionId="$selectedElection"/>
                    @endif
                @endif
            </div>

        </div>

        <div x-show="tab === 'tabulated'">
            <div class="bg-white shadow-md rounded p-6">
                <div
                    class="text-[12px] bg-white mt-0 p-5 rounded-md md:max-w-[800px] min-[90%]:max-w-[100%] lg:max-w-[900px] xl:w-[100%] xl:min-w-[100%] 2xl:max-w-[1190px]">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                        <div class="flex space-x-2">
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-center  w-full md:w-auto mt-2">
                            <div class="relative sm:w-[250px] mb-4">
                                <input type="text" placeholder="Search..." aria-label="Search"
                                       class="rounded-md text-[10px] border bg-white text-black border-gray-300 h-8 pl-8 pr-4 focus:ring-1 focus:ring-black focus:border-black w-full">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2">

                                            </span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto mt-4 min-h-[350px]">
                        <div class="mt-4 min-h-[400px]">
                            <div class="space-y-6">
                                <!-- Student Council Election -->
                                @if(!auth()->user()->hasRole('local-council-watcher'))
                                    @if ($hasStudentCouncilPositions)
                                        <div x-data="{ open: false }" class="bg-white shadow-lg rounded-lg p-4"
                                             wire:key="student-council">
                                            <div class="flex w-full justify-center items-center">
                                                <h2 class="text-[16px] font-bold uppercase text-center mb-4">
                                                    {{ $selectedElectionCampus->name ?? 'No campus available' }} Student
                                                    Council Candidates
                                                </h2>
                                            </div>

                                            <div class="mt-3 text-[12px]">
                                                @foreach ($candidates->where('election_positions.position.electionType.name', 'Student Council Election')->groupBy('election_positions.position.name') as $position => $candidatesForPosition)
                                                    <div class="bg-gray-100 p-3 rounded mt-2"
                                                         wire:key="position-{{ $position }}">
                                                        <span class="font-semibold">{{ $position }}</span>
                                                        <div class="mt-2 space-y-2">
                                                            @foreach ($candidatesForPosition as $candidate)
                                                                <div
                                                                    class="flex justify-between items-center bg-white p-2 rounded">
                                                                    <span>{{ $candidate->users->first_name }} {{ $candidate->users->last_name }}</span>
                                                                    <span class="font-bold">{{ $candidate->votes_count }} votes</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="mt-2 space-y-2">
                                                            @php
                                                                $abstainCount = \App\Models\AbstainVote::where('election_id', $selectedElection)
                                                                    ->whereHas('position', function ($query) use ($position) {
                                                                        $query->where('name', $position);
                                                                    })
                                                                    ->count();
                                                            @endphp
                                                            <div
                                                                class="flex justify-between items-center bg-white p-2 rounded">
                                                                <span>Abstain</span>
                                                                <span class="font-bold">{{ $abstainCount }} votes</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <!-- Local Council Election -->
                                @if(!auth()->user()->hasRole('student-council-watcher'))
                                    @if ($hasLocalCouncilPositions)
                                        <div x-data="{ open: false }" class="bg-white shadow-lg rounded-lg p-4"
                                             wire:key="local-council">
                                            <div class="flex w-full justify-center items-center">
                                                <h2 class="text-[16px] font-bold uppercase text-center mb-4">
                                                    {{ $selectedElectionCampus->name ?? 'No campus available' }} Local
                                                    Council Candidates
                                                </h2>
                                            </div>

                                            <div class="mt-3 text-[12px]">
                                                <!-- Group candidates by council (program) -->
                                                @foreach ($candidates->where('election_positions.position.electionType.name', 'Local Council Election')->groupBy('users.program.council.name') as $council => $candidatesForCouncil)
                                                    <div class="bg-gray-100 p-3 rounded mt-2"
                                                         wire:key="council-{{ $council }}">
                                                        <span class="font-semibold">{{ $council }}</span>
                                                        <div class="mt-2 space-y-2">
                                                            <!-- Group candidates by position within the council -->
                                                            @foreach ($candidatesForCouncil->groupBy('election_positions.position.name') as $position => $candidatesForPosition)
                                                                <div class="bg-gray-50 p-2 rounded">
                                                                    <span class="font-medium">{{ $position }}</span>
                                                                    <div class="mt-1 space-y-1">
                                                                        @foreach ($candidatesForPosition as $candidate)
                                                                            <div
                                                                                class="flex justify-between items-center bg-white p-2 rounded">
                                                                                <span>{{ $candidate->users->first_name }} {{ $candidate->users->last_name }}</span>
                                                                                <span class="font-bold">{{ $candidate->votes_count }} votes</span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="mt-2 space-y-2">
                                                                        @php
                                                                            $abstainCount = \App\Models\AbstainVote::where('election_id', $selectedElection)
                                                                                ->whereHas('position', function ($query) use ($position) {
                                                                                    $query->where('name', $position);
                                                                                })
                                                                                ->count();
                                                                        @endphp
                                                                        <div
                                                                            class="flex justify-between items-center bg-white p-2 rounded">
                                                                            <span>Abstain</span>
                                                                            <span class="font-bold">{{ $abstainCount }} votes</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function initChart(canvasId, eventName) {
            const ctx = document.getElementById(canvasId).getContext('2d');

            // Initialize the chart with empty data
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {color: 'black'},
                            grid: {color: 'rgba(0, 0, 0, 0.1)'}
                        },
                        x: {
                            ticks: {color: 'black'},
                            grid: {color: 'rgba(0, 0, 0, 0.1)'}
                        }
                    },
                    plugins: {
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: 'black',
                            bodyColor: 'black',
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1
                        },
                        legend: {display: false},
                        datalabels: {
                            display: true,
                            align: 'end',
                            anchor: 'end',
                            formatter: (value, context) => {
                                if (value === 0) return null;
                                return `${context.dataset.label}: ${value} votes`;
                            },
                            color: 'black',
                            font: {weight: 'bold'}
                        }
                    }
                }
            });

            // Listen for Livewire events to update the chart
            Livewire.on(eventName, chartData => {
                console.log(`🔥 Raw Chart Data Received for ${canvasId}:`, chartData);

                if (Array.isArray(chartData) && chartData.length > 0) {
                    chartData = chartData[0];
                }

                console.log(`📌 Extracted Chart Data for ${canvasId}:`, chartData);

                if (!chartData || !chartData.labels || !chartData.datasets) {
                    console.error(`❌ Invalid chart data received for ${canvasId}!`);
                    return;
                }

                if (!chartData.labels.length || !chartData.datasets.length) {
                    console.warn(`⚠️ No valid data to display for ${canvasId}!`);
                    return;
                }

                // ✅ Assign totalVoters safely
                let totalVoters = chartData.totalVoters ?? 100; // Default to 100 if undefined

                // Update chart data
                chart.data.labels = chartData.labels;
                chart.data.datasets = chartData.datasets;

                // ✅ Update y-axis max dynamically
                chart.options.scales.y.max = totalVoters;
                chart.options.scales.y.ticks.stepSize = Math.ceil(totalVoters / 10);

                chart.update();

                console.log(`✅ Chart Updated Successfully for ${canvasId} with totalVoters:`, totalVoters);
            });

            return chart;
        }
    </script>
</div>
