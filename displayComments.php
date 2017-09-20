<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script> 
<script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script>
$(function(){
   $('.myclassname .mylink').click(function(){
      $(this).siblings('.myclassname .mylink').hide();
      $(this).siblings('.myclassname .form').show();
      return false;
   });
});
</script>
<script>
$(function() {
   $('.myclassname .cancel_button').click(function(){
	  $('.myclassname .form').hide();
	  $('.myclassname .mylink').show();
      return true;
   });
});
</script>
<script>
$(function(){
     $('#ddlViewBy').change(function(){
     	//console.log( $("#ddlViewBy :selected").val());
     	$.ajax({
     		type: 'POST',
			url: 'displayComments.php', 
			data: {sortOrder: $("#ddlViewBy :selected").val(),
				   articleID: 1},
			success: function(dataReturned){
				$('#responsesReturned').html(dataReturned);
			}
		});
   });
});
</script>
<script>
$(function() {
    $(".myclassname .sub_comment_button").click(function() {
    //console.log("hey");
      // validate and process form here
      if($(this).siblings("#sub-comment-name").val() != "" && $(this).siblings("#sub-comment-text").val() != ""){
      	  $.ajax({
      		type: "POST",
			url: 'processSubComments.php', 
			data: {commentName: $(this).siblings("#sub-comment-name").val(),
				   commentID: $(this).siblings("#commentID").val(),
				   actualCommentText: $(this).siblings("#sub-comment-text").val()},
			success: function(data){
				//$('#sub-comment-name').val('');
				//$('#sub-comment-text').val('');
				//$('#error_label').html('');
				$('.myclassname .form').hide();
				$('.myclassname .mylink').show()
				$.ajax({
				type: "POST",
				data: {sortOrder: $("#ddlViewBy :selected").val(),
				   articleID: 1},
				url: 'displayComments.php',
				success: function(dataReturned){
					$('#responsesReturned').html(dataReturned);
				}
			});
			}
		});
      }else{
      	 $(this).siblings('#error_label_subcomment').html('<label class="error" for="name" id="error">Please provide a name and a comment</label>');
      }
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

$articleID = $_POST["articleID"]; 
$sortOrder="";
if($_POST["sortOrder"] == 1){
	$sortOrder = "DESC";
}else{
	$sortOrder = "ASC";
}

$sql = "SELECT id, name, comment, date FROM comments WHERE articleID = ". $articleID . " ORDER BY date " . $sortOrder ;
$result = $conn->query($sql);

date_default_timezone_set('EST');

if ($result->num_rows > 0) {
	echo '<div class="response"> <h4>Responses (' . $result->num_rows. ')</h4><h4>Sort by <select id="ddlViewBy">';
	if($_POST["sortOrder"] == 1){
		echo '<option value="1" selected="selected">newest</option><option value="2" >oldest</option>;
</select></h4>';
	}else{
		echo '<option value="1">newest</option><option value="2" selected="selected">oldest</option></select></h4>';
	}
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$phpdate = strtotime( $row['date'] );
		$mysqldate = date( 'd M, Y g:i:s A', $phpdate );
        echo '<div class="media response-info"> <div class="media-left response-text-left">
						<img src="images/si.png" class="img-responsive" alt=""></div>
						<div class="media-body response-text-right">
							<h4><b>' . $row['name'] . '</b></h4>
							<p>' . $row['comment'] . '</p>
							<ul>
								<li>'. $mysqldate .'</li>
								<li><div class="myclassname">
							   <a href="" class="mylink">Reply</a>
							   <div class="form">
							   <br></br>
							   <h5>Reply</h5>
							   <div id = error_label_subcomment></div>
							   <input type="text" id="sub-comment-name" placeholder="Name" name="myinput" id="myinput"/>
							   <br></br>
							   <textarea id="sub-comment-text" placeholder="Your Comment..." maxlength=250 required></textarea>
							   <input type="hidden" id="commentID" value="'.$row["id"].'"/>
							   <br></br>
							   <input type="submit" name="submit" id="submit_btn_subcomment" class="sub_comment_button" value="Submit">
							   <input type="submit" name="cancel" id="cancel_btn_subcomment" class="cancel_button" value="Cancel">
							   </div>
							</div></li>
							</ul>';
		$sql = "SELECT id, name, comment, date FROM subcomment WHERE parentCommentID = " . $row["id"] ." ORDER BY date ASC";
		$resultSubComment = $conn->query($sql);
		if ($resultSubComment->num_rows > 0) {
			 while($rowSubComment = $resultSubComment->fetch_assoc()) {
			 	$subphpdate = strtotime( $rowSubComment['date'] );
				$submysqldate = date( 'd M, Y g:i:s A', $subphpdate );
			 	echo '<div class="media response-info">
								<div class="media-left response-text-left">
										<img src="images/si.png" class="img-responsive" alt="">
								</div>
								<div class="media-body response-text-right">
									<h4><b>' . $rowSubComment['name'] . '</b></h4>
									<p>' . $rowSubComment["comment"] . '</p>
									<ul>
										<li>'. $submysqldate .'</li>
									</ul>		
								</div>
								<div class="clearfix"> </div>
							</div>';
			 }
		}
		echo '</div> <div class="clearfix"> </div> </div><hr>';
    }
    echo '</div>';
} else {
    //echo = echo . "0 results";
}
$conn->close();
?>