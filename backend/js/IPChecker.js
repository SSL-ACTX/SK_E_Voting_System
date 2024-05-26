async function getIpGeoLocation() {
    try {
        const response = await fetch('https://api.ipgeolocation.io/ipgeo?apiKey=cb4ccda8af3546ca8d38c3f1521b1455');
        const data = await response.json();
        return data.ip; // Return only the IP address
    } catch (error) {
        console.error('Error fetching IP and geolocation:', error);
    }
}

async function saveIpAddress() {
    try {
        const ipAddress = await getIpGeoLocation();

        // Send IP address to server-side script using fetch
        const response = await fetch('./backend/IPCheck.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ip: ipAddress })
        });

        if (!response.ok) {
            throw new Error('Failed to save IP address');
        }

        console.log('IP Address saved successfully');
    } catch (error) {
        console.error('Error saving IP address:', error);
    }
}

document.addEventListener('DOMContentLoaded', saveIpAddress);
