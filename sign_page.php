<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Diary - Login & Registration</title>
    <link rel="stylesheet" href="sign_page.css">
</head>
<body>
    
    
    <form action="" method="POST" class="register">
	
        <div class="main">
		<h1>NOTES KEEPING</h1>
    
            <div class="form" style="border:2px solid black; margin-left:-450px;">
                <h2>Register</h2>
                <label for="username" style="color:white;">Username:</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username"><br>
                <label for="password" style="color:white;">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password"><br>
                <input type="submit" name="register" value="Register" class="btnn">
				<br><br><p style="color:white;">already register <a href="login_page" style="color:green;">login</p>
			<br><br>
			<?php
    session_start();

    $con = new mysqli("localhost", "root", "", "trisha");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            echo "<span style='color:red;'>Username and Password are required.</span>";
        } else {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $con->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $passwordHash);

            if ($stmt->execute()) {
                echo "User registered successfully!";
            } else {
                echo "<span style='color:red;'>Error: username you want is already taken  </span>" ;
            }

            $stmt->close();
        }
    }

    $con->close();
    ?>
			
			
			</div>
        </div>
		
		
		
		
    </form>

    
</body>
</html>
