<?php
session_start();
if (isset($_SESSION['user'])) {
	$user_id = $_SESSION['user'];
	$isLogged = true;
}else{
	$isLogged = false;
}

// Create connection
$servername = "localhost";
$username = "root";
$password = "";
$db = "mydatabase";
$conn = mysqli_connect($servername, $username, $password, $db);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"SET NAMES utf8");
mysqli_query($conn,"SET CHARACTER_SET utf8");



function getMenuPrice($conn, $menu_id)
{
	$getPRICE = mysqli_fetch_assoc(mysqli_query($conn, "SELECT offering_price FROM foods WHERE id = '$menu_id'"));
	$price = $getPRICE['offering_price'];
	return $price;
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validateMenu($conn, $menu_id)
{
	$sql = "SELECT * FROM foods WHERE id = '$menu_id'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 1) {
		return true;
	}else{
		return false;
	}
}

function getCustomerOrder($conn, $isLogged, $user_id, $menu_id, $change)
{
	$msg = "";
	if ($isLogged) {
		$price = getMenuPrice($conn, $menu_id );
		$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND status = 'cart'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) < 1) {
			$dataresult = mysqli_query($conn, "INSERT INTO orders (user_id, status) VALUES ('$user_id', 'cart')", MYSQLI_USE_RESULT);
			
			$order_id = mysqli_insert_id($conn);
			
			if (mysqli_query($conn, "INSERT INTO order_details (order_id, food_id, quantity, extra, price) VALUES ('$order_id', '$menu_id', '1', '', '$price')")){
				$msg = "Added to cart";
			}
		}else{
			
			$getID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM orders WHERE user_id = '$user_id' AND status = 'cart'"));
			$order_id = $getID['id'];
			
			$sql = "SELECT * FROM order_details WHERE order_id = '$order_id' AND food_id = '$menu_id'";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) < 1) {
				if(mysqli_query($conn, "INSERT INTO order_details (order_id, food_id, quantity, extra, price) VALUES ('$order_id', '$menu_id', '1', '', '$price')")){
					$msg = "Added to cart";
				}
			}else{
				if(mysqli_query($conn, "UPDATE order_details SET quantity = quantity $change 1 WHERE order_id = '$order_id' AND food_id = '$menu_id'")){
					$msg = "Added to cart";
				}
			}
			
			
		}
	}else{
		$actual_link = base64_encode("$_SERVER[REQUEST_URI]");
		
		header("Location: login.php?link=$actual_link");
		die();
	}
	return $msg;
}

function getFoodInfo($conn, $menu_id){
	$getFood = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, image FROM foods WHERE id = '$menu_id'"));
	$data['name'] = $getFood['name'];
	$data['image'] = $getFood['image'];
	return $data;
}

function deleteMenuOrder($conn, $user_id, $menu_id){
	$getOrderDetails = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM orders WHERE user_id = '$user_id' AND status = 'cart'"));
	$order_id = $getOrderDetails['id'];
	$sql = "SELECT * FROM order_details WHERE order_id = '$order_id' AND food_id = '$menu_id'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 1) {
		$sql = "DELETE FROM order_details WHERE order_id = '$order_id' AND food_id = '$menu_id'";
		if (mysqli_query($conn, $sql)) {
			return true;
		}
	}
	return false;
}


function getCartDetails($conn, $user_id){
	$cart['price'] = 0;
	$cart['item'] = 0;
	/* Calculate Cart Details */
	$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND status = 'cart'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$getID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM orders WHERE user_id = '$user_id' AND status = 'cart'"));
		$order_id = $getID['id'];
		
		$sql = "SELECT * FROM order_details WHERE order_id = '$order_id'";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while ($info = mysqli_fetch_assoc($result)) {
				$cart['price'] = $cart['price'] + ($info['price'] * $info['quantity']);
				$cart['item'] = $cart['item'] + 1;
			}
		}
	}
	return $cart;
}

?>