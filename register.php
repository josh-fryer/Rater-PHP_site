<?php
  $page_title = 'Register';

  require_once ('login_tools.php');
	session_start();
	loginBlock();
  include ('includes/header.php');
  require_once ('connect_db.php');
  
  // get list of trades for register form trade select
  $q = "SELECT trade FROM trades"; 
  $r = $dbc->query($q);
  $tradesArr = array();
  if($r->num_rows > 0)
  {
    while ($row = $r->fetch_array(MYSQLI_ASSOC)){
      $tradesArr[] = $row;
    }
  }
  // print_r($tradesArr); 
?>

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST' )
  {
    $errors = array();

    if (empty($_POST['first_name'])) {
      $errors[] = 'Enter your first name.';
    } else {
      $fn = $dbc->real_escape_string(trim($_POST['first_name']));
    }

    if (empty($_POST['last_name'])) {
      $errors[] = 'Enter your last name.';
    } else {
      $ln = $dbc->real_escape_string(trim($_POST['last_name']));
    }

    if (empty($_POST['email'])) {
      $errors[] = 'Enter your email.';
    } else {
      $q = "SELECT user_id FROM users WHERE email='{$_POST['email']}'";
      $r = $dbc->query( $q );
      $rowcount = $r->num_rows;
      if ($rowcount != 0) {
        $errors[] = 'Email address already registered. <a href="login.php">Login</a>' ;	
      } 
      else { 
        $email = $dbc->real_escape_string(trim($_POST['email']));
      }
    }

    if (empty($_POST['pass1'])) 
    {
      $errors[] = 'Enter a password.';
    } 
    else 
    {
      $pass1 = $dbc->real_escape_string(trim($_POST['pass1']));      
      if (empty($_POST['pass2'])) 
      {
        $errors[] = 'Confirm your password.';
      }
      else 
      {
        $pass2 = $dbc->real_escape_string(trim($_POST['pass2']));

        if ($_POST['pass1'] != $_POST['pass2']) //if password1 not equal to pass2. passwords do not match
        {
          $errors[] = 'Passwords do not match.';
        }
      }
    }

    if (empty($_POST['username'])) {
      $errors[] = 'Enter a username.';
    } else {
      $u = $dbc->real_escape_string(trim($_POST['username']));
    }

    if (empty($_POST['phone'])) {
      $errors[] = 'Enter your phone number.';
    } else {
      $phone = $dbc->real_escape_string(trim($_POST['phone']));
    }

    if (empty($_POST['address1'])) {
      $errors[] = 'Enter your first line of address.';
    } else {
      $address1 = $dbc->real_escape_string(trim($_POST['address1']));
    }

    if (empty($_POST['address2'])) {
      $address2 = null;
    } else {
      $address2 = $dbc->real_escape_string(trim($_POST['address2']));
    }

    if (empty($_POST['city'])) {
      $errors[] = 'Enter your city.';
    } else {
      $city = $dbc->real_escape_string(trim($_POST['city']));
    }

    if (empty($_POST['company_name'])) {
      $errors[] = 'Enter your company name.';
    } else {
      $companyN = $dbc->real_escape_string(trim($_POST['company_name']));
    }

    if (empty($_POST['pin'])) {
      $errors[] = 'Enter a rating PIN.';
    } else {
      $pin = $dbc->real_escape_string(trim($_POST['pin']));
    }
    
    if (empty($_POST['proReg'])) {
      $proReg = null;
    } else {
      $proReg = $dbc->real_escape_string(trim($_POST['proReg']));
    }

    if (empty($_POST['hourlyRate'])) {
      $errors[] = 'Enter an hourly rate.';
    } else {
      $hourlyRate = $dbc->real_escape_string(trim($_POST['hourlyRate']));
    }

    if (!isset($_POST['trade'])) {
      $errors[] = 'Select your trade.';
    } else {
      $tradeOption = $dbc->real_escape_string(trim($_POST['trade']));
    }

    if (empty($_POST['availableDateFrom'])) {
      // defaults to todays date
      $dateFrom = $dbc->real_escape_string(trim(date("Y/m/d")));
    } else {
      $dateFrom = $dbc->real_escape_string(trim($_POST['availableDateFrom']));
    }

    if (empty($_POST['availableDateTo'])) {
      $dateTo = null;
    }
    else {
      $dateTo = $dbc->real_escape_string(trim($_POST['availableDateTo']));
    }

    if (empty($errors))  
    {
      // insert into users first
      $q = "INSERT INTO users (first_name, last_name, email, password, reg_date, username, account_type, phone_number) 
            VALUES ('$fn', '$ln', '$email', SHA1('$pass2'), NOW(), '$u','tradesman', '$phone')";
      if (!$dbc->query($q)) {
        $errors[] = 'Failed to register. Database fail. Please try again.'; 
      }
      else // INSERT success - continue register insert of tradesman details
      {
        // get userID
        $q = "SELECT user_id FROM users WHERE email='$email'";
        $r = $dbc->query($q);
        $row = $r->num_rows;
        if ($row != 1) 
        {
          $errors[] = 'Multiple accounts found with this email address. Contact Admin.';	
        }
        else
        {
          $row = $r->fetch_array(MYSQLI_ASSOC);
          $userID = $row['user_id'];
          $q = "INSERT INTO tradesmen (fk_user_id, address_line1, address_line2, city, proRegistration, availableDateFrom, availableDateTo, 
          hourlyRate, reviewPin, company_name, trade) 
          VALUES ('$userID', '$address1', '$address2', '$city', '$proReg', '$dateFrom','$dateTo', '$hourlyRate', '$pin', '$companyN', '$tradeOption')";      
          $r = $dbc->query($q);
          if ($r) { 
            echo '<h1>Registered!</h1><p>You are now registered.</p><p><a href="login.php">Login</a></p>'; 
          }
          $dbc->close();
          include ('includes/footer.html'); 
          exit();
        } 
      }
    }
    else
    {
      echo '<h3>Error!</h3><p id="err_msg">The following error(s) occurred:<br>';
      foreach ($errors as $msg) {
        echo " - $msg <br />";
      }
      echo "Please try again";
      $dbc->close();
    }    
  }
?>

<!-- Display register form -->
<!-- Php in input echos users input so they do not lose progress on a register error -->
<form action="register.php" method="post" role="form">
  <h2 class="form-signin-heading">Register a tradesman account</h2>
  <p>Fields marked '*' are required to continue.</p>
  <label for="fname">*First name:</label><br>
  <input type="text" id="fname" name="first_name" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" placeholder="First Name">
  <br><br>
  <label for="last_name">*Last name:</label><br> 
	<input type="text" id="last_name" name="last_name" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" placeholder="Last Name">
  <br><br>
  <label for="email">*Email Address:</label><br>
  <input type="text" id="email" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="Email Address">
  <br><br>
  <label for="phone">*Phone number:</label><br>
  <small>Is displayed on your profile and in searches</small><br>
  <input type="number" id="phone" name="phone" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>">
  <br><br>
  <!-- ------ Tradesman database fields --------- -->
  <label for="address1">*Address line 1:</label><br>
  <input type="text" id="address1" name="address1" value="<?php if (isset($_POST['address1'])) echo $_POST['address1']; ?>">
  <br><br>
  <label for="address2">Address line 2:</label><br>
  <input type="text" id="address2" name="address2" value="<?php if (isset($_POST['address2'])) echo $_POST['address2']; ?>">
  <br><br>

  <label for="city">*City:</label>
  <select name="city" id="cities">
                    <option value=""></option>
                    <option value="aberdeen">Aberdeen</option>
                    <option value="armagh">Armagh</option>
                    <option value="bangor">Bangor</option>
                    <option value="bath">Bath</option>
                    <option value="belfast">Belfast</option>
                    <option value="birmingham">Birmingham</option>
                    <option value="bradford">Bradford</option>
                    <option value="brighton">Brighton</option>
                    <option value="bristol">Bristol</option>
                    <option value="cambridge">Cambridge</option>
                    <option value="canterbury">Canterbury</option>
                    <option value="cardiff">Cardiff</option>
                    <option value="carlisle">Carlisle</option>
                    <option value="chelmsford">Chelmsford</option>
                    <option value="chester">Chester</option>
                    <option value="chichester">Chichester</option>
                    <option value="coventry">Coventry</option>
                    <option value="derby">Derby</option>
                    <option value="derry">Derry</option>
                    <option value="dundee">Dundee</option>
                    <option value="durham">Durham</option>
                    <option value="edinburgh">Edinburgh</option>
                    <option value="ely">Ely</option>
                    <option value="exeter">Exeter</option>
                    <option value="glasgow">Glasgow</option>
                    <option value="exeter">Exeter</option>
                    <option value="gloucester">Gloucester</option>
                    <option value="hereford">Hereford</option>
                    <option value="inverness">Inverness</option>
                    <option value="hull">Hull</option>
                    <option value="lancaster">Lancaster</option>
                    <option value="leeds">Leeds</option>
                    <option value="leicester">Leicester</option>
                    <option value="lichfield">Lichfield</option>
                    <option value="lincoln">Lincoln</option>
                    <option value="liverpool">Liverpool</option>
                    <option value="london">London</option>
                    <option value="manchester">Manchester</option>
                    <option value="newcastle">Newcastle</option>
                    <option value="newport">Newport</option>
                    <option value="norwich">Norwich</option>
                    <option value="nottingham">Nottingham</option>
                    <option value="oxford">Oxford</option>
                    <option value="perth">Perth</option>
                    <option value="peterborough">Peterborough</option>
                    <option value="plymouth">Plymouth</option>
                    <option value="portsmouth">Portsmouth</option>
                    <option value="preston">Preston</option>
                    <option value="ripon">Ripon</option>
                    <option value="st albans">St Albans</option>
                    <option value="st davids">St Davids</option>
                    <option value="salford">Salford</option>
                    <option value="salisbury">Salisbury</option>
                    <option value="sheffield">Sheffield</option>
                    <option value="southampton">southampton</option>
                    <option value="stirling">Stirling</option>
                    <option value="stoke-on-trent">Stoke-on-Trent</option>
                    <option value="sunderland">Sunderland</option>
                    <option value="swansea">Swansea</option>
                    <option value="truro">Truro</option>
                    <option value="wakefield">Wakefield</option>
                    <option value="wells">Wells</option>
                    <option value="westminster">Westminster</option>
                    <option value="winchester">Winchester</option>
                    <option value="wolverhampton">Wolverhampton</option>
                    <option value="worcester">Worcester</option>
                    <option value="york">York</option>
  </select><br>
  <br>
  <!-- echo database list of trades as client specified not to let users be able to write their own trade -->
  <label for="trade">Trade:</label><br>
  <select id="trade "name="trade">
    <option value=""></option>
    <?php
      // loop through all trades. 
      foreach ($tradesArr as $trades) {
        echo "<option value='{$trades['trade']}'>{$trades['trade']}</option>";
      }
      /*
      for ($i=0; $i < count($tradesArr); $i++) { 
          echo "<option value='{$tradesArr[$i]}'>{$tradesArr[$i]}</option>";
      }*/    
    ?>
  </select>  
  <br><br>
  
  <label for="company_name">*Company name:</label><br>
  <input type="text" id="company_name" name="company_name" value="<?php if (isset($_POST['company_name'])) echo $_POST['company_name']; ?>"><br><br>

  <label for="proReg">Professional trade association:</label><br>
  <small>If you are registered with any professional trade Association, enter your Association details here</small><br>
  <textarea id="proReg" name="proReg" rows="2" cols="50">
    <?php if (isset($_POST['proReg'])) echo $_POST['proReg']; ?>
  </textarea><br><br>

  <label for="availableDateFrom">Available to work from:</label><br>
  <small>Default is todays date. Leave blank for default.</small><br>
  <input type="date" id="availableDateFrom" name="availableDateFrom" value="<?php if (isset($_POST['availableDateFrom'])) echo $_POST['availableDateFrom']; ?>">
  <br><br>
  <label for="availableDateTo">Available to work up-to:</label><br>
  <small>Can be left blank</small><br>
  <input type="date" id="availableDateTo" name="availableDateTo" value="<?php if (isset($_POST['availableDateTo'])) echo $_POST['availableDateTo']; ?>">
  <br><br>
  <label for="hourlyRate">Hourly Rate:</label><br>
  <input type="number" id="hourlyRate" name="hourlyRate" min="1" step="0.01" value="<?php if (isset($_POST['hourlyRate'])) echo $_POST['hourlyRate']; ?>" placeholder="£ 00.00">
  <br>
  <p>Set your Review PIN below. Send it to clients you have completed work for, in order for them to post reviews of your service on your profile.</p>
  <label for="pin">Review PIN:</label><br>
  <small>Must be 5 digits long</small><br>
  <input type="text" pattern="\d*" minlength="5" maxlength="5" id="pin" name="pin" value="<?php if (isset($_POST['pin'])) echo $_POST['pin']; ?>"><br><br>
  <!-- --------- END of tradesman db fields --------- -->

  <label for="username">*Username:</label><br>
  <input type="text" id="username" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" placeholder="username">
  <br><br>
  <label for="pass1">*Password:</label><br>
  <small>Must be minimum 8 characters long</small><br>
  <input type="password" id="pass1" name="pass1" minlength="8" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>" placeholder="Password">
  <br><br>
  <label for="pass2">*Confirm password:</label><br>
  <input type="password" id="pass2" name="pass2" minlength="8" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>" placeholder="Confirm Password"> 

  <p><button class="btn btn-primary" name="submit" type="submit">Submit</button></p>
</form>

<?php
  include ("includes/footer.html");
?>