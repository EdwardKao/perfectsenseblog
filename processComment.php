<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "blog"; 

$conn = new mysqli ($servername, $username, $password, $dbname);

if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('EST');

$commentTime = date("Y-m-d H:i:s");
$flag = 0;
$articleID = $_POST["articleID"];
$name = $_POST["commentName"];
$comment = $_POST["actualCommentText"];


$stmt = $conn->prepare("INSERT INTO comments (name, comment, date, articleID, flag) VALUES (?,?,?,?,?)");
//$stmt->bind_param("s,s,s,i,i", $_POST['commentName'], $_POST['actualCommentText'], $commentTime, $articleID, $flag);
$stmt->bind_param("sssis", $name, $comment, $commentTime, $articleID, $flag);

$stmt->execute();

$stmt->close();

$conn->close();
?>
