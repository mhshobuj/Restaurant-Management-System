<?php
	$fname = "";
	$lname = "";
	$country = "";
	$city = "";
	$zip = "";
	$address = "";
	$email = "";
	$domain = "";
	$company = "";
	$package = "";
	
	$error = "";
	$error_status = "hidden";
  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fname = test_input($_POST["fname"]);
  $lname = test_input($_POST["lname"]);
  $country = test_input($_POST["country"]);
  $city = test_input($_POST["city"]);
  $zip = test_input($_POST["zip"]);
  $address = test_input($_POST["address"]);
  $email = test_input($_POST["email"]);
  $domain = test_input($_POST["domain"]);
  $company = test_input($_POST["company"]);
  $package = test_input($_GET["pack"]);
  
  if($package == "silver" || $package == "gold" || $package == "platinum")
  {
		// Create connection
	  	$servername = "localhost";
		$username = "root";
		$password = "";
		$db = "hosting";
		$conn = mysqli_connect($servername, $username, $password, $db);
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		mysqli_query($conn,"SET NAMES utf8");
		mysqli_query($conn,"SET CHARACTER_SET utf8");
		// Check Domain Status on server
		$sql = "SELECT * FROM orders WHERE (domain='$domain' AND status='active') OR domain='$domain' AND status='pending'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			$error = "<strong>Error!</strong> An account is already active with this domain <b>$domain</b>. You may contact with us for query.";
			$error_status = "";
		}else{
			// Accept Order and insert into database
			if (mysqli_query($conn, "INSERT INTO orders (domain, package, email, fname, lname, country, city, zip, address, company, status, paid, txn_id, txn_method)
								VALUES ('$domain', '$package', '$email', '$fname', '$lname', '$country', '$city', '$zip', '$address', '$company', 'pending', '0.0', '','')")) {
				include 'order-received.php';
				die();
				}
		}
		
		
  }else{
	header("Location: index.php");
	die();
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}



$pack = $_GET["pack"];
if($pack == "silver")
{
	$storage = "200 MB";
	$bandwidth = "5000 MB";
	$subdomain = "20";
	$email_no = "150";
	$price = "৳300";
	$title = "Silver Plan Package";
	$logo = "basic-plan.svg";
}else if($pack == "gold")
{
	$storage = "500 MB";
	$bandwidth = "8000 MB";
	$subdomain = "50";
	$email_no = "500";
	$price = "৳500";
	$title = "Gold Plan Package";
	$logo = "star-plan.png";
}else if($pack == "platinum")
{
	$storage = "1200 MB";
	$bandwidth = "20000 MB";
	$subdomain = "Unlimited";
	$email_no = "Unlimited";
	$price = "৳1000";
	$title = "Platinum Plan Package";
	$logo = "hosting.svg";
}else{
	header("Location: index.php");
	die();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Androstock -  Order Hosting Package</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.5">
	<!-- Framework Css -->
	<link rel="stylesheet" type="text/css" href="assets/css/lib/bootstrap.min.css">
	<!-- Font Awesome / Icon Fonts -->
	<link rel="stylesheet" type="text/css" href="assets/css/lib/font-awesome.min.css">
	<!-- Owl Carousel / Carousel- Slider -->
	<link rel="stylesheet" type="text/css" href="assets/css/lib/owl.carousel.min.css">
	<!-- Animations -->
	<link rel="stylesheet" type="text/css" href="assets/css/lib/animations.min.css">
	<!-- Style Theme -->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<!-- Responsive Theme -->
	<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
</head>
<body class="order-page">
<div class="wrapper">
	<!--===================== Header ========================-->
<header class="transparent">
	<div class="container">
		<div class="row">
			<div class="col-md-7">
				<div class="logo"><a href="index.php"><img src="assets/images/androstock-logo.svg" alt="logo"></a></div>
			</div>
			<div class="col-md-5">
				<ul class="menu">
					<li><a href="index.php">Home</a></li>
					<li><a href="contact.php">Contact Us</a></li>
				</ul>
			</div>
			
		</div>
	</div>
	<div class="mobile-block">
		<div class="logo-mobile"><a href="index.php"><img src="assets/images/androstock-logo.svg" alt="logo"></a></div>
		<a href="#" class="mobile-menu-btn"><span></span></a>
		<div class="mobile-menu">
			<div class="inside">
				<ul class="menu panel-group" id="accordion" aria-multiselectable="true">
					<li><a href="index.php">Home</a></li>
					<li><a href="contact.php">Contact Us</a></li>
				</ul><!--menu-->
				
			</div><!--inside-->
		</div><!--mobile-menu-->
	</div>
</header>
<!--===================== End of Header ========================-->
	<!--===================== Breadcrumbs ========================-->
	<div class="breadcrumbs">
		<div class="container">
			<h1>Order Hosting Package</h1>
			<p>Fill up your information to complete order</p>
		</div>
	</div>
	<!--===================== End of Breadcrumbs ========================-->
	<div class="alert alert-danger" <?php echo $error_status;?> style="max-width: 600px; margin-left: auto; margin-right: auto;">
		<?php echo $error;?>
	</div>
	<!--===================== Content Order ========================-->
	<div class="content-order container animatedParent">
		<div class="row">
			<div class="col-md-12">
				<h2 class="first">Selected Plan</h2>
			</div>
			<div class="col-md-8 animated bounceInLeft">
				<!--===================== Tab Content ========================-->
				<div class="tab-content">
					<div class="content">
						<div>
							<img width="40px" src="assets/images/<?php echo $logo;?>" alt="Standard Web Hosting">
						</div>
						<div class="center">
							<h6><?php echo $title;?></h6>
							<ul>
								<li>Storage SSD</li>
								<li>Bandwidth</li>
								<li>Subdomain</li>
								<li>Email Accounts</li>
							</ul>
							<span><b>Price</b>Yearly</span>
						</div>
						<div class="last">
							<a href="index.php">Change Plan</a>
							<ul>
								<li><?php echo $storage;?></li>
								<li><?php echo $bandwidth;?></li>
								<li><?php echo $subdomain;?></li>
								<li><?php echo $email_no;?></li>
							</ul>
							<span><?php echo $price;?>/Year</span>
						</div>
					</div><!--content-->
				</div>
				<!--===================== End of Tab Content ========================-->
				
				<!--===================== Account Details ========================-->
				<div class="account-details">
					<h2>Account Details</h2>
					<form action="" method="post">
						
						<div class="form-group col-2">
							<label>First Name <span>*</span></label>
							<input type="text" name="fname" value="<?php echo $fname;?>" required>
						</div>
						<div class="form-group col-2">
							<label>Last Name <span>*</span></label>
							<input type="text" name="lname" value="<?php echo $lname;?>" required>
						</div>
						<div class="form-group">
							<label>Country <span>*</span></label>
							<select name="country">
								<option value="Bangladesh">Bangladesh</option>
								<option value="Other">Other</option>
							</select>
						</div>
						<div class="form-group col-2">
							<label>City <span>*</span></label>
							<input type="text" name="city" value="<?php echo $city;?>" required>
						</div>
						<div class="form-group col-2">
							<label>ZIP Code <span>*</span></label>
							<input type="text" name="zip" value="<?php echo $zip;?>" required>
						</div>

						<div class="form-group">
							<label>Address <span>*</span></label>
							<input type="text" name="address" value="<?php echo $address;?>" required>
						</div>
						<div class="form-group">
							<label>Email Address <span>*</span></label>
							<input type="email" name="email" value="<?php echo $email;?>" required>
						</div>
						<div class="form-group">
							<label>Existing Domain Name <span>**</span></label>
							<input type="text" name="domain" placeholder="yourdomain.com" value="<?php echo $domain;?>" required>
						</div>
						<div class="form-group last">
							<span>
								<input type="radio" value="Individual" name="company" id="individual" checked>
								<label for="individual"><span></span>Individual</label>
							</span>
							<input type="radio" value="Company" name="company" id="company">
							<label for="company"><span></span>Company</label>
						</div>
						
						
					
					<div class="bottom">
						<h3>Payment Information</h3>
						<p>Billing information not required on this step. Once you submit this form, we will send you an email to finish payment. </p>
						<button type="submit">create my account</button>
					</div><!--bottom-->
					</form>
				</div>
				<!--===================== End of Account Details ========================-->
			</div>
			<div class="col-md-4 animated bounceInRight">
				<!--===================== Total Order ========================-->
				<div class="total-order">
					<h4>Summary</h4>
					<ul>
						<li>VPS Basic<span><?php echo $price;?></span></li>
						<li>Subtotal<span><?php echo $price;?></span></li>
						<li>VAT<span>৳0</span></li>
					</ul>
					<form>
						<div class="form-group">
							<input type="radio" name="month" checked id="year">
							<label for="year">Pay per Year <span class="first"></span><b><?php echo $price;?></b></label>
						</div>
					</form>
					<span class="total">TOTAL: <b><?php echo $price;?></b></span>
				</div>
				<!--===================== End of Total Order ========================-->
			</div>
		</div>
	</div>
	<!--===================== End of Content Order ========================-->
	<!--===================== Footer ========================-->
<footer>
	<div class="container">
		<div class="row">
		<a class="logoandro" href="index.html"><img width="130px" src="assets/images/androstock-logo.svg" alt="logo"></a>
		
		<p class="copyandro" style="color: white;">&copy; Copyright 2019 Androstock Hosting, All Rights Reserved</p>
		</div>
			
	</div>
</footer>
<!--===================== End of Footer ========================-->
</div><!--wrapper-->
<script src="assets/js/lib/jquery.js"></script>
<script src="assets/js/lib/bootstrap.min.js"></script>
<script src="assets/js/lib/owl.carousel.min.js"></script>
<script src="assets/js/lib/css3-animate-it.js"></script>
<script src="assets/js/lib/counter.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>