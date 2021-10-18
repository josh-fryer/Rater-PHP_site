<?php 
	$page_title = 'Forgot Username';
	include ('includes/header.php');
?>
			
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') 
	{
		require_once ('connect_db.php');

		$errors = array();

		if (empty($_POST['email'])) {
			$errors[] = 'You forgot to enter your email address.';
		} else {
			$e = $dbc->real_escape_string(trim($_POST['email']));
		}

		if (empty($errors)) 
		{ 
			$q = "SELECT user_id, first_name, username FROM users WHERE email='$e'"; // selects user from DB with matching email
			$r = $dbc->query($q);
			$num = $r->num_rows;
			if ($num == 1) // found user because only one row has returned 
			{ 
				$row = $r->fetch_array(MYSQLI_ASSOC);
				// *replace message link with full login page link*
				$to = $_POST['email'];
				$subject = 'RATER - Username Request';
				$message = "Hello {$row['first_name']},
				<p>
				Your username is: {$row['username']}<br><br>
				<a href=\"https://homepages.shu.ac.uk/~b5020551/Rater/login.php\">Login Here</a><br>
				</p>";
				$message = wordwrap($message, 70);
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

				if(mail($to, $subject, $message, $headers))
				{
					echo '<h1>Username Successfully Recovered</h1>
						<p>Check your email inbox.</p>';
					$dbc->close(); 
					include ('includes/footer.html'); 
					exit();
				}
				else
				{
					echo '<h1>Error! unable to send email</h1>
						<p>Check your entered email address.</p>';
				}					
			} 
			else 
			{ 
				echo '<h1>Error!</h1>
					<p>No account with this email address exists.</p>';
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

	<h1>Forgot Username</h1>
	<form action="Forgot_username.php" method="post" role="form">
		<input type="text" name="email" placeholder="Email address">
		<p><button class="btn btn-primary" name="submit" type="submit">Submit</button></p>
	</form>

<?php 
	include ('includes/footer.html'); 
?>