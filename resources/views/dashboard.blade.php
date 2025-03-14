<x-app-layout>
    @vite(['resources/js/dashboard.js'])

    {{-- Add these styles at the top of the file --}}
    <style>
        .modal-backdrop {
            z-index: 60;
        }
        .modal-content {
            z-index: 61;
        }
        #tableContainer {
            z-index: 10;
            position: relative;
        }
        [x-cloak] {
            display: none !important;
        }
        .fixed {
            z-index: 50;
        }
    </style>

    {{-- Implemented Sweet Alert Pop Ups on Conditionals --}}
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "Oops!...",
                    html: `
                    <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside text-left">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                    showConfirmButton: true,
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        </script>
    @endif

    {{-- <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="">

            </div>
            <div class="">
                <img src="{{asset('images/icons/default-image.svg')}}" alt="">
            </div>
        </div>
    </x-slot> --}}

    <div class="flex gap-5 mb-4">
        <div class="bg-gray-900 h-auto basis-1/2 p-3 rounded-md flex flex-col justify-between">
            <div class="flex justify-between">
                <div class="py-1 px-3">
                    <h2 class="text-4xl font-semibold text-green-500">Welcome, <span class="text-gray-200">{{ ucwords(auth()->user()->admin_uname) }}</span></h2>
                    <p class="text-gray-200 pt-1">RFID Attendance Management System</p>
                </div>
                <div class="py-1 px-3">
                    <a href="{{route('profile.edit')}}">
                        <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>                
                        </button>
                    </a>
                </div>
            </div>
            <div class="flex justify-start gap-3">
                <button onclick="location.href = '{{ route('attendance') }}'"
                    class="bg-gradient-to-r from-lime-500 to-lime-600 hover:bg-green-700 hover:scale-105 ease-linear transition-all text-white rounded-2xl px-4 py-4 text-lg flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                    </svg>
                    Attendance

                </button>
            </div>
        </div>
        <div class="bg-gray-900 basis-1/2 rounded-lg p-4 shadow-md">
            <div class="flex flex-col items-start">
                <h1 class="text-4xl font-semibold text-gray-200">Student Statistics</h1>
            </div>
            <div class="flex gap-5 justify-center items-center py-10">
                <!-- Students Card -->
                <div onclick="window.location.href = '{{ route('students') }}'"
                    class="flex items-center gap-3 bg-gradient-to-r from-lime-600 to-lime-700 text-white rounded-lg p-4 shadow-lg hover:shadow-xl hover:scale-105 transition-all cursor-pointer">
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-14 h-14">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">{{ $studentCount }}</h1>
                        <p class="text-lg font-medium opacity-90">Enrolled Students</p>
                    </div>
                </div>
    
                <!-- Graduates Card -->
                <div onclick="window.location.href = '{{ route('students') }}'"
                    class="flex items-center gap-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-4 shadow-lg hover:shadow-xl hover:scale-105 transition-all cursor-pointer">
                    <div class="bg-white/20 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-14 h-14">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">{{ $graduateCount }}</h1>
                        <p class="text-lg font-medium opacity-90">Graduates</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>


    <div class="flex items-center justify-between bg-white p-3 rounded-lg">
        <div class="flex gap-5">
            <div id="clockdate">
                <div class="clockdate-wrapper bg-gray-800 p-2 max-w-xs w-full text-center rounded-xl mx-auto shadow-lg border-2 border-gray-900">
                    <div id="clock" class="bg-gray-800 text-lime-500 text-2xl font-sans shadow-sm"></div>
                    <div id="date" class="tracking-widest text-sm font-sans text-white"></div>
                </div>
            </div>
        </div>

        {{-- Update the modals wrapper to include higher z-index --}}
        <div class="flex gap-3 relative z-50">
            {{-- MODALS --}}
            <x-new-modal>
                <x-slot name="button"
                    class="bg-green-500 hover:bg-green-600 ease-linear transition-all text-white rounded-xl px-5 text-2xl
                    flex items-center p-4 gap-1">
                    <div class="flex px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        Event
                    </div>
                </x-slot>

                <x-slot name="heading">
                    Create Event
                </x-slot>
                <x-slot name="content">
                    <form x-ref="eventForm" action="{{ route('addEvent') }}" method="POST" class="min-w-[500px]">
                        @csrf

                        <div class="flex flex-col mb-3">
                            <label for="">Day or Event:</label>
                            <input type="text" placeholder="Enter Event Name" name="event_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="">Event Date:</label>
                            <input type="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <p class="text-xl text-gray-900 font-bold">Check In:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="start-time" name="checkIn_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="checkIn_end" name="checkIn_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                        </div>
                        <p class="text-xl text-gray-900 font-bold">Check Out:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="start-time" name="checkOut_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="end-time" name="checkOut_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 items-center mb-3">
                            <input type="checkbox" id="wholeDay" name="wholeDay">
                            <label for="">Whole Day?</label>
                        </div>

{{-- AFTERNOON ATTENDANCE --}}

                        <div id="afternoon_attendance" class="hidden transition-all">
                            <p class="text-2xl font-bold text-gray-900">Afternoon Attendance</p>
                            <p class="text-xl font-semibold text-gray-900">Check In:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900">Start
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="start-time" name="afternoon_checkIn_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900">End
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="end-time" name="afternoon_checkIn_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                            <p class="text-xl text-gray-900 font-bold">Check Out:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900">Start
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="start-time" name="afternoon_checkOut_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900">End
                                        time:</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path fill-rule="evenodd"
                                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="end-time" name="afternoon_checkOut_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </x-slot>
                <x-slot name="footer">
                    <button x-on:click="$refs.eventForm.submit()" type="submit"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                </x-slot>
            </x-new-modal>
            {{-- MODALS --}}
            <x-new-modal>
                <x-slot name="button">
                    <div class="flex px-3 py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-7 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                        Student
                </x-slot>


                <x-slot name="heading">
                    Add Student Information
                </x-slot>
                <x-slot name="content">
                    <form id="studentForm"action="{{ route('addStudent') }}" x-ref ="studentForm" method="POST"
                        enctype="multipart/form-data" class="flex items-center">
                        @csrf
                        <div class="basis-3/4 justify-start">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">
                                        RFID
                                    </label>
                                    <input type="text" placeholder="Scan RFID" name="s_rfid" id="s_rfid" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Student ID:</label>
                                    <input type="text" placeholder="Enter Student ID (Ex. 2023-00069)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        name="s_studentID" id="s_studentID">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">First Name:</label>
                                <input type="text" placeholder="Enter Firstname" name="s_fname" id="s_fname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">Last Name:</label>
                                <input type="text" placeholder="Enter Lastname" name="s_lname" id="s_lname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Middle Name</label>
                                    <input type="text" placeholder="Enter Middlename" name="s_mname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        id="s_mname">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Suffix</label>
                                    <input type="text" placeholder="Enter Suffix" name="s_suffix" id="s_suffix" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Program</label>
                                    <select name="s_program" id="s_program" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected value="">Select Program</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSIS">BSIS</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Year Level</label>
                                    <select name="s_lvl" id="s_lvl" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected value="">Select Year Level</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Set</label>
                                    <select name="s_set" id="s_set" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected value="">Select Set</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                        <option value="G">G</option>
                                        <option value="H">H</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div x-data="{ image: '{{ asset('images/icons/default-image.svg') }}' }" class="basis-1/4 flex flex-col mt-5 items-center gap-5">
                            <img id="uploadImage" class="max-w-1/2 w-100" :src="image" alt="">
                            <input id="uploadFile" type="file" name="s_image" x-ref="imageFile"
                                x-on:change="image = URL.createObjectURL($refs.imageFile.files[0])" hidden>
                            <button x-on:click="$refs.imageFile.click()" type="button"
                                class="bg-green-600 rounded-xl text-white px-3 py-2 text-xl">
                                Upload Image
                            </button>
                        </div>
                    </form>
                </x-slot>
                <x-slot name="footer">
                    <button onclick="testStudentForm()" class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Test Form </button>
                    <button x-on:click="$refs.studentForm.submit()"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                </x-slot>
            </x-new-modal>

        </div>
    </div>


    <div class="mt-4 bg-gray-100 shadow-lg p-5 rounded-md">
        <div class="flex justify-between">
            <h3 class="text-3xl text-gray-950 font-extrabold mb-3">
                Attendance Record
            </h3>

            {{-- full-screen-btn --}}
            <button id="fullscreenToggle" class="bg-lime-600 text-white px-4 py-2 rounded-md mb-2">
                <svg height="15px" width="15px" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 512 512"  xml:space="preserve">
                <style type="text/css">
                    .st0{fill:#000000;}
                </style>
                <g>
                    <polygon class="st0" points="345.495,0 394.507,49.023 287.923,155.607 356.384,224.086 462.987,117.493 511.991,166.515
                        511.991,0 	"/>
                    <polygon class="st0" points="155.615,287.914 49.022,394.507 0.009,345.494 0.009,512 166.515,512 117.493,462.978
                        224.087,356.375 	"/>
                    <polygon class="st0" points="356.384,287.914 287.923,356.375 394.507,462.978 345.495,512 511.991,512 511.991,345.485
                        462.977,394.507 	"/>
                    <polygon class="st0" points="166.505,0 0.009,0 0.009,166.506 49.022,117.493 155.615,224.086 224.087,155.607 117.501,49.023 	"/>
                </g>
                </svg>
            </button>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg" >
            <table id="tableContainer" class="min-w-full w-full text-sm text-left rtl:text-right" >
                <thead class="text-lg font-semibold text-gray-100 uppercase bg-green-700">
                    <tr>
                        <td class="p-2">No.</td>
                        <td>Name</td>
                        <td>Program</td>
                        <td>Set</td>
                        <td>Year Level</td>
                        <td>Time In</td>
                        <td>Time Out</td>
                        <td>Event</td>
                        <td>Date</td>
                    </tr>
                </thead>
                <tbody class="border-3 shadow-lg text-gray-950 text-base hover:bg-gray-400 cursor-pointer">
                    @php
                        $index = 1;
                    @endphp
                    @foreach ($attendances as $attendance)
                        <tr class="font-semibold shadow-lg border-3">
                            <td class="py-5 px-3">{{ $index++ }}</td>
                            <td>{{ $attendance->s_fname . ' ' . $attendance->s_lname }}</td>
                            <td>{{ $attendance->s_program }}</td>
                            <td>{{ $attendance->s_set }}</td>
                            <td>{{ $attendance->s_lvl }}</td>
                            <td>{{ $attendance->attend_checkIn ? date('h:i A', strtotime($attendance->attend_checkIn)) : 'N/A' }}</td>
                            <td>{{ $attendance->attend_checkOut ? date('h:i A', strtotime($attendance->attend_checkOut)) : 'N/A' }}</td>
                            <td>{{ $attendance->event_name }}</td>
                            <td>{{ $attendance->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <script>
        document.getElementById("fullscreenToggle").addEventListener("click", function() {
            let tableContainer = document.getElementById("tableContainer");
            if (!document.fullscreenElement) {
                tableContainer.requestFullscreen().catch(err => {
                    style.backgroundColor = 'white'
                    console.error("Error attempting to enable full-screen mode:", err);
                });
            } else {
                document.exitFullscreen();
            }
        });

        // FOR MODAL EVENT WHOLE DAY
const afternoon = document.querySelector("#afternoon_attendance");
document.querySelector("#wholeDay").addEventListener("change", function () {
    afternoon.classList.toggle("hidden");
});
    </script>

</x-app-layout>

{{-- Removed the script file, transferred them into dashboard.js --}}
