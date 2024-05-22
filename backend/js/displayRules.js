$(document).ready(function () {
    // Check if the URL contains #vote
    var hasVoted = window.location.href.indexOf("#vote") !== -1;

    // Check if the user has already voted
    if (hasVoted) {
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

    // Function to show the already voted container
    function showAlreadyVotedContainer() {
        var container = null;
        var overlay = null;

        // Define a function to create the container
        function createContainer() {
            overlay = document.createElement("div");
            overlay.className = "fixed inset-0 bg-black opacity-50 z-20";

            container = document.createElement("div");
            container.className = "fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-30 bg-white rounded-lg p-8 max-w-md shadow-lg";
            container.innerHTML = `
                <div class="text-center">
                    <svg class="h-16 w-16 text-gray-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-gray-800 mt-4">You've Already Voted</h2>
                    <p class="text-gray-600 mt-2">Thanks for participating!</p>
                </div>
            `;

            document.body.appendChild(overlay);
            document.body.appendChild(container);
        }

        // Show container and overlay only when user has voted
        createContainer();

        // Close the container and overlay when the URL doesn't contain #vote anymore
        $(window).on('hashchange', function () {
            if (window.location.href.indexOf("#vote") === -1) {
                if (container) {
                    container.remove();
                }
                if (overlay) {
                    overlay.remove();
                }
            }
        });
    }

    function displayRules() {
        var closeCounter = 0; // Counter to keep track of close button clicks
        var intervalId; // Variable to store the interval ID
        var floatingContainer; // Variable to store the floating container

        // Function to check the voting status from the server
        function checkVotingStatus() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "./voteStatus.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    if (xhr.responseText.trim() === "0" || xhr.responseText.trim() === "closed") {
                        console.log("Voting is closed <-- Closing Rules...");
                        if (intervalId) {
                            clearInterval(intervalId); // Clear the interval if the status is closed
                        }
                        if (floatingContainer) {
                            floatingContainer.remove(); // Remove the container if it's being displayed
                        }
                        return true;
                    }
                    return false;
                }
            };
            xhr.send();
        }

        // Run the initial check
        if (checkVotingStatus()) {
            return; // Exit the function if voting is closed
        }

        // Set up continuous checking every 5 seconds (adjust interval as needed)
        intervalId = setInterval(function () {
            checkVotingStatus();
        }, 5000); // Check every 5 seconds

        // Create the floating container element
        floatingContainer = document.createElement("div");
        floatingContainer.className = "fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10";

        // Create the inner content of the floating container
        var innerContent = document.createElement("div");
        innerContent.className = "bg-white p-8 rounded-lg max-w-md";

        // Add the rules and regulations content
        innerContent.innerHTML = `
            <h2 class="text-xl font-semibold mb-4">Rules and Regulations for Voting</h2>
            <ol class="list-decimal list-inside mb-4">
                <li>All voters must be registered and verified.</li>
                <li>Each voter is entitled to one confidential and anonymous vote per election.</li>
                <li>Voters must complete their voting within the specified time frame.</li>
                <li>Any form of vote tampering, coercion, or fraud is strictly prohibited.</li>
                <li>Voters should review their selections carefully before submission.</li>
                <li>Report technical issues or disputes to election officials promptly.</li>
                <li>No electronic devices are allowed during voting.</li>
                <li>All votes are securely stored and encrypted.</li>
                <li>Voters agree to abide by these rules and any guidelines set by the election committee.</li>
            </ol>
            <button id="agreeButton" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 disabled:opacity-50">Agree and Vote</button>
            <button id="closeButton" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Close</button>
        `;

        // Append the inner content to the floating container
        floatingContainer.appendChild(innerContent);

        // Append the floating container to the body
        document.body.appendChild(floatingContainer);

        // Add event listener to close the floating container when "Agree and Vote" button is clicked
        document.getElementById("agreeButton").addEventListener("click", function () {
            floatingContainer.remove();
            if (intervalId) {
                clearInterval(intervalId); // Clear the interval when the container is closed
            }
        });

        // Add event listener to handle "Close" button click
        document.getElementById("closeButton").addEventListener("click", function () {
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
                setTimeout(function () {
                    window.location.href = "./logout.php";
                }, 2000);
            }
        });

        // Close the container if URL hash doesn't contain #vote
        function closeContainerIfNotInVote() {
            if (window.location.hash !== '#vote') {
                if (floatingContainer) {
                    floatingContainer.remove();
                }
                clearInterval(intervalId); // Clear the interval
                console.log('Rules closed because not in #vote');
            }
        }

        // Check URL hash every second (adjust interval as needed)
        var intervalId = setInterval(closeContainerIfNotInVote, 3900);
    }

});
