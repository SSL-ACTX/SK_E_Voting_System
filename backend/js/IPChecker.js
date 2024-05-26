async function getIpGeoLocation() {
    try {
        const response = await fetch('https://api.ipgeolocation.io/ipgeo?apiKey=cb4ccda8af3546ca8d38c3f1521b1455');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching IP and geolocation:', error);
    }
}

async function saveUserInfo() {
    try {
        const ipGeoData = await getIpGeoLocation();

        const accountResponse = await fetch('./getSessionInfo.php');
        const accountData = await accountResponse.json();

        if (accountData.error) {
            console.error('Error fetching account info:', accountData.error);
            return;
        }

        const userInfo = {
            ipGeoData: ipGeoData,
            accountData: accountData
        };

        const blob = new Blob([JSON.stringify(userInfo, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = './json/user_info.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

    } catch (error) {
        console.error('Error saving user info:', error);
    }
}

document.addEventListener('DOMContentLoaded', saveUserInfo);
