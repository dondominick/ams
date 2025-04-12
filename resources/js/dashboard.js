import axios from "axios";
import { startTime } from "./clockdate.js";
import { toggleDropdown, triggerDropdownOnLoad } from "./component.js";

console.log("Dashboard JS");

//Dashboard JS Functions
window.testStudentForm = testStudentForm;
window.startTime = startTime;
window.toggleDropdown = toggleDropdown;
triggerDropdownOnLoad();
// The code is from dashboard.blade.php sa script
// Pero gi transfer lng nako dire para uniformed, since testing function raman sad
export function testStudentForm() {
    // Added Randomality of Test Users
    const testUsers = [
        {
            s_rfid: "0002803473",
            s_studentID: "2023-00364",
            s_fname: "Romeo Selwyn",
            s_mname: "Molejon",
            s_lname: "Villar",
            s_program: "BSIT",
            s_lvl: "2",
            s_set: "B",
            s_suffix: "N/A",
        },
        {
            s_rfid: "0002193309",
            s_studentID: "2023-00069",
            s_fname: "Don Dominick",
            s_mname: "Banagaso",
            s_lname: "Enargan",
            s_program: "BSIT",
            s_lvl: "2",
            s_set: "H",
            s_suffix: "Jr.",
        },
        {
            s_rfid: "0002027286",
            s_studentID: "2023-00166",
            s_fname: "John Lyold",
            s_mname: "Castro",
            s_lname: "Lozada",
            s_program: "BSIT",
            s_lvl: "2",
            s_set: "A",
            s_suffix: "N/A",
        },
    ];

    const randomUsers = testUsers[Math.floor(Math.random() * testUsers.length)];

    document.getElementById("s_rfid").value = randomUsers.s_rfid;
    document.getElementById("s_studentID").value = randomUsers.s_studentID;
    document.getElementById("s_fname").value = randomUsers.s_fname;
    document.getElementById("s_mname").value = randomUsers.s_mname;
    document.getElementById("s_lname").value = randomUsers.s_lname;
    document.getElementById("s_program").value = randomUsers.s_program;
    document.getElementById("s_lvl").value = randomUsers.s_lvl;
    document.getElementById("s_set").value = randomUsers.s_set;
    document.getElementById("s_suffix").value = randomUsers.s_suffix;
}
