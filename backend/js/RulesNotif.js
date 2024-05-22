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

function displayRules() {
    var closeCounter = 0; // Counter to keep track of close button clicks
    var intervalId; // Variable to store the interval ID

    // Function to check if "voting is closed" is detected in the document
    function checkVotingClosed() {
        if (document.body.textContent.includes("Voting is closed.")) {
            console.log("Found 'voting closed'");
            if (intervalId) {
                clearInterval(intervalId); // Clear the interval if the text is found
            }
            return true;
        }
        return false;
    }

    // Run the initial check
    if (checkVotingClosed()) {
        return; // Exit the function if "voting is closed" is found
    }

    // Set up continuous checking every 5 seconds (adjust interval as needed)
    intervalId = setInterval(function() {
        if (checkVotingClosed()) {
            if (floatingContainer) {
                floatingContainer.remove(); // Remove the container if it's being displayed
            }
        }
    }, 3000); // Check every 5 seconds

    // Create the floating container element
    var floatingContainer = document.createElement("div");
    floatingContainer.className = "fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10";

    // Create the inner content of the floating container
    var innerContent = document.createElement("div");
    innerContent.className = "bg-white p-8 rounded-lg max-w-md";

    // Add the rules and regulations content
    innerContent.innerHTML = `
        <h2 class="text-xl font-semibold mb-4">Rules and Regulations for Voting</h2>
        <ol class="list-decimal list-inside text-gray-700 mb-4">
            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
            <li>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
            <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
        </ol>
        <button id="agreeButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 disabled:opacity-50">Agree and Vote</button>
        <button id="closeButton" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Close</button>
    `;

    // Append the inner content to the floating container
    floatingContainer.appendChild(innerContent);

    // Append the floating container to the body
    document.body.appendChild(floatingContainer);

    // Add event listener to close the floating container when "Agree and Vote" button is clicked
    document.getElementById("agreeButton").addEventListener("click", function() {
        floatingContainer.remove();
        if (intervalId) {
            clearInterval(intervalId); // Clear the interval when the container is closed
        }
    });

    // Add event listener to handle "Close" button click
    document.getElementById("closeButton").addEventListener("click", function() {
        closeCounter++; // Increment close button click counter
        if (closeCounter >= 5) { // If close button is clicked 5 times
            // Create notification for redirection
            var notification = document.createElement("div");
            notification.className = "fixed top-8 right-8 p-4 max-w-sm bg-white border border-gray-600 rounded-lg rounded-xl shadow-lg flex items-center space-x-4 z-60";
            notification.innerHTML = `
                <div class="shrink-0">
                    <img class="h-16 w-16" src="https://www.svgrepo.com/show/326725/notifications-circle-outline.svg" alt="Notification Icon">
                </div>
                <div>
                    <div class="text-xl font-medium text-black">Redirecting to logout...</div>
                </div>
            `;
            document.body.appendChild(notification);

            // Redirect to logout after 2 seconds
            setTimeout(function() {
                window.location.href = "./logout.php";
            }, 2000);
        }
    });
}
