<x-app-layout>
    @vite(['resources/js/student_attendance.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            Swal.fire({
                title: "Auto-attendance is on",
                timer: 1500,
                showConfirmButton: false,
            });
        })
    </script>

    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-900 leading-tight mb-3">
            {{ __('Student Attendance') }}
        </h2>
        <div class="flex justify-between">
            <div class="">
                <h2 class="text-2xl font-bold text-gray-800">
                    Event name: 

                    @if ($event)
                        <span class="text-gray-600">
                            {{ $event->event_name }}
                        </span>
                    @else
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                Swal.fire({
                                title: "Oops!",
                                text: "There are no events yet!",
                                icon: "warning"
                                });
                            });
                        </script>
                        <span class="text-red-600">
                            There are no event details yet
                        </span>
                    @endif

                </h2>
            </div>
            <div class="">
                <kbd class="px-2 py-1.5 text-xs font-semibold text-green-500 bg-gray-900 border border-gray-900 rounded-lg">
                    Created at:
                    <span>
                        @if ($event)
                            <span class="text-yellow-500">
                                {{ date('Y-m-d, h:i A', strtotime($event->date)) }}
                            </span>
                        @else
                            <span class="text-yellow-500">
                                No Event
                            </span>
                        @endif
                    </span>
                </kbd>
            </div>
        </div>
        <div class="flex justify-between items-start p-4 mt-3 bg-gray-900 border-b rounded-md">
            <div>
                @if ($event)
            
                    <div class="py-1">
                        <div class="block">
                            <h2 class="text-lg font-bold text-gray-100">
                                Orientation:
                                <span class="text-gray-200 font-light">
                                    {{$event->isWholeDay ? 'Half-Day' : 'Whole-Day'}}
                                </span>
                            </h2>
                        </div>
                        <div class="block">
                            <h2 class="text-lg font-bold text-gray-100">
                                Check In:
                                <span class="text-gray-200 font-light">
                                    {{ date_format(date_create($event->checkIn_start), 'h:i A') }}
                                </span>
                                -
                                <span class="text-gray-200 font-light">
                                    {{ date_format(date_create($event->checkIn_end), 'h:i A') }}
                                </span>
                            </h2>
                        </div>
                        <div class="block">
                            <h2 class="text-lg font-bold text-gray-100">
                                Check Out:
                                <span class="text-gray-200 font-light">
                                    {{ date_format(date_create($event->checkOut_start), 'h:i A') }}
                                </span>
                                -
                                <span class="text-gray-200 font-light">
                                    {{ date_format(date_create($event->checkOut_end), 'h:i A') }}
                                </span>
                            </h2>
                        </div>

                    </div>
                @endif


            </div>

            @if ($event)
                <div x-data="{ play: false }" class="flex">
                    <div x-data="{ open: false }" class="transition-all">
                        <div class="flex flex-col justify-end">
                            <button x-on:click="open = ! open" onclick="myFunction()"
                                class="bg-green-500 px-3 py-2 mb-2 hover:bg-green-700 transition-full max-w-xs text-center rounded-xl text-white shadow-lg">
                                <div class="flex gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                    </svg>
                                      
                                    Manually Enter RFID
                                </div>
                            </button>
                            <button onclick="startInterval()"
                                class="bg-yellow-500 px-3 py-2 mb-2 hover:bg-yellow-400 transition-full max-w-xs text-center rounded-xl text-white shadow-lg">
                                <div class="flex gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243-1.59-1.59" />
                                    </svg>
                                      
                                    Start Attendance
                                </div>
                            </button>
                        </div>

                        <div x-show.important="open"
                            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                            <div id="modalAttendance" x-on:click.outside="open = false"
                                class="max-w-[1000px] min-w-[500px] bg-white p-6 rounded-lg shadow-lg">
                                <div class="border-b-2 border-gray-300 mb-5">
                                    <h1 class="text-2xl font-bold">
                                        Attendance is Starting
                                    </h1>
                                </div>
                                <div class="mb-5">
                                    <form id="attendanceForm" method="POST" action="{{route('attendanceStudent')}}">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="uri" value="{{ route('attendanceStudent') }}">
                                        <div class="flex flex-col">
                                            <label class="text-lg font-semibold" for="">Enter RFID:</label>
                                            <input type="text" name="s_rfid" id="inputField" autocomplete="off">
                                        </div>
                                    </form>
                                </div>

                                <div class="flex justify-end">
                                    <button x-on:click="open = false" onclick="stopAttendance()"
                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            @endif

        </div>
    </x-slot>

    <div class="mt-4">
        <div class="flex justify-between mb-3">
            <h3 class="text-3xl text-gray-900 font-extrabold">
                Attendance Record
            </h3>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="min-w-full w-full text-sm text-center rtl:text-right text-gray-900 font-semibold">
                <thead class="text-lg font-semibold text-gray-100 uppercase bg-green-700">
                    <tr>
                        <td class="py-5">Name</td>
                        <td>Program</td>
                        <td>Set</td>
                        <td>Year Level</td>
                        <td>Time In</td>
                        <td>Time Out</td>
                        <td>Date</td>
                    </tr>
                </thead>
                <tbody id="student_table_body">
                    @isset($students)
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->s_fname . " ". $student->s_lname }} </td>
                                <td>{{$student->s_program}}</td>
                                <td>{{$student->s_set}}</td>
                                <td>{{$student->s_lvl}}</td>
                                <td>{{$student->attend_checkIn}}</td>
                                <td>{{$student->attend_checkOut}}</td>
                                <td>{{$student->created_at}}</td>
                            </tr>
                        @endforeach
                    @endisset

                </tbody>
            </table>
        </div>
    </div>

    <form id="getAttendanceForm" hidden>
        <input type="text" id="getURI" value="{{ route('getAttendanceRecent') }}" hidden>
    </form>

    @if ($event)
            {{-- FOR AUTO ATTENDANCE --}}
    <form id="auto_attendanceForm" method="POST" class="fixed -z-10">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <input type="hidden" name="uri" value="{{ route('attendanceStudent') }}">
        <input type="text" name="s_rfid" id="inputField1" class="bg-transparent border-none" autocomplete="off">
    </form>
    @endif

</x-app-layout>
<script>
    // Added Pop Ups from Sweet Alert2
    let startAttendance = false;

    const scannedData = document.getElementById("inputField");

    function myFunction() {
        console.log("attendance start");
        document.getElementById("inputField").focus();
        startAttendance = true;

        stopInterval();
    }

    function stopAttendance() {
        console.log("attendance stop");
        startAttendance = false;
        Swal.fire({
            icon: "warning",
            title: "Attendance Stopped!",
            showConfirmButton: false,
            timer: 1000
        });

        startInterval();
    }

</script>
