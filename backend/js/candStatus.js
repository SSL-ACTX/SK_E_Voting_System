$(document).ready(function () {
    // Function to check candidacy status
    function checkCandStatus() {
        // Check if the URL contains #candidacy
        if (window.location.href.indexOf("#candidacy") !== -1) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../backend/candStatus.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var status = parseInt(xhr.responseText);
                        if (status === 1) {
                            // Candidacy is open
                            console.log("Candidacy is open --> Displaying candidacy form...");
                            document.getElementById('ntf').innerHTML = ''; 
                            document.getElementById('candCont').classList.remove('hidden'); // Show the registration form
                        } else {
                            // Candidacy is closed
                            console.log("Candidacy is closed");
                            document.getElementById('ntf').innerHTML = `                            
                            <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-30">
                                <div class="p-8 bg-white rounded-lg shadow-lg">
                                    <div class="text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-16 w-16 text-gray-600 mx-auto">
                                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                        <h2 class="text-xl font-bold text-gray-800 mt-4">Candidacy is closed</h2>
                                        <p class="text-gray-600 mt-2">Thank you for your interest!</p>
                                    </div>
                                </div>
                            </div>`;
                            // Hide the registration form
                            document.getElementById('candCont').classList.add('hidden');
                        }
                    } else {
                        console.error('Error checking candidacy status');
                    }
                }
            };
            xhr.send();
        }
    }

    // Run the function immediately and every 5 seconds
    checkCandStatus();
    setInterval(checkCandStatus, 10000);
});
