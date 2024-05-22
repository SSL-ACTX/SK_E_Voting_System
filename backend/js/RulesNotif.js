$(document).ready(function () {
    // Check if the URL contains #vote
    if (window.location.href.indexOf("#vote") !== -1) {
        // Check if the user has already voted
        $.ajax({
            url: './voteCheck.php',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.voted) {
                    // Display notification if already voted
                    showAlreadyVotedContainer();
                    console.log("Voter already voted. --> Showing an indicator...")
                } else {
                    // Display rules if not voted
                    console.log("Voter haven't voted yet. --> Showing rules...")
                    displayRules();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error checking vote status:', error);
            }
        });
    }
});

function showAlreadyVotedContainer() {
    var container; // Declare container variable outside the function scope
    var overlay; // Declare overlay variable outside the function scope

    // Define a function to create the container
    function createContainer() {
        // Create the overlay div for background blur and darkening
        overlay = document.createElement("div");
        overlay.className = "fixed inset-0 bg-black opacity-50 z-20";

        // Create the container div
        container = document.createElement("div");
        container.className = "fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-30";
        container.style.backgroundColor = "white";
        container.style.borderRadius = "12px"; // Added rounded corners for better aesthetics

        // Create the inner content
        var innerContent = document.createElement("div");
        innerContent.className = "p-8 max-w-md rounded-lg shadow-lg";
        innerContent.innerHTML = `
            <div class="text-center">
                <svg class="h-16 w-16 text-gray-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-800 mt-4">You've Already Voted</h2>
                <p class="text-gray-600 mt-2">Thanks for participating!</p>
            </div>
        `;
        
        // Append inner content to container
        container.appendChild(innerContent);
        
        // Append overlay and container to the body
        document.body.appendChild(overlay);
        document.body.appendChild(container);
    }

    // Define a function to check if the word "Submit Vote" exists on the page
    function checkSubmitVote() {
        if (document.body.innerText.includes('Submit Vote')) {
            if (!container) {
                createContainer(); // Create the container if it doesn't exist
            }
            container.style.display = 'block'; // Show the container
            overlay.style.display = 'block'; // Show the overlay
        } else {
            if (container) {
                container.remove(); // Remove the container if it exists
                overlay.remove(); // Remove the overlay if it exists
            }
        }
    }

    // Call the checking function initially
    checkSubmitVote();

    // Set interval to call the checking function every 2 seconds for demonstration
    var interval = setInterval(checkSubmitVote, 2000);
}