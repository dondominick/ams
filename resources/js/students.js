import Alpine from "alpinejs";
import axios from "axios";
//Added testing function
import { testStudentForm } from "./dashboard";
window.testStudentForm = testStudentForm;

const api = axios.create({
    headers: {
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
});

function updateStudent(data, image) {
    console.log(data);
    console.log(data.s_rfid);
    document.getElementById("s_RFID").value = data.s_rfid;
    document.getElementById("s_STUDENTID").value = data.s_studentID;
    document.getElementById("s_MNAME").value = data.s_mname;
    document.getElementById("s_FNAME").value = data.s_fname;
    document.getElementById("s_LNAME").value = data.s_lname;
    document.getElementById("s_SUFFIX").value = data.s_suffix;
    document.getElementById("s_PROGRAM").value = data.s_program;
    document.getElementById("s_STATUS").value = data.s_status;
    document.getElementById("s_LVL").value = data.s_lvl;
    document.getElementById("s_SET").value = data.s_set;
    document.getElementById("s_ID").value = data.id;
    document.getElementById("uploadImage").src = image;
    console.log(document.getElementById("uploadImage").src);
}
function deleteStudent(data) {
    console.log(data);
    Swal.fire({
        title: "Chotto Matte Kudasai!!!",
        html: `
<strong>Are you sure to delete this student's data?</strong>
`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log(data);
            document.getElementById("s_id").value = data.id;
            document.getElementById("deleteStudent").submit();
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "User Deleted Successfully",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
}

document.updateStudent = updateStudent;
document.deleteStudent = deleteStudent;
document.search = search;
document.getCategory = getCategory;
const baseUrl = window.location.href + "/category?";

const table_row = document.getElementsByClassName("table_row");
document.getElementById("searchForm").addEventListener("submit", (e) => {
    e.preventDefault();
    const uri = document.getElementById("search_uri").value;
    const value = document.getElementById("default-search").value;
    search(uri, value);
});

Array.from(table_row).forEach((element) => {
    element.addEventListener("click", (e) => {
        // element.classList.toggle('selected', 'bg-green-500', 'shadow-lg', 'shadow-green-800')
        element.classList.toggle("selected");
        element.classList.toggle("bg-green-400");
    });
});

document.addEventListener("dblclick", (e) => {
    const selected = document.querySelectorAll(".selected");
    Array.from(selected).forEach((element) => {
        element.classList.remove("bg-green-400", "selected");
    });
});

async function getCategory() {
    const program = document.querySelectorAll(
        '#search_program input[name="program"]:checked'
    );
    const lvl = document.querySelectorAll(
        '#search_lvl input[name="lvl"]:checked'
    );
    const set = document.querySelectorAll(
        '#search_set input[name="set"]:checked'
    );
    const status = document.querySelectorAll(
        '#search_status input[name="status"]:checked'
    );

    let uri = baseUrl;
    const program_data = Array.from(program).map((cb) => cb.value);
    const lvl_data = Array.from(lvl).map((cb) => cb.value);
    const set_data = Array.from(set).map((cb) => cb.value);
    const status_data = Array.from(status).map((cb) => cb.value);
    uri += "&&program=";
    program_data.forEach((element) => {
        uri += element + ",";
    });
    uri += "&&lvl=";

    lvl_data.forEach((element) => {
        uri += element + ",";
    });
    uri += "&&set=";

    set_data.forEach((element) => {
        uri += element + ",";
    });

    uri += "&&status=";
    status_data.forEach((element) => {
        uri += element + ",";
    });
    const data = await searchViaCategory(uri);
    // UPDATE TABLE
    const students = data.students;
    const table = document.getElementById("student_table_body");
    table.innerHTML = "";
    if (students) {
        students.forEach((e) => {
            table.innerHTML += ` <tr class="table_row" id="${e.id}">
    <td>${e.s_studentID}</td>
    <td>${e.s_fname}</td>
    <td>${e.s_lname}</td>
    <td>${e.s_mname}</td>
    <td>${e.s_suffix}</td>
    <td>${e.s_lvl}</td>
    <td>${e.s_set}</td>
    <td>${e.s_program}</td>
    <td>${e.s_status}</td>
    <td class="flex gap-3 py-3">
        <button
            class='text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2'
            x-on:click="open = true" onclick='updateStudent(${JSON.stringify(
                e
            )})'>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </button>
        <button
            class='text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2'
            onclick='deleteStudent(${JSON.stringify(e)})'>


            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </button>
    </td>
</tr>`;
        });
        document.getElementById("std_info_table").innerHTML = "";
        Array.from(table_row).forEach((element) => {
            element.addEventListener("click", (e) => {
                // element.classList.toggle('selected', 'bg-green-500', 'shadow-lg', 'shadow-green-800')
                element.classList.toggle("selected");
                element.classList.toggle("bg-green-400");
                displayActionBar();
            });
        });
    } else {
        document.getElementById("std_info_table").innerHTML = `
        <div class="flex justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
            </svg>
            <h3 class="text-center font-bold text-lg py-5">
                No Student Found
            </h3>
        </div>

`;
    }
}

// MULTIPLE EDIT AND DELETE AND SELECT

window.closeEditModal = closeEditModal;

function closeEditModal() {
    document.querySelector("#multipleEditModal").classList.add("hidden");
    document.querySelector("#multipleEditModal").classList.remove("flex");
}

window.selectAll = selectAll;
function selectAll() {
    const rows = Array.from(document.querySelectorAll(".table_row"));
    const selected_count = Array.from(
        document.querySelectorAll(".selected")
    ).length;

    if (selected_count != rows.length) {
        rows.forEach((e) => {
            e.classList.add("selected", "bg-green-400");
        });
        document.querySelector("#selectAllBtn").innerHTML = `
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                </svg>

                Deselect All
            </div>
            `;
        return;
    }
    rows.forEach((e) => {
        e.classList.remove("selected", "bg-green-400");
    });
    document.querySelector("#selectAllBtn").innerHTML = `
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                </svg>

                Select All
            </div>
            `;
}

window.editSelectedRows = editSelectedRows;
function editSelectedRows() {
    const selected = Array.from(document.querySelectorAll(".selected"));

    // DISPLAY MODAL
    document.querySelector("#multipleEditModal").classList.remove("hidden");
    document.querySelector("#multipleEditModal").classList.add("fixed");
    document.querySelector("#multipleEditModal").classList.add("flex"); //Added Flex

    // UPDATE MODAL
    const field = document.querySelector("#_selected_students_field");
    field.value = selected.map((cb) => cb.id);
}

window.deleteSelectedRows = deleteSelectedRows;
async function deleteSelectedRows() {
    // Added a confirmation Sweet Alert Pop up before Deleting
    Swal.fire({
        title: "Chotto Matte Kudasai!!!",
        html: `
    <strong>Are you sure to delete this student's data?</strong>
    `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            const field = document.querySelector("#_selected_students_delete");
            const selected = Array.from(
                document.querySelectorAll(".selected")
            ).map((e) => e.id);

            field.value = selected;

            document.querySelector("#_selected_delete_form").submit();
        }
    });
}

// AXIOS API
async function search(uri, data) {
    console.log("Search is Working");
    uri = uri + "?search=" + data;
    const response = await axios.get(uri, {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    });

    // UPDATE TABLE
    const students = response.data.students;
    const table = document.getElementById("student_table_body");

    table.innerHTML = "";
    if (students) {
        console.log(students);
        students.forEach((e) => {
            const data = JSON.stringify(e);
            table.innerHTML +=
                ` <tr class="table_row" id="${e.id}">
    <td>${e.s_studentID}</td>
    <td>${e.s_fname}</td>
    <td>${e.s_lname}</td>
    <td>${e.s_mname}</td>
    <td>${e.s_suffix}</td>
    <td>${e.s_lvl}</td>
    <td>${e.s_set}</td>
    <td>${e.s_program}</td>
    <td>${e.s_status}</td>
    <td class="flex gap-3 py-3">

        <button
            class='text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2'
            x-on:click="open = true" onclick='updateStudent( ` +
                data +
                `)'>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </button>
        <button
            class='text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2'
            onclick='deleteStudent(${JSON.stringify(e)})'>


            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </button>
    </td>
</tr>`;
            document.getElementById("std_info_table").style.display = "none"; //Line by Panzerweb: When search is empty, remove the span
        });
        Array.from(table_row).forEach((element) => {
            element.addEventListener("click", (e) => {
                // element.classList.toggle('selected', 'bg-green-500', 'shadow-lg', 'shadow-green-800')
                element.classList.toggle("selected");
                element.classList.toggle("bg-green-400");
                console.log(element.id);
            });
        });
    } else {
        //Code by Panzerweb: If search does not match, display text 'No Student Found'
        document.getElementById("std_info_table").style.display = "block";
        document.getElementById(
            "std_info_table"
        ).innerHTML = `<h3 class="text-center tracking-wide text-gray-500 text-xl">No Student Found</h3>`;
    }
}
async function searchViaCategory(uri) {
    try {
        const response = await axios.get(uri, {
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
        });
        return response.data;
    } catch (error) {
        console.error(error);
    }
}

// FOR MULTIPLE EDITS
const form = document.getElementById("multiEditForm");

function multiEdit() {
    return false;
}
