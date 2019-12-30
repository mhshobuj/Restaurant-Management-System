<?php
include 'lib/function.php';

$error = "";
$error_type = "";
$error_status = "hidden";



	
  
if (isset($_GET["menu_id"])) {
	$menu_id = test_input($_GET["menu_id"]);
	$update = test_input($_GET["update"]);
	if(validateMenu($conn, $menu_id))
	{
		if($update == "inc_quantity")
		{
			$msg = getCustomerOrder($conn, $isLogged, $user_id, $menu_id, '+');
			$error = "<strong>Done!</strong> Cart Updated";
			$error_status = "";
			$error_type = "success";
		}else if($update == "dec_quantity")
		{
			$msg = getCustomerOrder($conn, $isLogged, $user_id, $menu_id, '-');
			$error = "<strong>Done!</strong> Cart Updated";
			$error_status = "";
			$error_type = "success";
		}else if($update == "del_item")
		{
			if(deleteMenuOrder($conn, $user_id, $menu_id))
			{
				$error = "<strong>Done!</strong> Cart Updated";
				$error_status = "";
				$error_type = "success";
			}else{
				$error = "<strong>Error!</strong> Invalid Action";
				$error_status = "";
				$error_type = "danger";
			}
			
		}else{
			$error = "<strong>Error!</strong> Invalid action.";
			$error_status = "";
			$error_type = "danger";
		}
	}else{
		$error = "<strong>Error!</strong> Invalid order.";
		$error_status = "";
		$error_type = "danger";
	}
}
	


	/* Calculate Cart Details */
	$sql = "SELECT * FROM orders WHERE user_id = '$user_id' AND status = 'cart'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		$getID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM orders WHERE user_id = '$user_id' AND status = 'cart'"));
		$order_id = $getID['id'];
		
		$sql = "SELECT * FROM order_details WHERE order_id = '$order_id'";
		$result_cart = mysqli_query($conn, $sql);
	}else{
		header('Location: menu.php');
		die();
	}


?>


<?php
	include 'header.php';
?>



<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop Page- Ustora Demo</title>
    
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/responsive.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
   
    
    
    <? echo $html_data_header;?>
    
    <div class="mainmenu-area">
        <div class="container">
            <div class="row">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div> 
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="menu.php">Our Menu</a></li>
                        <li class="active"><a href="cart.php">Cart</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div> 
            </div>
        </div>
    </div> <!-- End mainmenu area -->
    
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shopping Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	<div class="alert alert-<?php echo $error_type;?>" <?php echo $error_status;?> style="max-width: 600px; margin-top: 50px; margin-left: auto; margin-right: auto;">
		<?php echo $error;?>
	</div>
    
    
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                
				
				
                
                <div class="col-md-8">
                    <div class="product-content-right">
                        <div class="woocommerce">
                            <form method="post" action="#">
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                        <tr>
                                            <th class="product-remove">&nbsp;</th>
                                            <th class="product-thumbnail">&nbsp;</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									
									
									
										<?php
										
											if (mysqli_num_rows($result_cart) > 0) {
												while ($info = mysqli_fetch_assoc($result_cart)) {
													$food_id = $info['food_id'];
													$quantity = $info['quantity'];
													$price = $info['price'];
													$item_total_price = $price * $quantity;
													
													$foodData = getFoodInfo($conn, $food_id);
													$food_name = $foodData['name'];
													$food_image = $foodData['image'];
													
													echo"
														<tr class='cart_item'>
															<td class='product-remove'>
																<a title='Remove this item' class='remove' href='?menu_id=$food_id&update=del_item'>×</a> 
															</td>
														
															<td class='product-thumbnail'>
																<a href='single-product.html'><img width='145' height='145' alt='$food_name' class='shop_thumbnail' src='img/food/$food_image'></a>
															</td>
														
															<td class='product-name'>
																<a href='single-product.html'>$food_name</a> 
															</td>
														
															<td class='product-price'>
																<span class='amount'>$price</span> 
															</td>
														
															<td class='product-quantity'>
																<div class='quantity buttons_added'>
																	<a  href='?menu_id=$food_id&update=dec_quantity' class='minus'>-</a>
																	<input disabled type='number' size='4' class='input-text qty text' title='Qty' value='$quantity' min='0' step='1'>
																	<a  href='?menu_id=$food_id&update=inc_quantity' class='plus'>+</a>
																</div>
															</td>
														
															<td class='product-subtotal'>
																<span class='amount'>$item_total_price</span> 
															</td>
														</tr>
													
													";
												}
											}else{
												echo"
													<tr><td>No Item added into Cart</td></tr>
												";
											}
										
										?>
									
									
                                        
                                        <tr>
                                            <td class="actions" colspan="6">
                                                <div class="coupon">
                                                    <label for="coupon_code">Coupon:</label>
                                                    <input type="text" placeholder="Coupon code" value="" id="coupon_code" class="input-text" name="coupon_code">
                                                    <input type="submit" value="Apply Coupon" name="apply_coupon" class="button">
                                                </div>
                                                
												<input type="submit" value="Checkout" name="proceed" class="checkout-button button alt wc-forward">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

                        </div>                        
                    </div>                    
                </div>
				
				
				
				
				
				
				
				<div class="col-md-4">
                    <div class="product-content-right">
                        <div class="woocommerce">
							<div class="cart-collaterals">
								<div class="cart_totals ">
									<h2>Cart Totals</h2>
									
									<?php
										$cartData = getCartDetails($conn, $user_id);
										$total_price = $cartData['price'];
									?>
	
									<table cellspacing="0">
										<tbody>
											<tr class="cart-subtotal">
												<th>Cart Subtotal</th>
												<td><span class="amount"><? echo $total_price;?></span></td>
											</tr>
	
											<tr class="shipping">
												<th>Shipping and Handling</th>
												<td>Free Shipping</td>
											</tr>
	
											<tr class="order-total">
												<th>Order Total</th>
												<td><strong><span class="amount"><? echo $total_price;?></span></strong> </td>
											</tr>
										</tbody>
									</table>
								</div>

                            </div>
						</div>
					</div>
				</div>
				
				
				
				
            </div>
        </div>
    </div>


    <div class="footer-top-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="footer-about-us">
                        <h2>u<span>Stora</span></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis sunt id doloribus vero quam laborum quas alias dolores blanditiis iusto consequatur, modi aliquid eveniet eligendi iure eaque ipsam iste, pariatur omnis sint! Suscipit, debitis, quisquam. Laborum commodi veritatis magni at?</p>
                        <div class="footer-social">
                            <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                            <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">User Navigation </h2>
                        <ul>
                            <li><a href="">My account</a></li>
                            <li><a href="">Order history</a></li>
                            <li><a href="">Wishlist</a></li>
                            <li><a href="">Vendor contact</a></li>
                            <li><a href="">Front page</a></li>
                        </ul>                        
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">Categories</h2>
                        <ul>
                            <li><a href="">Mobile Phone</a></li>
                            <li><a href="">Home accesseries</a></li>
                            <li><a href="">LED TV</a></li>
                            <li><a href="">Computer</a></li>
                            <li><a href="">Gadets</a></li>
                        </ul>                        
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="footer-newsletter">
                        <h2 class="footer-wid-title">Newsletter</h2>
                        <p>Sign up to our newsletter and get exclusive deals you wont find anywhere else straight to your inbox!</p>
                        <div class="newsletter-form">
                            <input type="email" placeholder="Type your email">
                            <input type="submit" value="Subscribe">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="copyright">
                       <p>&copy; 2015 uCommerce. All Rights Reserved. <a href="http://www.freshdesignweb.com" target="_blank">freshDesignweb.com</a></p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="footer-card-icon">
                        <i class="fa fa-cc-discover"></i>
                        <i class="fa fa-cc-mastercard"></i>
                        <i class="fa fa-cc-paypal"></i>
                        <i class="fa fa-cc-visa"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <!-- Latest jQuery form server -->
    <script src="https://code.jquery.com/jquery.min.js"></script>
    
    <!-- Bootstrap JS form CDN -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!-- jQuery sticky menu -->
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    
    <!-- jQuery easing -->
    <script src="js/jquery.easing.1.3.min.js"></script>
    
    <!-- Main Script -->
    <script src="js/main.js"></script>
  </body>
</html>