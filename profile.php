<?php 
    $page_title = 'Profile';
	session_start();
    if (isset($_SESSION["userObj"]))
    {
        include ('includes/home_header.php');
        require_once ('user.php');
        require_once ('tradesman.php');    
        require_once ('admin.php');
        $userObj = unserialize($_SESSION["userObj"]);
    }
    else
    {
        include ('includes/header.php');
    }	
    require_once ('connect_db.php');
    require_once ('includes/rate_tools.php');
?>

<?php
    // set vars defaults
    $showPinForm = false;
    $showRateForm = false;

    if (isset($_GET['user_id'])) {
        $userID = $dbc->real_escape_string(trim($_GET['user_id']));
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') // form subitted
    {
        if (empty($_POST['pin']) && !isset($_POST['rating']))
        {
            echo "Error: Missing review pin.<br>";
        }
        
        if (!empty($_POST['pin']))
        {
            // if pin matches accounts PIN in db
            if (checkPIN($dbc, $_GET['user_id'], $_POST['pin']))
            {
                $showPinForm = false;
                $showRateForm = true;
            }
            else
            {
                $showPinForm = true;
                $showRateForm = false;
                echo "Error: Incorrect pin.<br>";
            }
        }

        if (isset($_POST['rating']))
        {
            // Apply rating and refresh page
            $rating = $dbc->real_escape_string(trim($_POST['rating']));
            if(applyRating($dbc, $userID, $rating))
            {
                echo '<p>Successfully submitted rating</p>';
            }
            else
            {
                echo '<p>Error: could not apply rating. Please try again.</p>';
            }

            $showPinForm = false;
            $showRateForm = false;
        }
    }
    // ----------------------

    if (isset($_GET['user_id'])) // viewing a search result
    {
        $showPinForm = true;
        //$userID = $dbc->real_escape_string(trim($_GET['user_id']));

        $q = "SELECT users.user_id, users.first_name, users.last_name, tradesmen.company_name, tradesmen.trade,
        tradesmen.hourlyRate, tradesmen.availableDateFrom, tradesmen.availableDateTo, tradesmen.tradesman_id
        FROM users 
        INNER JOIN tradesmen ON users.user_id = tradesmen.fk_user_id WHERE users.user_id=$userID";

        $r = $dbc->query($q);
        if ($r->num_rows == 1) // found the result
        {            
            $row = $r->fetch_array(MYSQLI_ASSOC);
            $AvgRt = calcAvgRating($dbc, $row['user_id']);

            // echo profile details
            echo "<h4>{$row['company_name']}</h4>
            Name: {$row['first_name']} {$row['last_name']}<br>
            Trade: {$row['trade']}<br>     
            Average Rating: {$AvgRt}<br>";  
        }        
    }
    else if (isset($userObj)) // get the logged in users profile
    {  
        // echo profile details
        if ($userObj instanceof Tradesman) // check if user is a tradesman 
        {      
            $AvgRt = calcAvgRating($dbc, $userObj->getUserID());
            echo "<h3>Viewing your own profile</h3>
            <h4>{$userObj->getCompanyName()}</h4>
            Name: {$userObj->getFirstName()} {$userObj->getLastName()}<br>
            Trade: {$userObj->getTrade()}<br>
            Average Rating: {$AvgRt}<br>";

        }
        else // is admin
        {    
            $showPinForm = false;
            $showRateForm = false;
            echo "<h4>Welcome Admin {$userObj->getFirstName()}</h4>
            Name: {$userObj->getFirstName()} {$userObj->getLastName()}<br>";
            // display admin settings form here

        }            
    }
?>

<!-- rating form: pin input that reloads this page to have db validate pin. If correct pin then display rate form -->
<?php
    if ($showPinForm == true && $showRateForm == false)
    {   
        echo "<form action='profile.php?user_id={$userID}' method='post' role='form'>
        <label for='pin'><b>If you have received this trademan's rating PIN, enter to leave a rating:</b></label>
        <input type='number' id='pin' name='pin' placeholder='12345'><br>
        <button class='btn btn-primary' name='submit' type='submit'>Submit</button>
        </form>";
    }

    if ($showRateForm == true)
    {
        echo "<form action='profile.php?user_id={$userID}' method='post' role='form'>
        <input type='radio' id='r1' name='rating' value='1'>
        <label for='r1'>1 Star</label><br>
    
        <input type='radio' id='r2' name='rating' value='2'>
        <label for='r2'>2 Star</label><br>
    
        <input type='radio' id='r3' name='rating' value='3'>
        <label for='r3'>3 Star</label><br>
    
        <input type='radio' id='r4' name='rating' value='4'>
        <label for='r4'>4 Star</label><br>
    
        <input type='radio' id='r5' name='rating' value='5'>
        <label for='r5'>5 Star</label><br>
    
        <button class='btn btn-primary' name='submit' type='submit'>Submit</button>
        </form>";
    }
?>

<?php
    $dbc->close();
    include ("includes/footer.html");
?>