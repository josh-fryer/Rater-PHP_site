<?php
    function checkPIN($dbc, $uID, $pinInput)
    {
        $pin = $dbc->real_escape_string(trim($pinInput));

        $q = "SELECT fk_user_id, reviewPin FROM tradesmen 
        WHERE fk_user_id = $uID AND reviewPin = $pin";
        
        $result = $dbc->query($q);
        if($result->num_rows == 1)
        {
            // Found matching userid and pin
            return true;
        }
        else
        {
            return false;
        }
    }

    function applyRating($dbc, $userID, $rating)
    {
        $q = "INSERT INTO ratings (user_id, rating) VALUES ($userID, $rating)";
        if($dbc->query($q)) {
            return true;
        } else {
            return false;
        }
    }

    function calcAvgRating($dbc, $userID) // return average rating
    {
        // userID has been escaped and trimmed in calling script
        // formula AR = 1*a+2*b+3*c+4*d+5*e/5
        $user_id = $dbc->real_escape_string(trim($userID));

        $q = "SELECT AVG(rating) AS avgRating FROM ratings WHERE user_id = $user_id";
        $r = $dbc->query($q);
        $row = $r->fetch_array(MYSQLI_ASSOC);
        if ($row['avgRating'] > 0) {
            // return $row['avgRating'];
            return number_format ($row['avgRating'], 1);
        } else {
            return "Not enough ratings.";
        }
    }

?>