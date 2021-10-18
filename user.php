<?php 
    class User
    {
        private $userID;

        private $firstName;
        private $lastName;
        private $username;
        private $emailAddress;
        private $phoneNumber;
        private $isAdmin;
        private $regDate;

        public function setUserID($ID)
        {
            $this->userID = $ID;
        }

        public function getUserID()
        {
            return $this->userID;
        }

        public function setIsAdmin($bool)
        {
            $this->isAdmin = $bool;
        }

        public function getIsAdmin()
        {
            // returns true or false
            return $this->isAdmin;
        }

        public function setFirstName($fN)
        {
            $this->firstName = $fN;
        }

        public function getFirstName()
        {
            return $this->firstName;
        }

        public function setLastName($lN)
        {
            $this->lastName = $lN;
        }

        public function getLastName()
        {
            return $this->lastName;
        }

        public function setUsername($usernameVar)
        {
            $this->username = $usernameVar;
        }

        public function getUsername()
        {
            return $this->username;
        }

        public function setPassword($passwordVar)
        {
            $this->password = $passwordVar;
        }

        public function setPhoneNumber($phoneNumVar)
        {
            $this->phoneNumber = $phoneNumVar;
        }

        public function getPhoneNumber()
        {
            return $this->phoneNumber;
        }

        public function setEmail($emailVar)
        {
            $this->emailAddress = $emailVar;
        }

        public function getEmail()
        {
            return $this->emailAddress;
        }

        public function deleteAccount()
        {
            require_once ('connect_db.php');
            $myUserID = $this->userID;

            $q = "SELECT * FROM users WHERE user_id='$myUserID'";  
            $r = $dbc->query($q);
            if ($r->num_rows == 1)  // success
            {
                $q = "DELETE users, tradesmen FROM users 
                INNER JOIN tradesmen 
                ON users.user_id = tradesmen.fk_user_id
                WHERE users.user_id = $myUserID";
                if($dbc->query($q) === TRUE)
                {
                    $dbc->close();
                    return 'Successfully removed account';
                }
                else
                {
                    $dbc->close();
                    return "Error: Could not delete account. Please try again.";
                }
                
            }
            else
            {
                $dbc->close();
                return 'Error: Database error please try again.';
            }
        }

        public function setRegDate($regDate)
        {
            // sets to reg date from database
            $this->regDate = $regDate;
        }

        public function getRegDate()
        {
            return $this->regDate;
        }
    }
?>