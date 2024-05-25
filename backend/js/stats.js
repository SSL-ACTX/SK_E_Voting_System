document.addEventListener('DOMContentLoaded', function() {
    fetch('./fetchUsers.php')
        .then(response => response.json())
        .then(data => {
            const userCount = data.user_count;
            document.getElementById('user-count').innerText = userCount;
        })
        .catch(error => console.error('Error fetching user count:', error));
});
