<?php
session_start();
if(isset($_POST["name"])){
$target_file = "images/". $_POST["email"] . basename($_FILES["picture"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

$check = getimagesize($_FILES["picture"]["tmp_name"]);
if($check !== false) {
/* echo "File is an image - " . $check["mime"] . "."; */
   $uploadOk = 1;
} else {
   $itempictureError = "File is not an image.";
   $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    $itempictureError = "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["picture"]["size"] > 2000000) {
    $itempictureError = "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $itempictureError = "Sorry, only JPG, JPEG, or PNG files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
   /** $itempictureError = "Sorry, your file was not uploaded."; **/
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
       /* echo "The file ". basename( $_FILES["itempicture"]["name"]). " has been uploaded.";*/
    	$servername = "localhost:3306";
		$username = "root";
		$password = "";
		$dbname = "blog"; 

		$conn = new mysqli ($servername, $username, $password, $dbname);

		if($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
			unlink($target_file);
		}
		$stmt = $conn->prepare("INSERT INTO user (name, email, password, picturepath) VALUES (?,?,?,?)");
		$name = $_POST["name"];
		//echo $name;
		$password = $_POST["password"];
		//echo $password;
		$email = $_POST["email"];
		//echo $email;
		$file = mysqli_real_escape_string($conn, $target_file);
		//echo $file;
		//$stmt->bind_param("ssss", $_POST["name"], $_POST["email"], $_POST["password"], mysqli_real_escape_string($targetfile));
		$stmt->bind_param("ssss", $name, $email, $password, $file);
		$stmt->execute();

		$stmt->close();

		$conn->close();
		header( 'Location: login.php' ) ;
		exit;
    } else {
        $itempictureError = "Sorry, there was an error uploading your file.";
    }
}
}
?>
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
	<div class="technology">
	<div class="container">
	<div class="form-style-6">
			<h1>Sign Up</h1>
			<form action = "signup.php" method = "POST" enctype="multipart/form-data">
			Name:
			<input type="text" name="name" placeholder="Your Name" required/>
			Email:
			<input type="email" name="email" placeholder="Email Address" required/>
			Password:
			<input type="password" name="password" required/>
			<br></br>
			Upload profile picture or don't upload if want to use default one:
			<input type="file" name="picture" />
			<br></br>
			<input type="submit" value="Send" />
			</form>
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