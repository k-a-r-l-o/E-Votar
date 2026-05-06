<div class="flex flex-col items-start space-y-4 w-full px-0">
    <div class="flex items-center justify-between w-full mb-3">
        <h1 class="text-lg font-bold text-gray-800">Election Results</h1>
    </div>

    <div class="bg-white p-6 text-[11px] md:text-[12px] mt-4 shadow-md rounded w-full">
        <div class=" mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center mb-2">
                <div class="flex items-center justify-between flex-wrap md:flex-nowrap gap-2">
                    <button
                        class="bg-white border border-gray-300 rounded h-8 px-3 py-2 flex items-center space-x-1 hover:drop-shadow hover:bg-gray-200 hover:scale-105 hover:ease-in-out hover:duration-300 transition-all duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:-translate-y-1 active:scale-x-90 active:scale-y-110"
                        wire:click="exportElectionResult"
                        wire:loading.attr="disabled">
                        <svg wire:loading.remove wire:target="exportElectionResult" xmlns="http://www.w3.org/2000/svg"
                             height="20px" viewBox="0 -960 960 960"
                             width="20px" fill="#000000">
                            <path
                                d="M480-336 288-528l51-51 105 105v-342h72v342l105-105 51 51-192 192ZM263.72-192Q234-192 213-213.15T192-264v-72h72v72h432v-72h72v72q0 29.7-21.16 50.85Q725.68-192 695.96-192H263.72Z"/>
                        </svg>
                        <span wire:loading.remove wire:target="exportElectionResult" class="text-[12px]">Export Election Result</span>
                        <svg wire:loading wire:target="exportElectionResult" class="animate-spin h-5 w-5 mr-3"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading wire:target="exportElectionResult">Exporting...</span>
                    </button>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-center  w-full md:w-auto mt-2">
                    <div class="relative sm:w-[250px] mb-4">
                        <input type="text" placeholder="Search..." aria-label="Search"
                               class="rounded-md text-[10px] border bg-white text-black border-gray-300 h-8 pl-8 pr-4 focus:ring-1 focus:ring-black focus:border-black w-full">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2">
                                <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.68208 10.7458C8.66576 11.5361 7.38866 12.0067 6.00167 12.0067C2.68704 12.0067 0 9.31891 0 6.00335C0 2.68779 2.68704 0 6.00167 0C9.31631 0 12.0033 2.68779 12.0033 6.00335C12.0033 7.39059 11.533 8.66794 10.743 9.6845L13.7799 12.7186C14.0731 13.0115 14.0734 13.4867 13.7806 13.7799C13.4878 14.0731 13.0128 14.0734 12.7196 13.7805L9.68208 10.7458ZM10.5029 6.00335C10.5029 8.49002 8.48765 10.5059 6.00167 10.5059C3.5157 10.5059 1.50042 8.49002 1.50042 6.00335C1.50042 3.51668 3.5157 1.50084 6.00167 1.50084C8.48765 1.50084 10.5029 3.51668 10.5029 6.00335Z"
                                    fill="#000000"/>
                    </svg>
                                            </span>
                    </div>
                </div>
            </div>

            <div>

                @if($selectedElection)
                    @if($latestElection->status == 'completed')
                        <div class="w-full mt-8 md:mt-0 mb-8" wire:key="voter-tally-{{ $selectedElection }}">
                            <div
                                class="bg-gray-100 text-black uppercase text-[11px] leading-normal text-center font-bold py-2 border-b border-gray-300 rounded-t-lg">
                                {{ $selectedElectionName }} summary
                            </div>
                            <table class="w-full border-collapse border-t border-b border-gray-100 rounded-t-lg">
                                <tbody>
                                <tr class="bg-white border-b border-gray-100 py-3 px-6 text-left">
                                    <td class="py-3 px-6 border-t border-b border-gray-100">Voters Turnout</td>
                                    <td class="py-3 px-6 border-t border-b border-gray-100">
                                        @if($totalVoters > 0)
                                            {{ number_format(($totalVoterVoted / $totalVoters) * 100, 2) }}%
                                            {{ ' - ' . $totalVoterVoted . '/' . $totalVoters }}
                                        @else
                                            0% - 0/0
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <td class="py-3 px-6 border-t border-b border-gray-100">Voters Who Actually Voted</td>
                                    <td class="py-3 px-6 border-t border-b border-gray-100">{{ $totalVoterVoted }}</td>
                                </tr>


                                </tbody>
                            </table>
                        </div>
                        <div class="w-full">
                            <!-- Student Council Election -->
                            @if ($hasStudentCouncilPositions && $studentCouncilWinners != null)
                                <div class="mb-8">
                                    <h2 class="text-[14px] font-bold uppercase text-center mb-4">
                                        {{ $selectedElectionCampus->name ?? 'No campus available' }} Student Council Election Results
                                    </h2>
                                    <div class="overflow-x-auto min-h-[350px]">
                                        <table class="min-w-full">
                                            <thead>
                                            <tr class="w-full bg-gray-100 text-black uppercase text-[11px] leading-normal">
                                                <th class="py-2 px-6 text-left rounded-tl-lg border-b border-gray-300">Position</th>
                                                <th class="py-2 px-8 text-left border-b border-gray-300">Candidate</th>
                                                <th class="py-2 px-6 text-left border-b border-gray-300">Party list</th>
                                                <th class="py-2 px-6 text-left border-b border-gray-300">Abstain Count</th>
                                                <th class="py-2 px-6 text-left border-b border-gray-300">Vote Tally</th>
                                                <th class="py-2 px-6 text-left rounded-tr-lg border-b border-gray-300">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody class="text-black text-[12px] font-light">
                                            @foreach ($studentCouncilWinners as $winner)
                                                @php
                                                    $positionId = $winner['position_id'];
                                                    $voteData = $voteTally->firstWhere('position_id', $positionId);
                                                    $abstainCount = $voteData['abstain_count'] ?? 0;
                                                    $voteTallyCount = $voteData['vote_tally'] ?? 0;
                                                    $totalVoters = $voteData['total_voters'] ?? 0;
                                                @endphp
                                                <tr class="border-b border-gray-100">
                                                    <td class="py-3 px-6 text-left">{{ $winner['position'] }}</td>
                                                    <td class="py-3 px-8 text-left font-bold">
                                                        {{ $winner['candidate'] ? $winner['candidate']->users->first_name . ' ' . $winner['candidate']->users->last_name : 'No winner' }}
                                                    </td>
                                                    <td class="py-3 px-6 text-left">{{ $winner['candidate'] ? $winner['candidate']->partyLists->name : '-' }}</td>
                                                    <td class="py-3 px-6 text-left">{{ $abstainCount }}</td>
                                                    <td class="py-3 px-6 text-left">
                                                        <div class="font-bold">{{ $voteTallyCount }} votes</div>
                                                    </td>
                                                    <td class="py-3 px-6 text-left">{{ $totalVoters }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif


                            <!-- Local Council Election Results -->
                            @if ($hasLocalCouncilPositions && $localCouncilWinners != null)
                                <div class="mb-4">
                                    <h2 class="text-[14px] font-bold uppercase text-center mb-4">
                                        {{ $selectedElectionCampus->name ?? 'No campus available' }} Local Council Election Results
                                    </h2>
                                    @foreach ($localCouncilWinners as $council => $winners)
                                        <div class="mb-8">
                                            <h3 class="text-[14px] font-semibold mb-2 uppercase">{{ $council }}</h3>
                                            @php
                                                $winnersByMajor = collect($winners)->groupBy(function ($winner) {
                                                    return $winner['major'] ?? 'N/A';
                                                });
                                            @endphp
                                            @foreach ($winnersByMajor as $major => $majorWinners)
                                                @if ($major !== 'N/A')
                                                    <h4 class="text-[12px] font-bold uppercase">(MAJOR - {{ $major }})</h4>
                                                @endif
                                                <div class="overflow-x-auto mb-2">
                                                    <table class="min-w-full">
                                                        <thead>
                                                        <tr class="w-full bg-gray-100 text-black uppercase text-[11px] leading-normal">
                                                            <th class="py-2 px-6 text-left rounded-tl-lg border-b border-gray-300">Position</th>
                                                            <th class="py-2 px-6 text-left border-b border-gray-300">Candidate</th>
                                                            <th class="py-2 px-6 text-left border-b border-gray-300">Partylist</th>
                                                            <th class="py-2 px-6 text-left border-b border-gray-300">Abstain Count</th>
                                                            <th class="py-2 px-6 text-left border-b border-gray-300">Vote Tally</th>
                                                            <th class="py-2 px-6 text-left rounded-tr-lg border-b border-gray-300">Total Voters</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-black text-[12px] font-light">
                                                        @foreach ($majorWinners as $winner)
                                                            @php
                                                                $positionId = $winner['position_id'];
                                                                $voteData = $voteTally->first(function ($item) use ($positionId, $council) {
                                                                    return $item['position_id'] == $positionId && $item['council'] == $council;
                                                                });
                                                                $abstainCount = $voteData['abstain_count'] ?? 0;
                                                                $voteTallyCount = $voteData['vote_tally'] ?? 0;
                                                                $totalVoters = $voteData['total_voters'] ?? 0;
                                                            @endphp
                                                            <tr class="border-b border-gray-100">
                                                                <td class="py-3 px-6 text-left">{{ $winner['position'] }}</td>
                                                                <td class="py-3 px-8 text-left font-bold">
                                                                    {{ $winner['candidate'] ? $winner['candidate']->users->first_name . ' ' . $winner['candidate']->users->last_name : 'No winner' }}
                                                                </td>
                                                                <td class="py-3 px-6 text-left">{{ $winner['candidate'] ? $winner['candidate']->partyLists->name : '-' }}</td>
                                                                <td class="py-3 px-6 text-left">{{ $abstainCount }}</td>
                                                                <td class="py-3 px-6 text-left">
                                                                    <div class="font-bold">{{ $winner['votes_count'] }} votes</div>
                                                                </td>
                                                                <td class="py-3 px-6 text-left">{{ $totalVoters }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Election in Progress</h3>
                                    <div class="mt-1 text-sm text-yellow-700">
                                        <p>Results will be available once the election period has concluded.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        </div>
    </div>
</div>

