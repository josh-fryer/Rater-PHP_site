<?php
	$page_title = 'Logout';
    session_start();
    if (isset($_SESSION["userObj"]))
    {
        include ('includes/home_header.php');
    }
    else // logged out already
    {    
        include ('includes/header.php');
        session_destroy();
        echo "You are already logged out.";
        include ("includes/footer.html");
        exit();
    }	
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' )
    {
        $_SESSION = array();
        session_destroy();
        
        echo '<h1>Goodbye!</h1>
            <p>You are now logged out.</p>
            <a href="index.php">Return to Home</a>';
            
        
        include ("includes/footer.html");
        exit();
    }
    
?>

<form action="logout.php" method="post" role="form">
    <h4> Are you sure you want to logout?</h4>

    <button class="btn btn-primary" name="yes" type="submit">Yes</button>
   
</form>


<?php
    include ("includes/footer.html");
?>