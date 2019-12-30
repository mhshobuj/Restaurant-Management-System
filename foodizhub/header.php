<?php

$total_price = 0;
$total_item = 0;
	
if (isset($_SESSION['user'])) {
	$user_id = $_SESSION['user'];
	
	$cartData = getCartDetails($conn, $user_id);
	$total_price = $cartData['price'];
	$total_item = $cartData['item'];
	
	
	$html_data_header = "
	
	<div class='header-area'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-8'>
                    <div class='user-menu'>
                        <ul>
                            <li><a href='register.php'><i class='fa fa-user'></i> My Orders</a></li>
                        </ul>
                    </div>
                </div>
				
				<div class='col-md-4'>
                    <div class='header-right'>
                        <ul class='list-unstyled list-inline'>
								<li><a href='logout.php'><i class='fa fa-user'></i> Logout</a></li>
						</ul>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End header area -->
    
    <div class='site-branding-area'>
        <div class='container'>
            <div class='row'>
                <div class='col-sm-6'>
                    <div class='logo'>
                        <h1><a href='./'><img src='img/logo.png'></a></h1>
                    </div>
                </div>
                
                <div class='col-sm-6'>
                    <div class='shopping-item'>
                        <a href='cart.php'>Cart - <span class='cart-amunt'>$total_price</span> <i class='fa fa-shopping-cart'></i> <span class='product-count'>$total_item</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End site branding area -->
	
	";
}else{
	
	$html_data_header = "
	
	<div class='header-area'>
        <div class='container'>
            <div class='row'>
                <div class='col-md-8'>
                    <div class='user-menu'>
                        <ul>
                            <li><a href='login.php'><i class='fa fa-user'></i> Login</a></li>
							<li><a href='register.php'><i class='fa fa-user'></i> Registration</a></li>
                        </ul>
                    </div>
                </div>
				
				
            </div>
        </div>
    </div> <!-- End header area -->
    
    <div class='site-branding-area'>
        <div class='container'>
            <div class='row'>
                <div class='col-sm-6'>
                    <div class='logo'>
                        <h1><a href='./'><img src='img/logo.png'></a></h1>
                    </div>
                </div>
                
                <div class='col-sm-6'>
                    <div class='shopping-item'>
                        <a href='#'>Cart - <span class='cart-amunt'>$total_price</span> <i class='fa fa-shopping-cart'></i> <span class='product-count'>$total_item</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End site branding area -->
	
	";
	
}
?>