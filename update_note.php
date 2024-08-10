<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_register.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$con = new mysqli("localhost", "root", "", "trisha");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['id'])) {
    $note_id = intval($_GET['id']);
    $stmt = $con->prepare("SELECT title, content FROM notes WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $note_id, $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($note_title, $note_content);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
    } else {
        echo "Note not found.";
        exit();
    }

    $stmt->close();
}

if (isset($_POST['update_note'])) {
    $note_id = intval($_POST['id']);
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $con->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssii", $title, $content, $note_id, $user_id);

    if ($stmt->execute()) {
        echo "<div style='margin-left:550px; border:2.5px solid green; height:50px;width:300px; '>Note updated successfully! <a href='notes.php'>Back to notes</a></div>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Note</title>
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
    <h1 style="text-align: center;">Update Note</h1>
    <form action="" method="POST" style="background-color:rgb(255,230,242) ; border:3px solid pink; height:650px; width:50%; text-align: center; margin-left:350px;">
        <br><br>
		<input type="hidden" name="id" value="<?php echo $note_id; ?>">
        <label for="title" >Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($note_title); ?> " required style="width: 400px; height: 40px; overflow: auto;"><br>
        <label for="content">Content:</label><br><br>
		
		
        <textarea name="content" required style="width: 400px; height: 200px; overflow: auto;"><?php echo htmlspecialchars($note_content); ?></textarea><br>
        <input type="submit" name="update_note" value="Update Note" class="btn">
    </form>
</body>
</html>
