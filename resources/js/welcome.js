// THIS JAVASCRIPT FILE IS SOLELY FOR THE welcome.blade.php only
// FOR THE CARDS TO BE GENERATED DYNAMICALLY

const cardsData = [
  {
      title: "Radio Frequency Integration Attendance System",
      description: "Utilizing the power of Radio Frequency to enhance the efficiency, security, accuracy of managing attendances.",
      link: ""
  },
  {
      title: "Seamless and Effortless Attendance Management",
      description: "Faster attendance management services. Implements Mass-Registration of students to reduce hassle of operations.",
      link: "#"
  },
  {
      title: "Modern Technology Stack Utilization",
      description: "Built from the foundation of Laravel 11 as its Full-Stack framework, enhanced by Alpine, ES6, Php.",
      link: "#"
  },
  
];

// Function to generate a card
function createCard(card) {
  return `
        <div class="card relative max-w-sm bg-pink-500 border-4 border-black shadow-[8px_8px_0px_#000] translate-x-[-6px] translate-y-[-6px] transition-all duration-300">
            <div class="head font-bold text-sm bg-gray-500 py-2 px-4">Window</div>
            <div class="content p-5">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-black">
                    ${card.title}
                </h5>
                <p class="mb-3 font-normal text-justify text-black">
                    ${card.description}
                </p>
                <a href="${card.link}" class="button inline-flex items-center px-3 py-2 text-sm font-medium text-center text-black bg-green-400 border-4 border-black shadow-[3px_3px_0px_#000] transition-all duration-300 hover:translate-x-1.5 hover:translate-y-1.5 hover:shadow-[1.5px_1.5px_0px_#000] hover:bg-blue-400 active:translate-x-3 active:translate-y-3 active:shadow-none">
                    Read more
                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </a>
            </div>
        </div>

  `;
}

// Select the container where you want to insert the cards
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("cardsContainer");

    if (!container) {
        console.error("Error: #cardsContainer not found!");
        return;
    }

    // Append cards
    cardsData.forEach(card => {
        container.insertAdjacentHTML("beforeend", createCard(card));
    });
});



//TEAMS DYNAMIC GENERATION===============================================================

const teamData = [
    {
        name: "Jasper Comeling",
        role: "Team Leader | Programmer",
        teamPic: imagePaths.jasper, 
    },
    {
        name: "Don Dominick",
        role: "Lead Full-Stack Developer",
        teamPic: imagePaths.don, 
    },
    {
        name: "Panzerweb",
        role: "Full-Stack Developer",
        teamPic: imagePaths.panzer, 
    },
    {
        name: "Dejure",
        role: "Backend Developer",
        teamPic: imagePaths.dejure, 
    },
    {
        name: "Kyledev",
        role: "Full-Stack Developer",
        teamPic: imagePaths.kyle, 
    },
    {
        name: "Rhose",
        role: "Graphic Designer | UI/UX Designer",
        teamPic: imagePaths.rhose, 
    },

    
];

  function teamCard(team) {
    return `
            <li>
                <div class="flex items-center gap-x-6">
                <img class="size-16 rounded-full" src="${team.teamPic}" alt="">
                <div>
                    <h3 class="text-base/7 font-semibold tracking-tight text-gray-900">${team.name}</h3>
                    <p class="text-sm/6 font-semibold text-indigo-600">${team.role}</p>
                </div>
                </div>
            </li>
    `;
}

  // Select the container where you want to insert the cards
const teamContainer = document.getElementById("teamContainer");

// Loop through the data and insert cards into the container
teamData.forEach(team => {
    teamContainer.innerHTML += teamCard(team);
});
