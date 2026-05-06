<div>
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
    <div class="container mx-auto px-6 mt-2">
        <div class="flex flex-row justify-between items-start mb-4 ">
            <div class="mb-2 md:mb-0 text-left">
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
                        <h2 class="text-sm text-gray-900 font-bold">{{ \App\Models\User::has('roles')->count() }}</h2>
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
                        <div class="bg-black text-white p-4 rounded-lg shadow-md text-center min-h-[160px] ">
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
                                    <p>Election has Ended!</p>
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
            <div class="w-full bg-white rounded-lg shadow-md">
                @if($selectedElection && $latestElection)
                    @if(auth()->user()->hasRole('student-council-watcher'))
                        <div class="w-full flex flex-col p-6 justify-center items-center">
                            <div>
                                <p class="text-[14px] font-bold">STUDENT COUNCIL ELECTION INFORMATION</p>
                            </div>
                            <div class="w-1/2 flex justify-between">
                                <div>
                                    <p class="text-[12px] font-bold">
                                        Start Date :  <span class="font-normal">{{ (new DateTime($latestElection->date_started))->format('F j, Y, \a\t g:ia') }}</span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[12px] font-bold">
                                        End Date :  <span class="font-normal">{{ (new DateTime($latestElection->date_ended))->format('F j, Y, \a\t g:ia') }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="w-full h-full">
                                <div class="container mx-auto px-4 py-4">
                                        <!-- Student Council Section -->
                                        @if($hasStudentCouncilPositions && $hasStudentCouncilCandidate)
                                            <h2 class="text-[16px] font-bold uppercase text-center mb-6">{{ $selectedElectionCampus->name ?? 'No campus available' }}
                                                Student Council Candidates</h2>
                                        <div id="studentCouncil" class="swiper" wire:key="student-council-list">
                                            <div class="swiper-wrapper max-w-[500px]">
                                                @foreach($candidates->where('election_positions.position.electionType.name', 'Student Council Election') as $candidate)
                                                    <div class="swiper-slide" wire:key="candidate-{{ $candidate->id }}">
                                                        <div class="relative">
                                                            <div class="flip-card-container mt-[-45px]" wire:key="cards-{{ $candidate->id }}">
                                                                <div class="flip-card" onclick="this.classList.toggle('flipped')">
                                                                    <!-- Front of the card -->
                                                                    <div class="flip-card-front bg-white p-6 shadow-md min-h-[320px]">
                                                                        <div class="flex justify-center items-center">
                                                                            <p class="text-[12px]">
                                                                                Running for:
                                                                                <span class="text-red-900 uppercase tracking-tighter font-semibold">
                                                        {{ $candidate->election_positions->position->name }}
                                                    </span>
                                                                            </p>
                                                                        </div>
                                                                        <div>
                                                                            <div class="flex justify-end mt-2 mr-[15px]">
                                                                                <img class="w-[85px]" src="{{ asset('storage/assets/icon/usep_logo_svg.png') }}" alt="">
                                                                            </div>
                                                                            <div class="mt-[-38px] flex justify-center">
                                                                                <div class="border-2 border-black">
                                                                                    <img class="w-[110px]" src="{{ asset('storage/' . $candidate->users->profile_photo_path) ?? asset('storage/assets/profile/cat_meme.jpg') }}" alt="">
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2 text-center">
                                                                                <div class="flex justify-center">
                                                                                    <p class="text-black uppercase font-black text-[11px]">
                                                                                        {{ $candidate->users->first_name }} {{ $candidate->users->middle_initial }}. {{ $candidate->users->last_name }}
                                                                                    </p>
                                                                                </div>
                                                                                <p class="text-black capitalize font-semibold text-[10px]">
                                                                                    {{ $candidate->users->year_level }} year
                                                                                </p>
                                                                                <p class="text-black capitalize font-semibold text-[12px] leading-none">
                                                                                    @php
                                                                                        $programName = $candidate->users->program->name;
                                                                                        $programName = str_starts_with($programName, 'Bachelor of Science') ? 'BS ' . substr($programName, strlen('Bachelor of Science')) : $programName;
                                                                                    @endphp
                                                                                    <span class="program-name !text-[12px]" title="{{ $programName }}">
                                                            {{ $programName }}
                                                        </span>
                                                                                </p>
                                                                                <p class="text-black capitalize font-semibold text-[11px] leading-none">
                                                                                    {{ optional($candidate->users->programMajor)->name ?? '' }}
                                                                                </p>
                                                                                <p class="text-black mt-2 capitalize italic font-semibold text-[11px]">
                                                                                    {{ $candidate->partyLists->name }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Back of the card with motto -->
                                                                    <div class="flip-card-back bg-white p-6 shadow-md min-h-[320px] flex items-center justify-center">
                                                                        <div class="text-center p-4">
                                                                            <h3 class="font-bold text-lg mb-2">ADVOCACY</h3>
                                                                            <p class="italic text-sm">{{ $candidate->description ?? 'No motto provided' }}</p>
                                                                            <p class="italic text-xs">
                                                                                -{{ $candidate->users->first_name . ' ' . substr($candidate->users->last_name, 0, 1) . '.' }}
                                                                            </p>
                                                                            <p class="italic text-[10px]">
                                                                                {{ Str::endsWith($candidate->election_positions->position->name, 'ent') ? Str::replaceLast('ent', 'ential', $candidate->election_positions->position->name) : $candidate->election_positions->position->name }} Aspirant.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                         @else
                                        <p>No Election Available</p>
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
                    @endif
                        @if(auth()->user()->hasRole('local-council-watcher'))
                            <div class="w-full flex flex-col p-6 justify-center items-center">
                                <div>
                                    <p class="text-[14px] font-bold">LOCAL COUNCIL ELECTION INFORMATION</p>
                                </div>
                                <div class="w-1/2 flex justify-between">
                                    <div>
                                        <p class="text-[12px] font-bold">
                                            Start Date :  <span class="font-normal">{{ (new DateTime($latestElection->date_started))->format('F j, Y, \a\t g:ia') }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-bold">
                                            End Date :  <span class="font-normal">{{ (new DateTime($latestElection->date_ended))->format('F j, Y, \a\t g:ia') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="w-full h-full">
                                    <div class="container mx-auto px-4 py-4">
                                        <!-- Student Council Section -->
                                        @if($hasLocalCouncilPositions && $hasLocalCouncilCandidate )
                                            <h2 class="text-[16px] font-bold uppercase text-center mt-8 mb-4">Local Councils
                                                Candidates</h2>
                                            @foreach($candidates->where('election_positions.position.electionType.name', 'Local Council Election')->groupBy('users.program.council.name') as $programName => $localCandidates)
                                                <h3 class="text-[12px] px-4 font-bold text-center uppercase text-gray-700 mt-6 mb-8">{{ $programName }}
                                                    Organization</h3>
                                                <div class="swiper" id="localCouncil">
                                                    <div class="swiper-wrapper max-w-[500px]">
                                                        @foreach($localCandidates as $candidate)
                                                            <div wire:key="candidate-{{ $candidate->id }}" class="swiper-slide mb-2">
                                                               <div class="relative">
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
                                                                                       <img class="w-[85px]"
                                                                                            src="{{ asset('storage/assets/icon/usep_logo_svg.png') }}"
                                                                                            alt="">
                                                                                   </div>
                                                                                   <div class="mt-[-38px] flex justify-center">
                                                                                       <div class="border-2 border-black">
                                                                                           <img class="w-[110px]"
                                                                                                src="{{ asset('storage/' . $candidate->users->profile_photo_path) ?? asset('storage/assets/profile/cat_meme.jpg') }}"
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
                                                               </div>

                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
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
                        @endif
                @endif
            </div>

        </div>

        <div class="flex flex-row justify-between text-gray-500 text-[10px] mt-4">
            <i>Copyright@2025</i>
            <i>E-Votar@2024</i>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('voteChart');
            if (ctx) {
                const chartCtx = ctx.getContext('2d');

                fetch('/labels')  // Fetch data from your backend
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
