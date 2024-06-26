document.addEventListener('DOMContentLoaded', function() {
    const teamMembers = [
        {
            name: "Jameel Tutungan",
            role: "Fullstack Developer (?)",
            img: "https://rscv-main.vercel.app/assets/me.2f4e1c72.jpg",
            socialLinks: [
                { type: "email", href: "#" },
                { type: "twitter", href: "#" },
                { type: "github", href: "#" }
            ]
        },
        {
            name: "Glennbrix Diolazo",
            role: "Group Leader / Project Manager / System Analyst",
            img: "https://i.ibb.co/BqrFWCS/user-icon-trendy-flat-style-600nw-1697898655.jpg",
            socialLinks: [{ type: "email", href: "#" }]
        },
        {
            name: "Jerico Evangelista",
            role: "Project Coordinator / UI/UX Designer",
            img: "https://i.ibb.co/BqrFWCS/user-icon-trendy-flat-style-600nw-1697898655.jpg",
            socialLinks: [{ type: "email", href: "#" }]
        },
        {
            name: "Eugene Dianito",
            role: "System Analyst",
            img: "https://i.ibb.co/BqrFWCS/user-icon-trendy-flat-style-600nw-1697898655.jpg",
            socialLinks: [{ type: "email", href: "#" }]
        },
        {
            name: "Jhob Aguilar",
            role: "System Analyst",
            img: "https://i.ibb.co/BqrFWCS/user-icon-trendy-flat-style-600nw-1697898655.jpg",
            socialLinks: [{ type: "email", href: "#" }]
        },
        {
            name: "Angelo Torreon",
            role: "System Analyst",
            img: "https://i.ibb.co/BqrFWCS/user-icon-trendy-flat-style-600nw-1697898655.jpg",
            socialLinks: [{ type: "email", href: "#" }]
        },
        {
            name: "Camille Mendoza",
            role: "Group Member",
            img: "https://i.ibb.co/BqrFWCS/user-icon-trendy-flat-style-600nw-1697898655.jpg",
            socialLinks: [{ type: "email", href: "#" }]
        }
    ];

    const desiredOrder = ["Jameel Tutungan", "Glennbrix Diolazo", "Jerico Evangelista", "Eugene Dianito", "Jhob Aguilar", "Angelo Torreon", "Camille Mendoza"];

    function reorderTeamMembers() {
        const teamMembersContainer = document.getElementById("team-members");
        const sortedMembers = teamMembers.sort((a, b) => {
            return desiredOrder.indexOf(a.name) - desiredOrder.indexOf(b.name);
        });

        sortedMembers.forEach(member => {
            let socialLinksHTML = '';
            member.socialLinks.forEach(link => {
                let svgContent = '';
                switch (link.type) {
                    case "email":
                        svgContent = `<path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>`;
                        break;
                    case "twitter":
                        svgContent = `<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>`;
                        break;
                    case "github":
                        svgContent = `<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-4.466 19.59c-.405.078-.534-.171-.534-.384v-2.195c0-.747-.262-1.233-.55-1.481 1.782-.198 3.654-.875 3.654-3.947 0-.874-.312-1.588-.823-2.147.082-.202.356-1.016-.079-2.117 0 0-.671-.215-2.198.82-.64-.18-1.324-.267-2.004-.271-.68.003-1.364.091-2.003.269-1.528-1.035-2.2-.82-2.2-.82-.434 1.102-.16 1.915-.077 2.118-.512.56-.824 1.273-.824 2.147 0 3.064 1.867 3.751 3.645 3.954-.229.2-.436.552-.508 1.07-.457.204-1.614.557-2.328-.666 0 0-.423-.768-1.227-.825 0 0-.78-.01-.055.487 0 0 .525.246.889 1.17 0 0 .463 1.428 2.688.944v1.489c0 .211-.129.459-.528.385-3.18-1.057-5.472-4.056-5.472-7.59 0-4.419 3.582-8 8-8s8 3.581 8 8c0 3.533-2.289 6.531-5.466 7.59z"></path>`;
                        break;
                }
                socialLinksHTML += `
                    <a rel="noopener noreferrer" href="${link.href}" title="${link.type}" class="dark:text-gray-50 hover:dark:text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" `; 

                        if (link.type === 'twitter') {
                            socialLinksHTML += `viewBox="0 0 24 24" `; // Correct viewBox for Twitter
                        } else if (link.type === 'github') {
                            socialLinksHTML += `viewBox="0 0 24 24" `; // Correct viewBox for GitHub
                        } else {
                            socialLinksHTML += `viewBox="0 0 20 20" `; // Default (Email) viewBox
                        }

                        socialLinksHTML += `fill="currentColor"
                                class="w-5 h-5">
                            ${svgContent}
                        </svg>
                    </a>
                `;
            });

            const memberDiv = `
                <div class="flex flex-col justify-center w-full px-8 mx-6 my-12 text-center rounded-md md:w-96 lg:w-80 xl:w-64 dark:bg-gray-800 dark:text-gray-100">
                    <img alt="" class="self-center flex-shrink-0 w-24 h-24 -mt-12 bg-center bg-cover rounded-full dark:bg-gray-500" src="${member.img}">
                    <div class="flex-1 my-4">
                        <p class="text-xl font-semibold leading-snug">${member.name}</p>
                        <p>${member.role}</p>
                    </div>
                    <div class="flex items-center justify-center p-3 space-x-3 border-t-2">
                        ${socialLinksHTML}
                    </div>
                </div>
            `;
            teamMembersContainer.innerHTML += memberDiv;
        });
    }

    reorderTeamMembers();
});
