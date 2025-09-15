<!-- index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Live Username Check + AJAX Fetch</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>User List</h2>
<table border="1" id="userTable">
    <tr><th>ID</th><th>Username</th><th>Email</th></tr>
</table>

<h3>Check Username Availability</h3>
<input type="text" id="username" placeholder="Enter username">
<span id="availability"></span>

<script>
$(document).ready(function() {
    // Fetch and display users
    $.ajax({
        url: 'fetch_users.php',
        method: 'GET',
        success: function(response) {
            let users = JSON.parse(response);
            users.forEach(function(user) {
                $('#userTable').append(`<tr><td>${user.id}</td><td>${user.username}</td><td>${user.email}</td></tr>`);
            });
        }
    });

    // Live username check
    $('#username').on('keyup', function() {
        let username = $(this).val().trim();
        if (username.length > 0) {
            $.ajax({
                url: 'check_username.php',
                method: 'POST',
                data: { username: username },
                success: function(response) {
                    if (response === 'taken') {
                        $('#availability').text('Username is taken').css('color', 'red');
                    } else {
                        $('#availability').text('Username is available').css('color', 'green');
                    }
                }
            });
        } else {
            $('#availability').text('');
        }
    });
});
</script>

</body>
</html>
