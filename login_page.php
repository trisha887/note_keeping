<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login </title>
</head>
 <link rel="stylesheet" href="style.css">
 
<body  style="background-image: url('photo.jpg');">

<div class="main">
		<br><br><br>
	<div class="navbar">
		<h1 style="color:white;">NOTES KEEPING </h1>
	</div>
		
		 <div class="content"><p class="par">Fuel creativity by carrying a notebook for ideas, surrounding yourself<br> with inspiring visuals, dedicating time to hobbies, and collaborating <br>with others. Creativity thrives on new perspectives and regular practice.</p></div>
	
		<div class="form">
		
				<h2>Login</h2>
				<form action="" method="POST" class="login">
					<label for="username" style="color:white;">Username:</label>
					<input type="text" name="username" required><br>
					<label for="password" style="color:white;">Password:</label><br>
					<input type="password" name="password" required><br>
					<input type="submit" name="login" value="Login" class="btnn">
				</form>

			<br><br>

			<?php
			session_start();

			$con = new mysqli("localhost", "root", "", "trisha");

			if ($con->connect_error) {
				die("Connection failed: " . $con->connect_error);
			}


			if (isset($_POST['login'])) {
				$username = $_POST['username'];
				$password = $_POST['password'];

				$stmt = $con->prepare("SELECT id, password FROM user WHERE username = ?");
				$stmt->bind_param("s", $username);
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($user_id, $hash);

				if ($stmt->num_rows > 0) {
					$stmt->fetch();
					if (password_verify($password, $hash)) {
						$_SESSION['user_id'] = $user_id;
						$_SESSION['username'] = $username;
						header("Location: notes.php");
						exit();
					} else {
						echo "Invalid password.";
					}
				} else {
					echo "<span style='color:red;'>No user found with that username.</span>";
				}

				$stmt->close();
			}

			$con->close();
			?>
			
			<p style="color:white;">First Registration Here <a href="sign_page.php" style="color:green;">Registration</p>
	</div>

</div>
</body>
</html>
