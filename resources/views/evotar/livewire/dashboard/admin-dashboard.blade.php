<div>
    <div class="container mx-auto px-6 mt-2">
        <div class="flex flex-col lg:flex-row w-full justify-between items-start mb-4 ">
            <div class="mb-2 md:mb-0 text-left w-full lg:w-1/2">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Dashboard</h1>
                <p class=" text-gray-500 text-[11px]">Hi, {{ auth()->user()->first_name }} . Welcome back!</p>
            </div>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-1 w-full gap-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4  ">
                <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
                    <div class="bg-black flex items-center justify-center rounded-full"
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-sm text-gray-900 font-bold">{{ $totalVoters }}</h2>
                        <p class="text-[10px] mb-2 font-semibold text-gray-500">TOTAL VOTERS</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
                    <div class="bg-black flex items-center justify-center rounded-full"
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-sm text-gray-900 font-bold">{{ $totalCandidates }}</h2>
                        <p class="text-[10px] mb-2 font-semibold text-gray-500">TOTAL CANDIDATES</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
                    <div class="bg-black flex items-center justify-center rounded-full"
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-users-cog text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-sm text-gray-900 font-bold">{{ \App\Models\User::count() }}</h2>
                        <p class="text-[10px] mb-2 font-semibold text-gray-500">TOTAL SYSTEM USER</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
                    <div class="bg-black flex items-center justify-center rounded-full"
                         style="width: 40px; height: 40px;">
                        <i class="fas fa-tasks text-white text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h2 class="text-sm text-gray-900 font-bold">{{ $totalPositions }}</h2>
                        <p class="text-[10px] mb-2 font-semibold text-gray-500">TOTAL POSITION</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 sm:gap-4">
            <div class="flex-row space-y-2 mb-6">
                <div>
                    @if($selectedElection)
                        <livewire:timer.admin-dashboard-timer :selectedElection="$selectedElection"
                                                              wire:key="timer-election-{{ $selectedElection}}"/>
                    @else
                        <div class="bg-black text-white p-4 rounded-lg shadow-md text-center min-h-[160px]">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-stopwatch text-white text-sm"></i>
                                    <span class="text-sm font-bold text-white">No Election Available</span>
                                </div>

                                <div class="text-white">
                                    <i class="fas fa-pause-circle text-xl"></i>
                                    <i class="fas fa-stop-circle text-xl"></i>
                                </div>
                            </div>
                            <div class="tick" data-did-init="handleTickInit" data-credits="false">
                                <div
                                    data-repeat="true"
                                    data-layout="horizontal fit"
                                    data-transform="preset(d, h, m, s) -> delay"
                                    class="tick-container">

                                    <div class="tick-group">
                                        <div data-key="value" data-repeat="true" data-transform="pad(00) -> split -> delay">
                                            <span data-view="flip" class="tick-value"></span>
                                        </div>
                                        <span data-key="label" data-view="text" class="tick-label"></span>
                                    </div>
                                </div>
                                <div class="tick-onended-message" style="display: none">
                                    <p>Time's up</p>
                                </div>
                            </div>

                        </div>
                    @endif
                </div>
                <div
                    class="bg-white min-h-[160px] p-4 rounded-lg shadow-md w-full max-w-3xl transition-transform transform hover:scale-100">
                    <div class="flex items-center space-x-2">
                        <span class="text-center text-sm font-bold">Votes Information</span>
                    </div>
                    <div class="flex flex-wrap justify-around mt-4">
                        <div class="flex flex-col items-center ">
                            <div class="relative w-16 h-16">
                                <!-- SVG Progress Circle -->
                                <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 36 36">
                                    <path class="text-gray-200"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke-width="3" stroke="currentColor"></path>
                                    <path id="circular-progress-1" class="text-red-600"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke-width="3"
                                          stroke-dasharray="{{ $totalVoters > 0 ? ($totalVoterVoted / $totalVoters * 100) . ', 100' : '0, 100' }}"
                                          stroke="currentColor"></path>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-[11px] font-semibold text-gray-800">{{ $totalVoters > 0 ? number_format(($totalVoterVoted / $totalVoters) * 100, 2) : 0 }}%</span>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700 text-xs font-bold">Total Votes</p>
                        </div>
                        <div class="flex flex-col items-center ">
                            <div class="relative w-16 h-16">
                                <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 36 36">
                                    <path class="text-gray-200"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke-width="3" stroke="currentColor"></path>
                                    <path id="circular-progress-2" class="text-gray-500"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke-width="3" stroke-dasharray="{{ $totalVoters > 0 ? number_format(100 - ($totalVoterVoted / $totalVoters * 100), 2) . ', 100' : '0, 100'}}"
                                          stroke="currentColor"></path>
                                </svg>
                                <div class="absolute inset-0 top-1 flex items-center justify-center">
                                    <span class="text-[11px] font-semibold text-gray-800">{{ $totalVoters > 0 ? number_format(100 - ($totalVoterVoted / $totalVoters * 100), 2) : 0 }}%</span>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700 text-xs font-bold">Remaining Votes</p>
                        </div>
                        <div class="flex flex-col items-center ">
                            <div class="relative w-16 h-16">
                                <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 36 36">
                                    <path class="text-gray-200"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke-width="3" stroke="currentColor"></path>
                                    <path id="circular-progress-3" class="text-red-400"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                          fill="none" stroke-width="3" stroke-dasharray="{{ $totalVoters > 0 ? number_format(($totalVoterVoted / $totalVoters) * 100, 2) . ', 100' : '0, 100'}}"
                                          stroke="currentColor"></path>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-[11px] font-semibold text-gray-800">{{ $totalVoters > 0 ? number_format(($totalVoterVoted / $totalVoters) * 100, 2) : 0 }}%</span>
                                </div>
                            </div>
                            <p class="mt-2 text-gray-700 text-xs font-bold">Voter Turnout</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md max-w-4xl w-full lg:h-[340px] mb-4">
                <h1 class="text-sm font-bold mb-6">Campus Course Vote Turnout</h1>
                <div class="flex flex-col md:flex-row justify-center mb-4">
                    <div class="w-full md:w-2/5">
                        <canvas id="voteChart"
                                style="display: block; box-sizing: border-box; height: 150px; width: 208px;"
                                width="208" height="150"></canvas>
                    </div>
                    <div class="w-full md:w-2/2 md:pl-2 mt-2 md:mt-0">
                        <ul id="courseList" class="space-y-2 text-xs">
                        </ul>
                    </div>
                </div>
                <p class="text-[10px] text-gray-600 font-medium italic">
                    Note: This pie chart provides a detailed illustration of the campus vote turnout,
                    broken down by each course. The data represented here is live, meaning it is continually updated
                    to reflect the most current vote counts for each course. This real-time aspect allows viewers to
                    see the most accurate and up-to-date voting trends across the campus.
                </p>
            </div>
        </div>

        <div>
            <div class="w-full bg-white rounded-lg p-6">
                @if($selectedElection)
                    <div class="w-full flex flex-col justify-center items-center">
                        <div class="mb-6 text-center">
                            <h1 class="text-[16px] font-bold text-gray-900">Election Information</h1>
                        </div>
                        <div class="w-full flex flex-col md:flex-row justify-center items-center gap-4 lg:gap-8 mb-4 lg:mb-10 text-center">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs font-medium">
                                    Start: <span class="font-semibold">{{ (new DateTime($latestElection->date_started))->format('F j, Y, \a\t g:ia') }}</span>
                                </p>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-xs font-medium">
                                    End: <span class="font-semibold">{{ (new DateTime($latestElection->date_ended))->format('F j, Y, \a\t g:ia') }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
                            <!-- Participating Councils -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-center mb-4">
                                    <svg class="h-5 w-5 mr-2 text-gray-700"  xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M38-428q-18-36-28-73T0-576q0-112 76-188t188-76q63 0 120 26.5t96 73.5q39-47 96-73.5T696-840q112 0 188 76t76 188q0 38-10 75t-28 73q-11-19-26-34t-35-24q9-23 14-45t5-45q0-78-53-131t-131-53q-81 0-124.5 44.5T480-616q-48-56-91.5-100T264-760q-78 0-131 53T80-576q0 23 5 45t14 45q-20 9-35 24t-26 34ZM0-80v-63q0-44 44.5-70.5T160-240q13 0 25 .5t23 2.5q-14 20-21 43t-7 49v65H0Zm240 0v-65q0-65 66.5-105T480-290q108 0 174 40t66 105v65H240Zm540 0v-65q0-26-6.5-49T754-237q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780ZM480-210q-57 0-102 15t-53 35h311q-9-20-53.5-35T480-210Zm-320-70q-33 0-56.5-23.5T80-360q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-280Zm640 0q-33 0-56.5-23.5T720-360q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-280Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-440q0 50-34.5 85T480-320Zm0-160q-17 0-28.5 11.5T440-440q0 17 11.5 28.5T480-400q17 0 28.5-11.5T520-440q0-17-11.5-28.5T480-480Zm0 40Zm1 280Z"/></svg>
                                    <h2 class="text-sm font-bold text-gray-900">Participating Councils</h2>
                                </div>

                                <div class="space-y-3">
                                    @foreach($councils as $council)
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 mr-2 text-gray-500"  xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M38-428q-18-36-28-73T0-576q0-112 76-188t188-76q63 0 120 26.5t96 73.5q39-47 96-73.5T696-840q112 0 188 76t76 188q0 38-10 75t-28 73q-11-19-26-34t-35-24q9-23 14-45t5-45q0-78-53-131t-131-53q-81 0-124.5 44.5T480-616q-48-56-91.5-100T264-760q-78 0-131 53T80-576q0 23 5 45t14 45q-20 9-35 24t-26 34ZM0-80v-63q0-44 44.5-70.5T160-240q13 0 25 .5t23 2.5q-14 20-21 43t-7 49v65H0Zm240 0v-65q0-65 66.5-105T480-290q108 0 174 40t66 105v65H240Zm540 0v-65q0-26-6.5-49T754-237q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780ZM480-210q-57 0-102 15t-53 35h311q-9-20-53.5-35T480-210Zm-320-70q-33 0-56.5-23.5T80-360q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-280Zm640 0q-33 0-56.5-23.5T720-360q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-280Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-440q0 50-34.5 85T480-320Zm0-160q-17 0-28.5 11.5T440-440q0 17 11.5 28.5T480-400q17 0 28.5-11.5T520-440q0-17-11.5-28.5T480-480Zm0 40Zm1 280Z"/></svg>

                                            <p class="text-[12px] text-gray-800">{{ $council->name }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Available Positions -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" class="h-5 w-5 mr-2 text-gray-700" fill="currentColor"><path d="M160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm0-80h640v-440H600q0 33-23.5 56.5T520-520h-80q-33 0-56.5-23.5T360-600H160v440Zm80-80h240v-18q0-17-9.5-31.5T444-312q-20-9-40.5-13.5T360-330q-23 0-43.5 4.5T276-312q-17 8-26.5 22.5T240-258v18Zm320-60h160v-60H560v60Zm-200-60q25 0 42.5-17.5T420-420q0-25-17.5-42.5T360-480q-25 0-42.5 17.5T300-420q0 25 17.5 42.5T360-360Zm200-60h160v-60H560v60ZM440-600h80v-200h-80v200Zm40 220Z"/></svg>

                                    <h2 class="text-sm font-bold text-gray-900">Available Positions</h2>
                                </div>

                                @php
                                    $groupedPositions = $positions->groupBy(function($position) {
                                        return $position->electionType ? $position->electionType->name : 'Other';
                                    });
                                @endphp

                                @foreach($groupedPositions as $type => $positionGroup)
                                    <div class="mb-4">
                                        <h3 class="text-[12px] font-semibold text-gray-800 mb-2">{{ $type }}</h3>
                                        <div class="space-y-2 max-h-[480px] overflow-y-auto">
                                            @foreach($positionGroup as $position)
                                                <div class="flex items-center">
                                                    <svg class="h-5 w-5 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg" height="20px"
                                                         viewBox="0 -960 960 960" width="20px" fill="currentColor">
                                                        <path
                                                            d="M624-384q-50 0-85-35t-35-85q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35ZM384-144v-63q0-28 14.5-51t38.5-35q43-21 90-32t97-11q50 0 97 11t90 32q24 12 38.5 35t14.5 51v63H384Zm73-72h334q-30-23-72-35.5T624-264q-53 0-95 12.5T457-216Zm167-240q20.4 0 34.2-13.8Q672-483.6 672-504q0-20.4-13.8-34.2Q644.4-552 624-552q-20.4 0-34.2 13.8Q576-524.4 576-504q0 20.4 13.8 34.2Q603.6-456 624-456Zm0-48Zm0 288ZM144-396v-72h288v72H144Zm0-300v-72h432v72H144Zm293 150H144v-72h326q-12 16-20.11 33.78Q441.77-566.44 437-546Z"/>
                                                    </svg>
                                                    <p class="font-bold text-[12px] text-gray-800">{{ $position->name }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Candidates -->
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h2 class="text-sm font-bold text-gray-900">Candidates</h2>
                                </div>
                                <!-- Scrollable wrapper -->
                                <div class="space-y-4 max-h-[480px] overflow-y-auto pr-2">
                                    @foreach($candidates as $candidate)
                                        <div class="border border-gray-100 rounded-lg p-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $candidate->users->first_name . ' ' . $candidate->users->last_name }}</p>
                                                <p class="text-[10px] text-gray-600">
                                                    Running for <span class="text-red-600">{{ $candidate->election_positions->position->name }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart.js Data Labels Plugin CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
        // Register the plugin with Chart.js
        Chart.register(ChartDataLabels);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('voteChart');
            if (ctx) {
                const chartCtx = ctx.getContext('2d');

                fetch('/labels')
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.labels;  // Labels fetched from the backend
                        const colors = data.colors;  // Colors fetched from the backend (if available)
                        const votes = data.votes;    // Data for the chart (e.g., vote counts)

                        // Dynamically populate the list with course names and their colors
                        const courseList = document.getElementById('courseList');
                        if (courseList) {
                            courseList.innerHTML = ''; // Clear existing content
                            labels.forEach((label, index) => {
                                const listItem = document.createElement('li');
                                listItem.classList.add('flex', 'items-center', 'font-medium');
                                listItem.innerHTML = `
                            <span class="w-4 h-4 mr-1" style="background-color: ${colors[index]};"></span>
                            <span>${label}</span>
                        `;
                                courseList.appendChild(listItem);
                            });
                        }

                        // Create the chart with dynamic data
                        const voteChart = new Chart(chartCtx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,  // Dynamic labels
                                datasets: [{
                                    data: votes,  // Dynamic data
                                    backgroundColor: colors,  // Dynamic colors
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching labels and data:', error));
            } else {
                console.error('Canvas element with ID "voteChart" not found!');
            }
        });
    </script>
</div>
