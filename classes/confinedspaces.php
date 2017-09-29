<?php

function do_confined(){
	$confined = "confined";
	//NEW FUNCTION
	if (!current_user_can( $confined ) ){
		//WHEN ORDER COMPLETE GENERATE USERS FROM CODES FUNCTION
		add_action( 'woocommerce_order_status_completed', 'complete_function' );
		/*
		* Do something after WooCommerce sets an order on completed
		*/
		function complete_function($order_id) {
			// order object (optional but handy)
			$order = new WC_Order( $order_id );
			// do some stuff here
			// Getting the items in the order
			$current_user = wp_get_current_user();
			$current_email = $current_user->user_email;
			
			global $wpdb;
			$ccdbs = $wpdb->get_results( $wpdb->prepare("SELECT `key_id`, `order_id`, `activation_email`, `license_key`, `software_product_id`, `activations_limit`, `created` FROM `wp_woocommerce_software_licenses` WHERE order_id = $order_id "));
			echo "</br>";
			echo "License Keys";
			$order_items = $order->get_items();
			// Iterating through each item in the order
			foreach ($order_items as $item_id => $item_data) {
    	// Get the product name
    	$product_name = $item_data['name'];
    	// Get the item quantity
    	$item_quantity = $order->get_item_meta($item_id, '_qty', true);
    	// Get the item line total
    	$item_total = $order->get_item_meta($item_id, '_line_total', true);

    	// Displaying this data (to check)
    	echo 'Product name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. $item_total;

			for($x = 1; $x <=$item_quantity; $x = $x +1)
			{
				echo "</br>";
				// Here you can access to every object value in the way that you want
				$likey = $ccdb->license_key;
				echo $likey;
				//generate users from license keys and loop creation
				echo "USERS BEING GENERATED...";
				echo "</br>";
				echo "PLEASE WAIT";
				echo "</br>";
				$Space = "SPACE";
				$random = mt_rand(100000,500000);
				$newusername = $Space . $random;
				
				$email_address = $newusername;
				if( null == username_exists( $email_address ) ) {
					// Generate the password and create the user
					
					//BASIC PASSWORD TEST (PLAIN TEXT UN-SECURE)
					//$password = "hellomrsl";

					$password = uniqid();
					$user_id = wp_create_user( $email_address, $password, $email_address );
					// Set the nickname
					wp_update_user(
						array(
							'ID'          =>    $user_id,
							'nickname'    =>    $email_address
						)
					);
					// Set the role
					$user = new WP_User( $user_id );
					$user->set_role( 'confined' );
					// Email the user
					//wp_mail( $email_address, 'Welcome!', 'Your Password: ' . $password );
					wp_mail( $current_email, 'New User Details', 'Please provide these account details to your users. They require these to access their course: Username ='.$email_address.'Password ='.$password);
					//
					echo "New User Succesfully Added!";
					echo "</br>";
					
				} // end if
			}
			}
			echo "SCRIPT COMPLETED SUCCESFULLY!!";
			// Email the user
			wp_mail( 'michael.wing@rocketmail.com', 'Testing Order Complete', 'Order id ='.$order_id);
		}
	};
	function confinedcheck() {
		//do stuff
		//Assign roles to variables 
		$confined = "confined";
		if ( is_user_logged_in() ) {
			// your code for logged in user 
			if (current_user_can( $confined ) ){
				echo "Hello Confined Space member";
				$current_user = wp_get_current_user();
				if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, '40817' ) ) { 
					echo ' you already have access to the confined space course!';
				}
				else if ( !wc_customer_bought_product( $current_user->user_email, $current_user->ID, '40817' ) ) { 
					//GENERATE AN ORDER FOR CONFINED SPACES PRODUCT
					$address = array(
						'first_name' => 'Confined',
						'last_name'  => 'Space User',
						'company'    => 'confinedspace',
						'email'      => 'michael.wing@resolutiontelevision.com',
						'phone'      => '777-777-777-777',
						'address_1'  => '3 Woodfield House',
						'address_2'  => '', 
						'city'       => 'Scunthorpe',
						'state'      => 'Lincs',
						'postcode'   => 'DN15 7DQ',
						'country'    => 'UK'
					);
					$order = wc_create_order();
					//$order->add_product( get_product( '12' ), 2 ); //(get_product with id and next is for quantity)
					$order->add_product( get_product( '40817' ), 1 ); //(get_product with id and next is for quantity)
					$order->set_address( $address, 'billing' );
					$order->set_address( $address, 'shipping' );
					//$order->add_coupon('Fresher','10','2'); // accepted param $couponcode, $couponamount,$coupon_tax
					$order->calculate_totals();
					// assign the order to the current user
					update_post_meta($order->id, '_customer_user', get_current_user_id() );
					// payment_complete
					$order->payment_complete();
					echo "Order created!";
					$order->update_status( 'completed' );
					echo "order marked as complete";
				}
			}
		}
	}
};
