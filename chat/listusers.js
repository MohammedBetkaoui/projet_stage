document.getElementById('list_users_link').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    var userList = document.getElementById('user_list');

    // Toggle visibility: If shown, hide it; if hidden, fetch users
    if (userList.style.display === 'none' || userList.innerHTML === '') {
        userList.style.display = 'block'; // Show the user list

        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/chat/fetch_users.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                userList.innerHTML = xhr.responseText;
                
            } else {
                userList.innerHTML = '<p>Error loading users.</p>';
            }
        };

        xhr.send(); // Send request
    } else {
        userList.style.display = 'none'; // Hide the user list on second click
    }
});
