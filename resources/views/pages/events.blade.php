<x-app-layout>
    @vite(['resources/js/events.js'])

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


    <x-slot name="header">
        <div class="">
            <h2 class="font-semibold text-4xl text-gray-900 leading-tight">
                Events Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="mt-4" x-data="{ open: false }">
        <div x-show.important="open" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div x-on:click.outside="open = false" class="max-w-[1000px] bg-white p-6 rounded-lg shadow-lg">
                <div class="border-b-2 border-gray-300 mb-5">
                    <h1 class="text-2xl font-bold">
                        Edit Event
                    </h1>
                </div>
                <div class="mb-5 max-h-[400px] overflow-y-scroll">
                    <form x-ref="updateForm" action="{{ route('updateEvent') }}" method="POST" class="min-w-[500px]">
                        @csrf
                        @method('PATCH')
                        <input type="text" id="evn_id" name="id" hidden>
                        <div class="flex flex-col mb-3">
                            <label for="">Day or Event:</label>
                            <input type="text" placeholder="Enter Event Name" name="event_name" id="evn_name">
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="">Event Date:</label>
                            <input type="date" name="date" id="evn_date" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <p class="text-xl font-semibold text-gray-900">Check In:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                    <input type="time" name="checkIn_start" id="in_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                    <input type="time" name="checkIn_end" id="in_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                        </div>
                        <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                    <input type="time" name="checkOut_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" id="out_start" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                    <input type="time" name="checkOut_end"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" id="out_end" required />
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 items-center mb-3">
                            <input type="checkbox" id="isWholeDay" name="wholeDay">
                            <label for="">Whole Day?</label>
                        </div>
                        <div id="update_afternoon_attendance" class="hidden transition-all">
                            <p class="text-2xl font-semibold text-gray-900">Afternoon Attendance</p>
                            <p class="text-xl font-semibold text-gray-900">Check In:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                        <input type="time" id="afternoon_in_start" name="afternoon_checkIn_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                        <input type="time" id="afternoon_in_end" name="afternoon_checkIn_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                            <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                        <input type="time" id="afternoon_out_start" name="afternoon_checkOut_start"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="end-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                                        <input type="time" id="afternoon_out_end" name="afternoon_checkOut_end"
                                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                            min="09:00" max="18:00" value="00:00" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

                <div class="flex justify-end">
                    <button x-on:click="$refs.updateForm.submit()" type="submit"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                    <button x-on:click="open = false"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
                </div>
            </div>
        </div>


        <div class="flex justify-between items-center mb-3">
            <x-new-modal>
                <x-slot name="button"
                    class="bg-violet-600 hover:bg-violet-950 ease-linear transition-all duration-75 text-white rounded-xl px-2 text-[10px] flex items-center p-2 gap-1 w-5">
                    <div class="flex px-2 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-5 mt-1 m-1">
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
                            <label for="" class="mb-2">Day or Event:</label>
                            <input type="text" placeholder="Enter Event Name" name="event_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="">Event Date:</label>
                            <input type="date" name="date" class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </div>

                        <p class="text-xl font-semibold text-gray-900">Check In:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                    <input type="time" id="start-time" name="checkIn_start"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                        min="09:00" max="18:00" value="00:00" required />
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="end-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                        <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                        <div class="flex gap-5 mb-3">
                            <div class="w-full">
                                <label for="start-time"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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

                            {{-- AFTERNOON ATTENDANCE --}}

                        </div>
                        <div class="flex gap-4 items-center mb-3">
                            <input type="checkbox" id="wholeDay" name="wholeDay">
                            <label for="">Whole Day?</label>
                        </div>
                        <div id="create_afternoon_attendance" class="hidden transition-all">
                            <p class="text-2xl font-semibold text-gray-900">Afternoon Attendance</p>
                            <p class="text-xl font-semibold text-gray-900">Check In:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                            <p class="text-xl font-semibold text-gray-900">Check Out:</p>
                            <div class="flex gap-5 mb-3">
                                <div class="w-full">
                                    <label for="start-time"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
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
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
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
                        class="bg-green-400 text-white px-3 py-2 mx-4 hover:bg-green-600 rounded-md">
                        Save </button>
                </x-slot>
            </x-new-modal>
        </div>

        <div class="flex border-b">
            <button onclick="toggleTab('upcoming')" class="px-4 py-2 font-semibold bg-gray-300" id="upcomingTab">Upcoming</button>
            <button onclick="toggleTab('completed')" class="px-4 py-2 font-semibold bg-gray-300" id="completedTab">Completed</button>
        </div>

        <div id="upcoming" class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <h2 class="text-xl font-bold mt-4">Upcoming Events</h2>

            <table class="min-w-full w-full text-sm text-center rtl:text-right text-gray-900 font-semibold">
                <thead class="text-md font-semibold text-gray-100 uppercase bg-green-700">
                    <tr>
                        <th scope="col" class="py-5">Event Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time of Check In</th>
                        <th scope="col">Time of Check Out</th>
                        <th scope="col">Time of Check In - Afternoon</th>
                        <th scope="col">Time of Check Out - Afternoon</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->date }}</td>
                            <td>{{ date_format(date_create($event->checkIn_start), 'h:i A') . " - " . date_format(date_create($event->checkIn_end), 'h:i A') }}</td>
                            <td>{{ date_format(date_create($event->checkOut_start), 'h:i A') . " - " . date_format(date_create($event->checkOut_end), 'h:i A' )  }}</td>
                            <td>
                                @if ($event->afternoon_checkIn_start != null)
                                    {{ date_format(date_create($event->afternoon_checkIn_start), 'h:i A') . " - " . date_format(date_create($event->afternoon_checkIn_end), 'h:i A') }}
                                @else
                                        ---
                                @endif
                            </td>
                            <td>
                                @if ($event->afternoon_checkOut_start != null)
                                    {{ date_format(date_create($event->afternoon_checkOut_start), 'h:i A') . " - " . date_format(date_create($event->afternoon_checkOut_end), 'h:i A') }}
                            @else
                                    ---
                            @endif
                        </td>
                            <td class="flex gap-3 py-3">
                                <x-edit-button x-on:click="open = true" onclick="editEvent({{ $event }})">
                                    {{-- Edit Button --}}
                                </x-edit-button>
                                <x-delete-button onclick="deleteEvent({{ $event }})">
                                    {{-- Delete Button --}}
                                </x-delete-button>

                                {{-- Add Complete Event Button --}}
                                <form action="{{ route('events.complete', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Complete Event
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div id="completed" class="tab-content hidden">
            <h2 class="text-xl font-bold mt-4">Completed Events</h2>
            <table class="min-w-full w-full text-sm text-center rtl:text-right text-gray-900 font-semibold">
                <thead class="text-md font-semibold text-gray-100 uppercase bg-green-700">
                    <tr>
                        <th scope="col" class="py-5">Event Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time of Check In</th>
                        <th scope="col">Time of Check Out</th>
                        <th scope="col">Time of Check In - Afternoon</th>
                        <th scope="col">Time of Check Out - Afternoon</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completed as $event)
                        <tr>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->date }}</td>
                            <td>{{ date_format(date_create($event->checkIn_start), 'h:i A') . " - " . date_format(date_create($event->checkIn_end), 'h:i A') }}</td>
                            <td>{{ date_format(date_create($event->checkOut_start), 'h:i A') . " - " . date_format(date_create($event->checkOut_end), 'h:i A' )  }}</td>
                            <td>
                                @if ($event->afternoon_checkIn_start != null)
                                    {{ date_format(date_create($event->afternoon_checkIn_start), 'h:i A') . " - " . date_format(date_create($event->afternoon_checkIn_end), 'h:i A') }}
                                @else
                                        ---
                                @endif
                            </td>
                            <td>
                                @if ($event->afternoon_checkOut_start != null)
                                    {{ date_format(date_create($event->afternoon_checkOut_start), 'h:i A') . " - " . date_format(date_create($event->afternoon_checkOut_end), 'h:i A') }}
                            @else
                                    ---
                            @endif
                        </td>
                            <td class="flex gap-3 py-3">
                                <x-edit-button x-on:click="open = true" onclick="editEvent({{ $event }})">
                                    {{-- Edit Button --}}
                                </x-edit-button>
                                <x-delete-button onclick="deleteEvent({{ $event }})">
                                    {{-- Delete Button --}}
                                </x-delete-button>

                                {{-- Add Complete Event Button --}}
                                <form action="{{ route('events.complete', $event->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Complete Event
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

{{-- MODAL FOR EXPORTING EVENTS  --}}

    <form method="POST" id="deleteForm" action="{{ route('deleteEvent') }}" hidden>
        @csrf
        @method('DELETE')
        <input type="text" name="id" id="s_id" hidden>
    </form>
</x-app-layout>
