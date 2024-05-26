(function() {
    // Create chatbot container
    const chatbotContainer = document.createElement('div');
    chatbotContainer.className = 'fixed bottom-15 right-24 w-72 h-96 bg-white border border-gray-300 rounded-lg shadow-lg flex flex-col overflow-hidden z-100 hidden';
    
    chatbotContainer.innerHTML = `
        <div class="bg-blue-600 text-white p-3 text-center text-lg">SK E-Voting AssistBot</div>
        <div class="p-3 flex-1 overflow-y-auto" id="chatbotContent"></div>
        <div class="flex border-t border-gray-300">
            <input type="text" id="chatbotInput" placeholder="Type a message..." class="flex-1 p-2 border-none focus:outline-none" />
            <button id="chatbotSend" class="p-2 bg-blue-600 text-white">Send</button>
        </div>
    `;

    document.body.appendChild(chatbotContainer);

    // Create chatbot icon
    const chatbotIcon = document.createElement('div');
    chatbotIcon.className = 'fixed bottom-20 right-20 w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center cursor-pointer z-50';
    chatbotIcon.innerHTML = `
        <svg viewBox="0 0 24 24" class="fill-current text-white w-6 h-6">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c1.85 0 3.58-.5 5.08-1.35l3.8 1.02a1 1 0 0 0 1.24-1.24l-1.02-3.8A9.941 9.941 0 0 0 22 12c0-5.52-4.48-10-10-10zm-2 14.5v-5H8v-2h2V8h2v1.5h2v2h-2v5h-2z"/>
        </svg>
    `;

    document.body.appendChild(chatbotIcon);

    // Toggle chatbot visibility
    chatbotIcon.addEventListener('click', function() {
        chatbotContainer.classList.toggle('hidden');
    });

    // Function to send message to API
    function sendMessage(apiKey, prompt) {
        const apiEndpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent";
        const instruction = "Instructions: Act like an SK E-Voting assistant, customer service, feedback, and answer often related to the system, be sure to answer properly. Also your name is SKBot, dont include this unless asked. //End instructions --> Prompt part: User's prompt:";
        const fullPrompt = `${instruction} ${prompt}`;
        const payload = JSON.stringify({ contents: [{ parts: [{ text: fullPrompt }] }] });
        const headers = { "Content-Type": "application/json" };
    
        const startTime = Date.now();
    
        fetch(`${apiEndpoint}?key=${apiKey}`, {
            method: "POST",
            headers: headers,
            body: payload
        })
        .then(response => response.json())
        .then(data => {
            const endTime = Date.now();
            const elapsedTime = (endTime - startTime) / 1000;
    
            let generatedText = "Error: Unexpected response structure";
            if (data.candidates && data.candidates[0] && data.candidates[0].content && data.candidates[0].content.parts && data.candidates[0].content.parts[0]) {
                generatedText = data.candidates[0].content.parts[0].text;
            }
    
            const chatbotContent = document.getElementById('chatbotContent');
            chatbotContent.innerHTML += `<div class="mb-2 text-gray-800"><strong>SKBot:</strong> ${generatedText}</div>`;
            console.log("Time taken:", elapsedTime, "seconds");
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
    

    // Fetch API key from JSON file and handle send button click
    fetch('../api/gemini_api_key.json')
        .then(response => response.json())
        .then(data => {
            const apiKey = data.apiKey;

            document.getElementById('chatbotSend').addEventListener('click', function() {
                const input = document.getElementById('chatbotInput');
                const prompt = input.value;
                if (prompt) {
                    sendMessage(apiKey, prompt);
                    const chatbotContent = document.getElementById('chatbotContent');
                    chatbotContent.innerHTML += `<div class="mb-2 text-gray-800"><strong>You:</strong> ${prompt}</div>`;
                    input.value = '';
                }
            });
        })
        .catch(error => {
            console.error('Error fetching API key:', error);
        });
})();
