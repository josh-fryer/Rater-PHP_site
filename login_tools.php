<?php
    // if the load() function is called without any arguments, the login page will be loaded
    function load($page = 'index.php')
    {
        // to build a URL string of protocol, current domain and directory
        $url = 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);

        // Remove all trailing slashes and concatenate the $page argument to the end
        $url = rtrim( $url, '/\\' ) ;
        $url .= '/' . $page ;

        // redirect to $url
        header("Location: $url"); 
        exit();
    }

    // if already logged in, 
    function loginBlock()
    {
        if (isset($_SESSION["user_obj"])) {
            include ('includes/home_header.php');	
            echo "<p>you are already logged in <a href='profile.php'>View Profile</a></p>";
            include ('includes/footer.php');
            exit();
        }
    }


    // will check the credentials that were entered in the login form against the database
    function validate($dbc, $username, $password)
    {
        $errors = array() ;

        // check email field is not empty
        if (empty($username)) 
        {
            $errors[] = 'Enter your username.'; // store error in array
        } 
        else 
        {
            $u = $dbc->real_escape_string(trim($username));
        }

        if (empty($password)) 
        {
            $errors[] = 'Enter your password.';
        } 
        else 
        {
            $p = $dbc->real_escape_string(trim($password));
        }
        
        // If there haven’t been any errors yet, attempt database query
        if (empty($errors)) 
        {
            $q = "SELECT * FROM users WHERE username='$u' AND password=SHA1('$p')";  
            $r = $dbc->query($q);
            if ($r->num_rows == 1)  // success
            {           
                // format the result set as an associative array - 
                // uses column names as keys                                  
                $row = $r->fetch_array(MYSQLI_ASSOC);
                if($row['account_type'] == 'tradesman')
                {
                    // if account is tradesman, also get tradesman account details
                    $q = "SELECT * FROM tradesmen WHERE fk_user_id = {$row['user_id']}";
                    $r = $dbc->query($q);
                    $row2 = $r->fetch_array(MYSQLI_ASSOC);
                    $merge = array_merge($row, $row2);
                    return array(true, $merge); 
                }      
                return array(true, $row);                      
            } 
            else //If the number of rows returned is not equal to 1, return false to indicate a failed attempt along with the $errors array
            { 
                $errors[] = 'Username and password not found.'; 
                return array(false, $errors); // returns false and errors array
            }
        }
        else
        {
            return array(false, $errors);
        }
    }
    
    function resetPwd($dbc, $email = '', $pwd = '', $confirmPwd = '')
    {
        $errors = array() ;

        // check email field is not empty
        if (empty($email)) 
        {
            $errors[] = 'Enter your email.'; // store error in array
        } 
        else 
        {
            $e = $dbc->real_escape_string(trim($email));
        }
        
        // check password field is not empty
        if (empty($pwd)) 
        {
            $errors[] = 'Enter your password.';
        } 
        else 
        {
            $p = $dbc->real_escape_string(trim($pwd));
            $haveP = true;
        }
        
        // check password field is not empty
        if (empty($confirmPwd)) 
        {
            $errors[] = 'Missing confirmation of your password.';
        } 
        else 
        {
            $haveCP = true;
        }
        
        // confirm there is both a password and confirmed password
        if ($haveP && $haveCP)
        {
            if ($pwd != $confirmPwd)
            {   
                $errors[] = '"New password" and "Confirm password" do not match.';
            }
        }

        // If there haven’t been any errors yet, attempt to retrieve the user record from the database
        if (empty($errors)) 
        {
            // Confirm account exists with submitted email
            $q = "SELECT user_id 
                FROM users 
                WHERE email ='$e'";  
            $r = $dbc->query($q);
            if ($r->num_rows == 1)  // success
            {                                 
                $q = "UPDATE users 
                    SET password = SHA1('$p') WHERE email ='$e' ";
                
                // update password value with new password in database 
                if ($dbc->query($q) == true)  
                {
                    // $errors is empty here, and is only there to make sure return is functional
                    return array(true, $errors);
                }
                else //SQL query failed
                {
                    $errors[] = 'System error. Please try again.';
                    return array(false, $errors);
                }               
            } 
            else //If the number of rows returned is not exactly 1, return false to indicate a failed attempt, along with the $errors array
            { 
                $errors[] = 'Account with this email address does not exist: $e.'; 
                return array(false, $errors); // returns errors array
            }
        }
        else
        {
            return array(false, $errors);
        }
    }

    function resetUsername($dbc, $email = '', $pwd = '', $confirmPwd = '')
    {
        $errors = array() ;

        // check email field is not empty
        if (empty($email)) 
        {
            $errors[] = 'Enter your email.'; // store error in array
        } 
        else 
        {
            $e = $dbc->real_escape_string(trim($email));
        }
        
        // check password field is not empty
        if (empty($pwd)) 
        {
            $errors[] = 'Enter your password.';
        } 
        else 
        {
            $p = $dbc->real_escape_string(trim($pwd));
            $haveP = true;
        }
        
        // check password field is not empty
        if (empty($confirmPwd)) 
        {
            $errors[] = 'Confirm your password.';
        } 
        else 
        {
            $haveCP = true;
        }
        
        // confirm there is both a password and confirmed password
        if ($haveP && $haveCP)
        {
            if ($pwd != $confirmPwd)
            {   
                $errors[] = '"New password" and "confirm password" do not match.';
                return array(false, $errors);
            }
        }

        // If there haven’t been any errors yet, attempt to retrieve the user record from the database
        if (empty($errors)) 
        {
            // Confirm account exists with submitted email
            $q = "SELECT user_id 
                FROM users 
                WHERE email ='$e'";  
            $r = $dbc->query($q);
            if ($r->num_rows == 1)  // success
            {                                 
                $q = "UPDATE users 
                    SET password = SHA1('$p') WHERE email ='$e' ";
                
                // update password value with new password in database 
                if ($dbc->query($q) == true)  
                {
                    // $errors is empty here, and is only there to make sure return is functional
                    return array(true, $errors);
                }
                else //SQL query failed
                {
                    $errors[] = 'System error. Please try again.';
                    return array(false, $errors);
                }               
            } 
            else //If the number of rows returned is not exactly 1, return false to indicate a failed attempt, along with the $errors array
            { 
                $errors[] = 'Account with this email address does not exist: $e.'; 
                return array(false, $errors); // returns errors array
            }
        }
        else
        {
            return array(false, $errors);
        }
    }

?>