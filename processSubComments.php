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
$bool = date('I');
$commentTime;
if($bool == 1){
	$commentTime = date("Y-m-d H:i:s");
}else{
	$commentTime = date("Y-m-d H:i:s", strtotime('+1 hours'));	
}
$parentcommentID = $_POST["commentID"];
$name = $_POST["commentName"];
$comment = $_POST["actualCommentText"];



$stmt = $conn->prepare("INSERT INTO subcomment (name, comment, parentcommentID, date) VALUES (?,?,?,?)");
//$stmt->bind_param("s,s,s,i,i", $_POST['commentName'], $_POST['actualCommentText'], $commentTime, $articleID, $flag);
$stmt->bind_param("ssis", $name, $comment, $parentcommentID, $commentTime);

$stmt->execute();

$stmt->close();

$conn->close();
?>
