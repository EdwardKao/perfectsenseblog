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
$sql = "SELECT id, name, comment, date FROM comments WHERE articleID = 1 ORDER BY date DESC";
$result = $conn->query($sql);
$resultString='';
if ($result->num_rows > 0) {
	$resultString = '<div class="response"> <h4>Responses (' . $result->num_rows. ')</h4> <h4>Sort by <select id="ddlViewBy">
  <option value="1">newest</option>
  <option value="2">oldest</option>
</select></h4>
	';
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$phpdate = strtotime( $row['date'] );
		$mysqldate = date( 'd M, Y g:i:s A', $phpdate );
        $resultString = $resultString .'<div class="media response-info"> <div class="media-left response-text-left">
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
			 	$resultString = $resultString . '<div class="media response-info">
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
		$resultString = $resultString . '</div> <div class="clearfix"> </div> </div><hr>';
    }
    $resultString = $resultString . '</div>';
} else {
    //$resultString = $resultString . "0 results";
}
$conn->close();
?>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>Coding Blog</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Style Blog Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href='//fonts.googleapis.com/css?family=Raleway:400,600,700' rel='stylesheet' type='text/css'>
<link href="css/style.css" rel='stylesheet' type='text/css' />	
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- AJAX scripts -->
<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script> 
<script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<!-- actual scripts -->
<script>
 $(function() {
    $("#submit_btn").click(function() {
      // validate and process form here
      var sortOrderNumber;
      if(document.getElementById('#ddlViewBy')){
      	sortOrderNumber =  $("#ddlViewBy :selected").val();
      }else{
      	sortOrderNumber = 1;
      }
      console.log("sortOrderNumber: " + sortOrderNumber);
      if($("#main-comment-name").val() != "" && $("#main-comment-text").val() != ""){
      	  $.ajax({
      		type: "POST",
			url: 'processComment.php', 
			data: {commentName: $( "#main-comment-name" ).val(),
				   sortOrder: sortOrderNumber,
				   articleID: 1,
				   actualCommentText: $("#main-comment-text").val()},
			success: function(data){
				$('#main-comment-name').val('');
				$('#main-comment-text').val('');
				$('#error_label').html('');
				$.ajax({
				type: "POST",
				data: {sortOrder: sortOrderNumber,
				   articleID: 1},
				url: 'displayComments.php',
				success: function(dataReturned){
					$('#responsesReturned').html(dataReturned);
				}
			});
			}
		});
      }else{
      	 $('#error_label').html('<label class="error" for="name" id="error">Please provide a name and a comment</label>');
      }
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
$(function(){
   $('.myclassname .mylink').click(function(){
      $(this).hide();
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
$(function() {
    $(".myclassname .sub_comment_button").click(function() {
      // validate and process form here
      if($(this).siblings("#sub-comment-name").val() != "" && $(this).siblings("#sub-comment-text").val() != ""){
      	  $.ajax({
      		type: "POST",
			url: 'processSubComments.php', 
			data: {commentName: $(this).siblings("#sub-comment-name").val(),
				   commentID: $(this).siblings("#commentID").val(),
				   actualCommentText: $(this).siblings("#sub-comment-text").val()},
			success: function(data){
				//$(this).siblings('#sub-comment-name').val('');
				//$(this).siblings('#sub-comment-text').val('');
				//$(this).siblings('#error_label_subcomment').html('');
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


<!-- animation-effect -->
<link href="css/animate.min.css" rel="stylesheet"> 
<script src="js/wow.min.js"></script>
<script>
 new WOW().init();
</script>
<!-- //animation-effect -->
</head>
<body>
<div class="header" id="ban">
		<div class="container">
			<div class="head-left wow fadeInLeft animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInLeft;">
				<div class="header-search">
						<div class="search">
							<input class="search_box" type="checkbox" id="search_box">
							<label class="icon-search" for="search_box"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></label>
							<div class="search_form">
								<form action="#" method="post">
									<input type="text" name="Search" placeholder="Search...">
									<input type="submit" value="Send">
								</form>
							</div>
						</div>
				</div>
			</div>
			<div class="header_right wow fadeInLeft animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInLeft;">
			<nav class="navbar navbar-default">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
					<nav class="link-effect-7" id="link-effect-7">
						<ul class="nav navbar-nav">
							<li class="active act"><a href="index.php">Home</a></li>
							<li><a href="about.php">About</a></li>
							<li><a href="coding.php">Coding</a></li>
							<li><a href="signup.php">SignUp</a></li>
							<li><a href="login.php">Login</a></li>
							<!--<li><a href="music.html">Music</a></li>
							<li><a href="codes.html">Codes</a></li>
							<li><a href="contact.html">Contact</a></li> -->
						</ul>
					</nav>
				</div>
				<!-- /.navbar-collapse -->
			</nav>
			</div>
			<div class="nav navbar-nav navbar-right social-icons wow fadeInRight animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInRight;">
					<ul>
						<li><a href="#"> </a></li>
						<li><a href="#" class="pin"> </a></li>
						<li><a href="#" class="in"> </a></li>
						<li><a href="#" class="be"> </a></li>
						
						<li><a href="#" class="vimeo"> </a></li>
					</ul>
				</div>
			<div class="clearfix"> </div>	
		</div>
	</div>
	<!--start-main-->
	<div class="header-bottom">
		<div class="container">
			<div class="logo">
				<h1><a href="index.php">CODING BLOG</a></h1>
				<p><label class="of"></label>LET'S MAKE A BLOG POST<label class="on"></label></p>
			</div>
		</div>
	</div>
<!-- banner -->

<div class="banner-1">

</div>

	<!-- technology-left -->
	<div class="technology">
	<div class="container">
		<div class="col-md-9 technology-left">
			<div class="agileinfo">

		  <h2 class="w3">Building a Web Application 101</h2>
			<div class="single">
			   <img src="images/sing-1.jpg" class="img-responsive" alt="">
			    <div class="b-bottom"> 
			      <h5 class="top">Tips for building a web application using PHP, SQL, and HTML</h5>
				   <p class="sub">
				   1. Use html templates. 
				   2. Use Xampp as a web and database server to host and serve code.
				   3. Model your web application after MVC: Model, View, Controller; where
				   		Model = Database  = SQL;
				   		View = HTML/CSS;
				   		Controller = PHP
				   </p>
			      <p>On Sept 10 <a class="span_link" href="#"><span class="glyphicon glyphicon-comment"></span>0 </a><a class="span_link" href="#"><span class="glyphicon glyphicon-eye-open"></span>0</a></p>
				</div>
			 </div>
			 	<div class="coment-form" id="main-comment-section">
					<h4>Leave your comment for the current blog post</h4>
						<div id = error_label></div>
						<input type="text" id="main-comment-name" placeholder="Name" name="name" required>
						<textarea id="main-comment-text" placeholder="Your Comment..." maxlength=250 required></textarea>
						<input type="submit" name="submit" id="submit_btn" class="button" value="Submit Comment">
				</div>	
				<div class="clearfix"></div>
			 <div id = "responsesReturned"><?php echo $resultString?></div>
			</div>
		</div>
	</div>
</div>
<div class="footer">
		<div class="container">
			<div class="col-md-4 footer-left wow fadeInDown"  data-wow-duration=".8s" data-wow-delay=".2s">
				<h4>About Me</h4>
				<p>Computer Science Undergrad Looking for a Job</p>
				<img src="images/t4.jpg" class="img-responsive" alt="">
					<div class="bht1">
						<a href="about.php">Read More</a>
					</div>
			</div>
			<div class="col-md-4 footer-middle wow fadeInDown"  data-wow-duration=".8s" data-wow-delay=".2s">
			<h4>Latest Cool Sayings</h4>
			<div class="mid-btm">
				<p>Do what makes you happy.</p>
			</div>
				<p>Love and peace and unity.</p>
			</div>
			<div class="col-md-4 footer-right wow fadeInDown"  data-wow-duration=".8s" data-wow-delay=".2s">
				<h4>Newsletter</h4>
				<p>Don't have one, but can start one if you like</p>
						<div class="name">
							<form action="#" method="post">
								<input type="text" placeholder="Your Name" required="">
								<input type="text" placeholder="Your Email" required="">
								<input type="submit" value="Subscribe Now">
							</form>
						
						</div>	
						
							<div class="clearfix"> </div>
					
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<div class="copyright wow fadeInDown"  data-wow-duration=".8s" data-wow-delay=".2s">
				<div class="container">
					<p>© 2016 Coding Blog. All rights reserved | Design by <a href="http://w3layouts.com/">W3layouts</a></p>
				</div>
			</div>
</body>
</html>