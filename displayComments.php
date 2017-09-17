<script src="js/js/jquery-1.6.2.min.js" type="text/javascript"></script> 
<script src="js/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script>
$(function(){
   $('.myclassname .mylink').click(function(){
      $(this).hide();
      $('.myclassname .form').show();
      return false;
   });
});
</script>
<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "blog"; 

$conn = new mysqli ($servername, $username, $password, $dbname);

if($conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT id, name, comment, date FROM comments WHERE articleID = 1 and flag = '0' ORDER BY date DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	echo '<div class="response"> <h4>Responses</h4>';
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div class="media response-info"> <div class="media-left response-text-left">
						<img src="images/si.png" class="img-responsive" alt=""></div>
						<div class="media-body response-text-right">
							<h4><b>' . $row['name'] . '</b></h4>
							<p>' . $row['comment'] . '</p>
							<ul>
								<li>'. $row['date'] .'</li>
								<li><div class="myclassname">
							   <a href="" class="mylink">Reply</a>
							   <div class="form">
							   <br></br>
							   <h5>Reply</h5>
							   <div id = error_label></div>
							   <input type="text" id="sub-comment-name" placeholder="Name" name="myinput" id="myinput"/>
							   <br></br>
							   <textarea id="sub-comment-text" placeholder="Your Comment..." maxlength=250 required></textarea>
							   <input type="hidden" id="commentID" value="'.$row["id"].'"/>
							   <br></br>
							   <input type="submit" name="submit" id="submit_btn_subcomment" class="button" value="Submit Comment">
							   </div>
							</div></li>
							</ul>';
		$sql = "SELECT id, name, comment, date FROM subcomment WHERE parentCommentID = " .$row["id"]." ORDER BY date ASC";
		$resultSubComment = $conn->query($sql);
		if ($resultSubComment->num_rows > 0) {
			 while($rowSubComment = $resultSubComment->fetch_assoc()) {
			 	echo '<div class="media response-info">
								<div class="media-left response-text-left">
										<img src="images/si.png" class="img-responsive" alt="">
								</div>
								<div class="media-body response-text-right">
									<h4><b>' . $rowSubComment['name'] . '</b></h4>
									<p>' . $rowSubComment["comment"] . '</p>
									<ul>
										<li>'. $rowSubComment['date'] .'</li>
									</ul>		
								</div>
								<div class="clearfix"> </div>
							</div>';
			 }
		}
		echo '</div> <div class="clearfix"> </div> </div>';
    }
    echo  '</div>';
} else {
    //echo = echo . "0 results";
}
$conn->close();
?>