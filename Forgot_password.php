<?php 
	$page_title = 'Forgot Password';
	include ('includes/header.php');
?>
			
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		require_once ('connect_db.php');

		$errors = array();

		if (!isset($_POST['email']) && empty($_POST['email'])) {
			$errors[] = 'Please enter your email address.';
		} else {
			$e = $dbc->real_escape_string(trim($_POST['email']));
		}

		if (empty($errors)) { 
			$q = "SELECT user_id, first_name FROM users WHERE email='$e'"; // selects user from DB with matching email
			$r = $dbc->query($q);
			$num = $r->num_rows;
			if ($num == 1) // found user because only one row has returned 
			{ 
				$row = $r->fetch_array(MYSQLI_ASSOC);
				// *replace message link with full reset_pwd.php page link*
				$message = "<p>Hello {$row['first_name']},<br><br>
				Reset your password here: <a href=\"https://homepages.shu.ac.uk/~b5020551/Rater/Reset_pwd.php\">Reset Password</a><br>
				</p>";
				$message = wordwrap($message, 70);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				mail($_POST['email'], 'RATER - Reset Password', $message, $headers);

				echo '<h1>Reset Password Link Sent</h1>
						<p>Check your email inbox.</p>';

				$dbc->close(); 
				include ('includes/footer.html'); 
				exit();
			} 
			else 
			{ 
				echo '<h1>Error!</h1>
					<p>An account with this email address does not exist.</p>';
			}
		} 
		else 
		{ 
			echo '<h1>Error!</h1>
				<p>The following error(s) occurred:<br />';
			foreach ($errors as $msg) { 
				echo " - $msg<br />\n";
			}
			echo '</p><p>Please try again.</p><br />';
		} 

		$dbc->close(); 
	} 
?>

	<h1>Reset Your Password</h1>
	<form action="forgot_password.php" method="post" role="form">
		<input type="text" name="email" placeholder="Email address">
		<p><button class="btn btn-primary" name="submit" type="submit">Reset Password</button></p>
	</form>

<?php 
	include ('includes/footer.html'); 
?>