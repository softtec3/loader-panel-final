<?php
require_once("./php/db_connect.php");
require_once("./php/login_cre.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body style="height: 100vh; display:flex; align-items:center; justify-content: center">

    <form class="defaultForm" action="" method="post" style="max-width: 400px;">
        <h2>Login</h2>
        <div class="formElement">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id" required />
        </div>
        <div class="formElement">
            <label for="user_password">Password</label>
            <input type="password" name="user_password" required />
        </div>


        <div class="formElement">
            <button type="submit">Login</button>
        </div>
    </form>

</body>

</html>