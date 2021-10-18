<?php
    $page_title = 'Reset Password';
	include ('includes/header.php');
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {      
        require_once ('connect_db.php'); // connenct to database
        require_once ('login_tools.php');

            list($check, $data) = resetPwd($dbc, $_POST['email'], $_POST['pass'], $_POST['passConfirm']);
        
        if ($check) // success
        {
            echo 'Successfully reset password.<br>
                <h3><a href="login.php">Login</a></h3>'; // replace link with working link
        }
        else 
        {
            $errors = $data;
        }
        $dbc->close();       

        // check if there are any errors using the isset() and empty() functions
	    // and display them using foreach loop to iterate through the $errors array.
        if (isset($errors) && !empty($errors))
	    {
            echo '<p id="err_msg">There was a problem:<br>';
            foreach ($errors as $msg) 
            { 
                echo " - $msg<br>";
            }
            echo 'Please try again</p>';
	    }
    }
?>

<!-- Display reset password form -->
<form action="Reset_pwd.php" method="post" class="form-signin" role="form">
	<h2 class="form-signin-heading">Reset Password</h2>
	<label for="email">Email Address: </label>
    <input type="text" id="email" name="email" placeholder="Email Address"><br>
    <p>
    <label for="pass">New Password: </label>
    <input type="password" id="pass" name="pass" placeholder="New Password"><br>
    </p>
    <label for="passConfirm">Confirm New Password: </label>
    <input type="password" id="passConfirm" name="passConfirm" placeholder="Confirm New Password"><br>
    
	
    <p><button class="btn btn-primary" name="submit" type="submit">Submit</button></p>
</form>

<?php
	include ('includes/footer.html');
?>