window.editEvent = editEvent;
window.deleteEvent = deleteEvent;
window.toggleTab = toggleTab;
function editEvent(data) {
    console.log("editing event");
    console.log(data);
    document.getElementById("evn_name").value = data.event_name;
    document.getElementById("evn_id").value = data.id;
    document.getElementById("evn_date").value = data.date;
    document.getElementById("in_start").value = data.checkIn_start;
    document.getElementById("in_end").value = data.checkIn_end;
    document.getElementById("out_start").value = data.checkOut_start;
    document.getElementById("out_end").value = data.checkOut_end;
    document.getElementById("afternoon_out_end").value =
        data.afternoon_checkOut_end;
    document.getElementById("afternoon_out_start").value =
        data.afternoon_checkOut_start;
    document.getElementById("afternoon_in_start").value =
        data.afternoon_checkIn_start;
    document.getElementById("afternoon_in_end").value =
        data.afternoon_checkIn_end;
    if (data.isWholeDay == "true") {
        document.getElementById("isWholeDay").checked = true;
        update_afternoon.classList.remove("hidden");
    } else {
        document.getElementById("isWholeDay").checked = false;
        update_afternoon.classList.add("hidden");
    }

    // document.getElementById('date').value = data.date;
}

function deleteEvent(data) {
    console.log("deleting event");
    console.log(data);
    document.getElementById("s_id").value = data.id;
    document.getElementById("deleteForm").submit();
}
// FOR MODAL EVENT WHOLE DAY
const update_afternoon = document.querySelector("#update_afternoon_attendance");
const create_afternoon = document.querySelector("#create_afternoon_attendance");

document.querySelector("#wholeDay").addEventListener("change", function () {
    create_afternoon.classList.toggle("hidden");
});

document.querySelector("#isWholeDay").addEventListener("change", function () {
    update_afternoon.classList.toggle("hidden");
});

function toggleTab(tab) {
    document.getElementById("upcoming").classList.add("hidden");
    document.getElementById("completed").classList.add("hidden");
    document.getElementById(tab).classList.remove("hidden");

    document.getElementById("upcomingTab").classList.remove("bg-gray-400");
    document.getElementById("completedTab").classList.remove("bg-gray-400");
    document.getElementById(tab + "Tab").classList.add("bg-gray-400");
}

toggleTab("upcoming");
