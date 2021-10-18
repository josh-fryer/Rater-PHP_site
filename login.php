<?php
	$page_title = 'Login';
	// check if logged in
	require_once ('login_tools.php');
	session_start();
	loginBlock();
	include ('includes/header.php');
?>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
		require_once ('connect_db.php'); // connenct to database
		//require_once ('login_tools.php');

		// pass in submitted username and password values and DB object.
		// $check and $isAdmin is bool value. $data is an array. 
		list($check, $data) = validate($dbc, $_POST['username'], $_POST['pass']);
		
		// if login successful, start session to store details about user who has logged in. 
		if ($check) 
		{
			//session_start();
			require_once ('user.php'); 
			if ($data['account_type'] == 'admin')
			{
				require_once ('admin.php'); // Admin class inherits User class
				$userObj = new Admin($dbc); // create class object
				$userObj->setIsAdmin(true);
			}
			else if ($data['account_type'] == 'tradesman')
			{
				require_once ('tradesman.php'); // Tradesman class inherits User class
				$userObj = new Tradesman;
				$userObj->setIsAdmin(false);
				// tradesman class functions:				
				$userObj->setAddressLine1($data['address_line1']);
				$userObj->setAddressLine2($data['address_line2']);
				$userObj->setCity($data['city']);
				$userObj->setPostcode($date['postcode']);
				$userObj->setProReg($data['proRegistration']);
				$userObj->setAvailableFrom($data['availableDateTo']);
				$userObj->setAvailableTo($data['availableDateFrom']);
				$userObj->setHourlyRate($data['hourlyRate']);
				$userObj->setReviewPin($data['reviewPin']);
				$userObj->setCompanyName($data['company_name']);
				$userObj->setTrade($data['trade']);			
			}

			$userObj->setUserID($data['user_id']);
			$userObj->setUsername($data['username']);
			$userObj->setFirstName($data['first_name']);
			$userObj->setLastName($data['last_name']);
			$userObj->setEmail($data['email']);
			$userObj->setPhoneNumber($data['phone_number']);
			$userObj->setRegDate($data['reg_date']);

			// store userObj as a session object. unserialize to use.
			$_SESSION["userObj"] = serialize($userObj);
			$dbc->close();
			load('index.php'); // !!change to profile php link!!
		}	
		else 
		{
			$errors = $data;
		}
		
		// check if there are any errors using the isset() and empty() functions
		// and display them using foreach loop to iterate through the $errors array.
		if (isset($errors) && !empty($errors))
		{
			echo '<p id="err_msg">There was a problem:<br>';
			foreach ($errors as $msg) 
			{ 
				echo " - $msg<br>";
			}
			echo 'Please try again or <a href="register.php">sign-up</a></p>';

		}
		$dbc->close();
	}	
?>

<!-- Display login form -->
<form action="login.php" method="post" class="form-signin" role="form">

	<h2 class="form-signin-heading">Please login</h2>

	<input type="text" name="username" placeholder="Username">
	<input type="password" name="pass" placeholder="Password">
	<p><button class="btn btn-primary" name="submit" type="submit">Login</button></p>
	<small><a href="Forgot_password.php">Forgot Password?</a></small>
	<small><a href="Forgot_username.php">Forgot Username?</a></small>
</form>

<?php
	include ('includes/footer.html');
?>
