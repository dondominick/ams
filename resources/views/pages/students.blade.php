<x-app-layout>
    @vite(['resources/js/students.js'])
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

    {{-- Session Error Handling from Import Controller --}}
    {{-- UPDATE: This error handling pop ups is not exclusive to the Import Controller anymore --}}
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
    {{-- Error popup modified by Panzerweb --}}
    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const errors = @json(session('error'));
                console.log(errors);
                let errorList = '<ul class="pl-5 text-sm text-red-700">';
                for (const [key, value] of Object.entries(errors.details)) {
                    errorList += `<li><strong>${key}:</strong> ${value}</li>`;
                }
                errorList += '</ul>';

                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    html:
                    `   <h2 class="text-lg font-semibold text-red-600">Something is wrong!</h2><br>
                        <div class="w-full max-w-md mx-auto">
                            <div class="">
                                <button onclick="toggleAccordion()" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                    View Details
                                </button>
                                <div id="errorDetails" class="hidden p-4 bg-red-100 border-t border-gray-300 rounded-lg">
                                        <div>
                                            <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0 9 9 0 0112.728 0zM12 8v4m0 4h.01" />
                                                </svg>
                                                Full Error Details
                                            </h3>
                                            <p class="text-sm text-red-600">
                                                The following information might help you identify and fix the issue:
                                            </p>
                                        </div>
                                        <div class="bg-gray-200 p-4 rounded-md border border-red-200">
                                            <p class="text-md font-medium text-red-800 mb-2">Error Message:</p>
                                            <p class="text-sm text-red-700 italic">
                                                The error shows that either a <b>Student RFID</b> or <b>Student ID</b> has been duplicated, or there are <b> empty fields </b>, please check carefully input details of inserted data.
                                            </p>
                                            <div class="bg-gray-100 p-4 rounded-md border border-red-200">
                                                <p class="text-sm font-medium text-red-800 mb-2">Details affected:</p>
                                                ${errorList}
                                            </div>

                                            <span class="text-sm"><strong>Full error message: </strong>${errors.message}</span>
                                        </div>

                                </div>
                            </div>
                        </div>`,
                    showConfirmButton: true,
                });
            });
        </script>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-4xl text-gray-900 leading-tight">
            Students Masterlist
        </h2>
    </x-slot>
    <div class="flex justify-between items-center">
        <div class="flex flex-col items-start">
            <form action="{{ route('importStudent') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center">
                    <label for="file"
                        class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                        Choose Excel File
                    </label>
                    <input type="file" name="file" id="file" class="hidden" onchange="selectFile(event)">
                    {{-- Choose Excel file and import it --}}
                    <button id="import-btn"
                        class="hidden focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        Import Data
                    </button>
                </div>
                <div class="preview mb-4 mt-2 p-1 border border-gray-300 rounded-lg bg-gray-100 shadow-md">
                    <p id="preview-name" class="text-gray-700 text-base font-semibold italic text-center">
                        No file currently selected
                    </p>
                </div>
            </form>
        </div>
    </div>
    {{-- Edit Student Information --}}
    <div x-data="{ open: false }"="mt-4">
        <div x-show.important="open" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div x-on:click.outside="open = false" class="max-w-[1000px] bg-white p-6 rounded-lg shadow-lg">
                <div class="border-b-2 border-green-500 mb-5">
                    <h1 class="text-2xl font-bold">
                        Edit Student Information
                    </h1>
                </div>
                <div class="mb-5">
                    <form id="updateForm" action="{{ route('updateStudent') }}" x-ref ="updateForm" method="POST"
                        enctype="multipart/form-data" class="flex items-center">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="id" id="s_ID" hidden>
                        <div class="basis-3/4 justify-start">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">
                                        RFID
                                    </label>
                                    <input type="text" placeholder="Scan RFID" name="s_rfid" id="s_RFID"
                                        value="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Student ID:</label>
                                    <input type="text" placeholder="Enter Student ID (Ex. 2023-00069)"
                                        name="s_studentID" id="s_STUDENTID" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">First Name:</label>
                                <input type="text" placeholder="Enter Firstname" name="s_fname" id="s_FNAME" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 mt-5 mx-7">
                                <label for="">Last Name:</label>
                                <input type="text" placeholder="Enter Lastname" name="s_lname" id="s_LNAME" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Middle Name</label>
                                    <input type="text" placeholder="Enter Middlename" name="s_mname"
                                        id="s_MNAME" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Suffix</label>
                                    <input type="text" placeholder="Enter Suffix" name="s_suffix" id="s_SUFFIX" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-8 mt-5 mx-7">

                                <div class="grid grid-cols-1">
                                    <label for="">Program</label>
                                    <select name="s_program" id="s_PROGRAM" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected value="">Select Program</option>
                                        <option value="BSIT">BSIT</option>
                                        <option value="BSIS">BSIS</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Year Level</label>
                                    <select name="s_lvl" id="s_LVL" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                        <option selected value="">Select Year Level</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-1">
                                    <label for="">Set</label>
                                    <select name="s_set" id="s_SET" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
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
                        <div class="basis-1/4  mt-5 items-center gap-5">
                            <div x-data="{ image: '{{ asset('images/icons/default-image.svg') }}' }" class="flex flex-col items-center gap-5">
                                <img id="uploadImage" class="max-w-1/2" :src="image" alt="">
                                <input id="uploadFile" type="file" name="s_image" x-ref="imageFile"
                                    x-on:change="image = URL.createObjectURL($refs.imageFile.files[0])" hidden>
                                <button x-on:click="$refs.imageFile.click()" type="button"
                                    class="bg-green-400 text-white px-3 py-2 text-xl">
                                    Upload Image
                                </button>
                            </div>
                            <div>
                                <span>
                                    Change Student Status
                                </span>
                                <select name="s_status" id="s_STATUS" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="ENROLLED">ENROLLED</option>
                                    <option value="DROPPED">DROPPED</option>
                                    <option value="GRADUATED">GRADUATED</option>
                                </select>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="flex justify-end">
                    <button x-on:click="$refs.updateForm.submit()"
                        class="bg-green-400 text-white px-3 py-2 rounded-md mx-4">
                        Save </button>
                    <button x-on:click="open = false"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
                </div>
            </div>
        </div>


        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <div class="flex justify-between shadow-lg rounded-md border border-gray-400 my-2 p-2">
                <div class="">
                    <button id="selectAllBtn" onclick="selectAll()" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                            </svg>

                            Select All
                        </div>
                    </button>
                </div>
                <div class="">
                    <button onclick="editSelectedRows()" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                            Edit
                        </div>
                    </button>
                    <button onclick="deleteSelectedRows()" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <div class="flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                            Delete
                        </div>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between py-1 w-full">
                <div class="flex items-center">
                    {{-- SEARCH FORM --}}
                    <div class="search mx-2">
                        {{-- Search Form --}}
                        <div class="flex items-center justify-start py-1 gap-3 w-full">
                            <form class="max-w-md w-full" id="searchForm" method="GET">
                                <input type="hidden" id="search_uri" value="{{ route('fetchStudent') }}" hidden>
                                <label for="default-search"
                                    class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                                <div class="">
                                    <div class="flex items-center">

                                        <div
                                            class="flex items-center bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            <svg class="w-4 h-4 text-gray-500 mx-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>
                                            <input type="search" id="default-search"
                                                class="outline-none w-full p-4 ps-2 text-sm text-gray-900 border-none  bg-gray-50 "
                                                placeholder="Student name, Student ID, ..." />

                                        </div>

                                        {{-- NOTE: Remove button if Live Search is implemented --}}
                                        <button type="submit"
                                            class="inline-flex items-center h-full py-4 px-3 ms-2 text-sm font-semibold text-gray-950 bg-yellow-400 rounded-lg hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                            <svg class="w-4 h-4 me-2" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                            </svg>Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    {{-- FILTER --}}
                    <button id="dropdownDefault" data-dropdown-toggle="dropdown"
                        class="text-gray-900 font-semibold bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg text-sm px-4 py-3 text-center inline-flex items-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 hidden w-auto p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                        <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                            Category
                        </h6>
                        <div class="flex justify-between gap-3">
                            {{-- List for Program --}}
                            <form id="search_program" class="" onchange="getCategory()">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold dark:text-gray-100">Program</label>
                                    @foreach (['BSIT', 'BSIS'] as $program)
                                        <li class="flex items-center">
                                            <input value="{{ $program }}" type="checkbox" name="program"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $program }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $program }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </form>

                            {{-- List for Year Levels --}}
                            <form id="search_lvl" onchange="getCategory()">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold dark:text-gray-100">Year Level</label>
                                    {{-- Key-value pair for this list, key is for the database field, value is the placeholder --}}
                                    @foreach (['1' => 'First Year', '2' => 'Second Year', '3' => 'Third Year', '4' => 'Fourth Year'] as $key => $value)
                                        <li class="flex items-center">
                                            <input value="{{ $key }}" type="checkbox" name="lvl"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $key }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $value }}
                                            </label>
                                        </li>
                                    @endforeach

                                </ul>
                            </form>
                            {{-- List for Sets --}}
                            <form id="search_set" onchange="getCategory()">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold dark:text-gray-100">Set</label>
                                    @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $set)
                                        <li class="flex items-center">
                                            <input value="{{ $set }}" type="checkbox" name="set"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $set }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $set }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </form>
                            {{-- List for Status if Enrolled, Dropped, or Graduated --}}
                            <form id="search_set" onchange="getCategory()">
                                <ul class="space-y-2 text-sm" aria-labelledby="dropdownDefault">
                                    <label for="" class="font-semibold dark:text-gray-100">Status</label>
                                    @foreach (['ENROLLED', 'DROPPED', 'GRADUATED'] as $status)
                                        <li class="flex items-center">
                                            <input value="{{ $status }}" type="checkbox" name="status"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />

                                            <label for="{{ $status }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $status }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </form>
                        </div>

                    </div>
                </div>

                {{-- Add Student --}}
                <div class="z-50">
                    <x-new-modal>
                        <x-slot name="button">
                            <div class="flex px-1 py-3 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                </svg>
                                <span class="text-md">
                                    Student
                                </span>
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
                                            <input type="text" placeholder="Enter Middlename" name="s_mname" id="s_mname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
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
                                        class="bg-green-400 text-white px-3 py-2 text-xl">
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

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg z-0">
                <table class="min-w-full w-full text-sm text-center rtl:text-right text-gray-900 font-semibold">

                    <thead class="text-lg font-semibold text-gray-100 uppercase bg-green-700">
                        <tr>
                            <th scope="col" class="py-5">No.</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Middle Name</th>
                            <th scope="col">Suffix</th>
                            <th scope="col">Year Level</th>

                            <th scope="col">Set</th>
                            <th scope="col">Program</th>

                            <th scope="col">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="student_table_body">
                        @isset($students)
                            @foreach ($students as $student)
                                {{-- Added tr elements for rows to fix UI --}}
                                <tr class="table_row" id="{{ $student->id }}">
                                    <td>{{ $student->s_studentID }}</td>
                                    <td>{{ $student->s_fname }}</td>
                                    <td>{{ $student->s_lname }}</td>
                                    <td>{{ $student->s_mname }}</td>
                                    <td>{{ $student->s_suffix }}</td>
                                    <td>{{ $student->s_lvl }}</td>
                                    <td>{{ $student->s_set }}</td>
                                    <td>{{ $student->s_program }}</td>
                                    <td>{{ $student->s_status }}</td>
                                    <td class="flex gap-3 py-3">
                                        <x-edit-button x-on:click="open = true" onclick="updateStudent({{ $student }})">
                                            {{-- Edit Button --}}
                                        </x-edit-button>
                                        <x-delete-button onclick="deleteStudent({{ $student }})">
                                            {{-- Delete button --}}
                                        </x-delete-button>

                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
                <span id="std_info_table" class="py-5">

                </span>
            </div>

            {{-- Pagination view added by Panzerweb--}}
            {{$students->onEachSide(5)->links()}}
        </div>

    </div>

    <form action="{{ route('deleteStudent') }}" id="deleteStudent" method="POST" hidden>
        @csrf
        @method('DELETE')
        <input type="text" name="id" id="s_id" hidden>
    </form>

    {{-- MODALS FOR MULTIPLE EDIT AND DELETE --}}
    {{-- EDIT MODAL --}}
    <div id="multipleEditModal" class="inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="max-w-[1000px] bg-white p-6 rounded-lg shadow-lg">
            <div class="border-b-2 border-gray-300 mb-5">
                <h1 class="text-2xl font-bold">
                    Edit Selected Students
                </h1>
            </div>
            <form

            class="mb-5" id="multiEditForm" action="{{route('multiStudentEdit')}}" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" name="students" id="_selected_students_field" hidden>
                <div class="my-3">
                    <label for="" class="font-semibold text-base">Set:</label>
                    <select name="s_set" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $set)
                            <option value="{{ $set }}">{{ $set }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="my-3">
                    <label for="" class="font-semibold text-base">Status</label>
                    <select name="s_status" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        @foreach (['ENROLLED', 'GRADUATED', 'DROPPED', 'TO BE UPDATED'] as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="my-3">
                    <label for="" class="font-semibold text-base">Program</label>
                    <select name="s_program" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSIS">BSIS</option>
                    </select>
                </div>
                <div class="my-3">
                    <label for="" class="font-semibold text-base">Year Level</label>
                    <select name="s_lvl" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Keep Current</option>
                        @foreach (['1' => 'First Year', '2' => 'Second Year', '3' => 'Third Year', '4' => 'Fourth Year'] as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="flex justify-evenly">
                <button onclick="document.getElementById('multiEditForm').submit()"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-800 transition-colors">
                    Apply Changes
                </button>
                <button onclick="closeEditModal()"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Close</button>
            </div>
        </div>
    </div>

    <form id="_selected_delete_form" method="POST" action="{{route('multiStudentDelete')}}">
        @csrf
        @method('DELETE')
        <input type="text" name="students" id="_selected_students_delete" hidden>
    </form>

    <script>
        //FILE UPLOADED PREVIEW AND DISPLAYING OF IMPORT BUTTON
        function selectFile(event) {
            let preview = document.getElementById("preview-name");
            let importBtn = document.getElementById("import-btn");

            // Check if a file is selected
            if (event.target.files.length > 0) {
                const fileName = event.target.files[0].name; // Get file name
                preview.innerHTML = fileName; // Display file name
                importBtn.classList.remove('hidden'); //Show the import button
                console.log('File Upload: ' + fileName);
            }
        }

        // Accordion for Error Details
        function toggleAccordion() {
            let details = document.getElementById("errorDetails");
            details.classList.toggle("hidden");
        }


    </script>

</x-app-layout>
