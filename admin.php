<?php
    require_once ('user.php');
    class Admin extends User
    {
        // array of trades
        private $tradesList; 

        // set '$tradeslist' on instantation
        public function __construct($dbc)
        {
            $this->updateTradesList($dbc); 
        }

        private function updateTradesList($dbc) 
        {
            // selects all trades from trade column
            $q = "SELECT trade FROM trades";  
            $r = $dbc->query($q);
            if ($r->num_rows > 0)  // if there are rows to add
            {             
                // get trades list as an array.
                $this->tradesList = $r->fetch_array(MYSQLI_ASSOC);                                         
            }
        }

        public function getTradesList()
        {            
            return $this->tradesList;            
        }

        public function addTradeToList($dbc, $trade)
        {
            // check if trade is already in trades table
            $t = $dbc->real_escape_string(trim($trade));
            $q = "SELECT trade FROM trades WHERE trade = '$t'";
            $r = $dbc->query($q);
            if ($r->num_rows == 1)  // found existing trade in table
            {
                return "Error: {$trade} already exists in trades database table";                                            
            }
            else // trade not found in list, so Insert trade into table.
            {
                $q = "INSERT INTO trades (trade) VALUES ('$t')";
                if ($dbc->query($q) === TRUE)  // success
                {
                    // update this tradeslist              
                    $this->updateTradesList($dbc);
                    return "Succesfully inserted {$trade}.";                                         
                }
                else
                {
                    return "Error: Database error. Could not insert {$trade} into trades list"; 
                }
                
            }           
        }

       public function removeTrade($dbc, $trade)
       {
           // removes trade from list of trades. trades list is used when a user picks a trade for their account.
            // check if trade is in trades table:
            $t = $dbc->real_escape_string(trim($trade));
            $q = "SELECT trade FROM trades WHERE trade = '$t'";
            $r = $dbc->query($q);
            if ($r->num_rows == 1)  // found trade in table
            {
                // delete trade
                $q = "DELETE FROM trades WHERE trade = '$t'";
                if ($dbc->query($q) === TRUE)  // success
                {                
                    // update trades list
                    $this->updateTradesList($dbc); 
                    return "Succesfully deleted {$trade} from database.";                                         
                }
                else
                {
                    return "Error: Database error. Could not delete {$trade} from database."; 
                }                                  
            }
            else // trade not found.
            {
                return "Error: {$trade} cannot be found in trades table";          
            }  
       }

    }
?>