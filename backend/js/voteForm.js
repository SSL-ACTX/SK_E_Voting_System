document.addEventListener('DOMContentLoaded', function () {
    function checkVotingStatus() {
        // Check if the URL contains #home
        if (window.location.href.indexOf("#vote") !== -1) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../backend/voteStatus.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var status = parseInt(xhr.responseText);
                        if (status === 1) {
                            // Voting is open, load positions
                            console.log("Voting is open --> Displaying voting container...")
                            loadPositions();
                        } else {
                            // Voting is closed
                            console.log("Voting is closed")
                            document.getElementById('content').innerHTML = '<div class="flex items-center justify-center h-full"><div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">Voting is closed.</div></div>';
                        }
                    } else {
                        console.error('Error checking voting status');
                    }
                }
            };
            xhr.send();
        }
    }

    function loadPositions() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../backend/loadPositions.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('positions').innerHTML = xhr.responseText;
                } else {
                    console.error('Error loading positions');
                }
            }
        };
        xhr.send();
    }

    // Initial check voting status
    checkVotingStatus();

    // Submit form data using AJAX
    document.getElementById('voteForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../backend/voteSubmit.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // Display success message or error
                    loadPositions(); // Reload positions after submitting vote
                    window.location.href = "./voter_dashboard.php";
                } else {
                    console.error('Error submitting vote');
                }
            }
        };
        xhr.send(formData);
    });
});

// If the content is loaded dynamically, you need to reinitialize the scripts
function initializeVoteScript() {
    var votingFormLoaded = false; // Flag to track if the voting form is loaded

    function checkVotingStatus() {
        if (window.location.href.indexOf("#vote") !== -1) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../backend/voteStatus.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var status = parseInt(xhr.responseText);
                        if (status === 1) {
                            // Voting is open, load positions if the voting form is not loaded
                            console.log("Voting is open --> Displaying voting container...");
                            if (!votingFormLoaded) {
                                loadPositions();
                            }
                            // Clear the notification
                            document.getElementById('notif').innerHTML = '';
                        } else {
                            // Voting is closed
                            document.getElementById('notif').innerHTML = `
                            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-30">
                            <div class="p-8 bg-white rounded-lg shadow-lg">
                            <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-16 w-16 text-gray-600 mx-auto">
                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                            <h2 class="text-xl font-bold text-gray-800 mt-4">Voting is closed</h2>
                            <p class="text-gray-600 mt-2">Thank you for your interest!</p>
                            </div>
                            </div>
                            </div>`;
                        }
                    } else {
                        console.error('Error checking voting status');
                    }
                }
            };
            xhr.send();
        }
    }

    // Run the function immediately and every 5 seconds
    checkVotingStatus();
    setInterval(checkVotingStatus, 5000);

    function loadPositions() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../backend/loadPositions.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    document.getElementById('positions').innerHTML = xhr.responseText;
                    votingFormLoaded = true; // Update the flag to indicate that the voting form is loaded
                } else {
                    console.error('Error loading positions');
                }
            }
        };
        xhr.send();
    }

    checkVotingStatus();

    // Submit form data using AJAX
    document.getElementById('voteForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../backend/voteSubmit.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // Display success message or error
                    loadPositions(); // Reload positions after submitting vote
                    window.location.href = "./voter_dashboard.php";
                } else {
                    console.error('Error submitting vote');
                }
            }
        };
        xhr.send(formData);
    });
}

// Call this function after dynamically loading the content
initializeVoteScript();
