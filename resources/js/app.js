import "./bootstrap";
import Alpine from "alpinejs";
import Swal from "sweetalert2"; //Added Sweet Alert module
import "flowbite"; //I restored Flowbite kay wala nigana ang Dropdowns na gikan Flowbite
import { toggleDropdown, triggerDropdownOnLoad } from "./component";

console.log("Testing App------- Developer");

// AlpineJS
window.Alpine = Alpine;

Alpine.start();

// ComponentJS functions
window.toggleDropdown = toggleDropdown;
window.triggerDropdownOnLoad = triggerDropdownOnLoad;
//Clockdate function

//Sweet AlertJS
window.Swal = Swal;
