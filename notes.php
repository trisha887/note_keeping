<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

      echo"  <a href='logout.php'><button class='btn' style='margin-top:10px;border:2px solid black;'>Logout</button></a>";

$user_id = $_SESSION['user_id'];

  echo "<div style='text-align: center; margin-top:-90px; ' >";
    echo "<span style='color: blue; font-size: 20px;'>User ID: " . htmlspecialchars($user_id) . "</span>";
    echo "</div>";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
echo "<div style='text-align: center; color: blue; font-size: 20px; margin-top: 10px; '>";
    echo "Username: " . htmlspecialchars($username);
    echo "</div>";





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOTES KEEPING</title>
</head>


  <style>
  
  
/* button */
.btn {
  margin: 100px;
  padding: 15px 40px;
  border: none;
  outline: none;
  color: #FFF;
  cursor: pointer;
  position: relative;
  z-index: 0;
  border-radius: 12px;
  border:2px solid black;
}
.btn::after {
  content: "";
  z-index: -1;
  position: absolute;
  width: 100%;
  height: 100%;
  background-color: #333;
  left: 0;
  top: 0;
  border-radius: 10px;
}
/* glow */
.btn::before {
  content: "";
  background: linear-gradient(
    45deg,
    #FF0000, #FF7300, #FFFB00, #48FF00,
    #00FFD5, #002BFF, #FF00C8, #FF0000
  );
  position: absolute;
  top: -2px;
  left: -2px;
  background-size: 600%;
  z-index: -1;
  width: calc(100% + 4px);
  height:  calc(100% + 4px);
  filter: blur(8px);
  animation: glowing 20s linear infinite;
  transition: opacity .3s ease-in-out;
  border-radius: 10px;
  opacity: 0;
}

@keyframes glowing {
  0% {background-position: 0 0;}
  50% {background-position: 400% 0;}
  100% {background-position: 0 0;}
}

/* hover */
.btn:hover::before {
  opacity: 1;
}

.btn:active:after {
  background: transparent;
}

.btn:active {
  color: #000;
  font-weight: bold;
}

</style>








<body style="background-color:rgb(49,213,180);">
        
    <h1 style="text-align: center;">Your Notes</h1>

    <!-- Add Note Form -->
    <form action="" method="POST" class ="form"style="background-color:rgb(255,230,242) ; border:3px solid pink; height:650px; width:50%; text-align: center; margin-left:350px;">
       
	   <h2>Add Note</h2><br><br><br>
        
		<label for="title"><b>Title:</b></label><br><br>
        <input type="text" name="title" required style="width: 400px; height: 40px; overflow: auto;"><br><br><br>
        
		<label for="content"><b>Content:</b></label><br><br>
		<textarea name="content" required style="width: 400px; height: 200px; overflow: auto;"></textarea>
			
			
        <input type="submit" name="add_note" value="Add Note" class="btn">
		
    </form>
	

    <?php
    $con = new mysqli("localhost", "root", "", "trisha");

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (isset($_POST['add_note'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $stmt = $con->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $content);

        if ($stmt->execute()) {
            echo "Note added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Fetch Notes
    $stmt = $con->prepare("SELECT id, title, content FROM notes WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($note_id, $note_title, $note_content);

    echo "<h2 style='text-align: center;'>Your Notes</h2>";

    if ($stmt->num_rows > 0) {
        echo "<ul>";
        while ($stmt->fetch()) {
            
			echo "<li style='border:2px solid black; background-color:pink;'><b>$note_title</b>: $note_content <br><br><a href='update_note.php?id=$note_id'><div style='border: 5px solid rgb(255, 213, 234); height:30px;width:60px;'>Edit</div></a><pre>    </pre><a href='delete_note.php?id=$note_id'><div style='border: 5px solid rgb(255, 213, 234); height:30px;width:60px;'>Delete</div></a></li>";
			echo "<br><br>";
		}
        echo "</ul>";
    } else {
        echo "No notes found.";
    }

    $stmt->close();
    $con->close();
    ?>
</body>
</html>
