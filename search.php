<?php
    $page_title = 'Search';

    session_start();
    if (isset($_SESSION["userObj"]))
    {
        include ('includes/home_header.php');
    }
    else
    {
        include ('includes/header.php');
    }	

    /*echo "<script>
    function filterHide() {
      var x = document.getElementById('hide');
      if (x.style.display === 'none') {
        x.style.display = 'block';
      } else {
        x.style.display = 'none';
      }
    }
    </script>"*/

?>

<h1>Search Results</h1>

<form action="search.php" method="POST">
    Search for a trade, name or company name:<br>
    <input type="search" name="mySearch" placeholder="Search..." 
    value="<?php    // saves search query on page load:
            if (isset($_POST['mySearch'])) { echo $_POST['mySearch'];}
            else if (isset($_GET['search'])) { echo $_GET['search'];} 
        ?>">  
    <button type="submit" name="submit-search">Search</button>
    <p>
        <!-- <button onclick="filterHide()">Show/Hide Filters</button> -->
        <div class='filters' id="hide">
            <p>
                <h4>Filters:</h4>
                <!-- Select City dropdown  list -->
                <label for="city">City:</label>
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

                <label for="maxRate">Max Hourly Rate:</label>
                <input type="number" name="maxRate" placeholder="£ MAX">

                <!-- Select date tradesman is needed from -->
                <label for="dateFrom">Date from:</label>
                <input type="date" name="dateFrom" min="2020-12-31">          
            </p>
        </div>
    </p>
</form>

<?php
    require_once ('connect_db.php');
    $errors = array();
    
    // Vars for listing
    $num = 0;
    $results_per_page = 10;

    // check if search field is not empty
    if (isset($_POST['mySearch']) || isset($_GET['search'])) 
    {
        // ------- Build a URL -------
        $url = 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        // Remove all trailing slashes and concatenate the $page argument to the end
        $url = rtrim( $url, '/\\' ) ;
        $url .= '/';
        // -------------------------
        
        // check which page user is on or set page number.
        if (isset($_GET["page"])) { $page = $_GET["page"]; } else { $page=1; }
        $start_from = ($page-1) * $results_per_page;
            
        // Search all name fields, order by pro-registered first.        
        $q = "SELECT users.user_id, users.first_name, users.last_name, tradesmen.company_name, tradesmen.trade,
        tradesmen.hourlyRate, tradesmen.availableDateFrom, tradesmen.availableDateTo, tradesmen.tradesman_id, tradesmen.proRegistration
        FROM users 
        INNER JOIN tradesmen ON users.user_id = tradesmen.fk_user_id WHERE";
        // ### Filter Statements ###
        // ------- check if search filters are set ------
        $filterSet = false;
        if(isset($_POST['city']) && !empty($_POST['city'])) 
        {
            $city = $dbc->real_escape_string(trim($_POST['city']));
            // concanate on to end of where query
            $q = $q." tradesmen.city = '$city'";
            $filterSet = true;
        }

        if(isset($_POST['maxRate']) && !empty($_POST['maxRate'])) 
        {
            $maxRate = $dbc->real_escape_string(trim($_POST('maxRate')));
            if ($filterSet == true){
                $q = $q." AND";
            }
            $q = $q." tradesmen.hourly_rate < '$maxRate'";
            $filterSet = true;
        }

        if(isset($_POST['dateFrom']) && !empty($_POST['dateFrom'])) 
        {
            $dateFrom = $dbc->real_escape_string(trim($_POST['dateFrom']));
            if ($filterSet == true){
                $q = $q." AND";
            }
            $q = $q." tradesmen.availableDateFrom > $dateFrom";
            $filterSet = true;
        }
        // ------------ END ------------

        if(isset($_POST['mySearch']))
        {
            // if no search input then display all 
            // $q = $q." ORDER BY tradesmen.proRegistration IS NULL, tradesmen.proRegistration ASC";
            $search = $dbc->real_escape_string(trim($_POST['mySearch']));      
        }
        else if (isset($_GET['search'])) //from index page
        {      
            $search = $dbc->real_escape_string(trim($_GET['search'])); 
        }

        if ($filterSet == true) {
            $q = $q." AND";
        }

        // NOTE: 'ORDER BY' is ASC by default
        $q = $q." users.first_name LIKE '%$search%' OR users.last_name LIKE '%$search%' 
            OR tradesmen.trade LIKE '%$search%' OR tradesmen.company_name LIKE '%$search%'
            ORDER BY tradesmen.proRegistration DESC";
         // echo "<p>{$q}</p>"; < for sql debug
        $r1 = $dbc->query($q);
        $num = $r1->num_rows;
        $total_pages = ceil($num / $results_per_page);
        // End query with Limit and OFFSET.
        // limit by amount of results-per-page, 
        //  and use OFFSET to select which page of results to show. 
        $q = $q." LIMIT $results_per_page OFFSET $start_from";
        $r = $dbc->query($q); 
        
        if ($num > 0)
        {
            include_once ('includes/rate_tools.php');
            echo "There are {$num} results.";
            // while there are new rows, print search results
            while ($row = $r->fetch_array(MYSQLI_ASSOC))
            {       
                if(!empty($row['proRegistration']))
                {
                    $proReg = true;
                } else {
                    $proReg = false;
                }

                echo "<div class='search-result-box'><fieldset>
                <h3>{$row['company_name']}</h3>
                <p>
                    <b>{$row['trade']}</b><br>
                    Name: {$row['first_name']} {$row['last_name']}<br>
                    Hourly Rate: £{$row['hourlyRate']}<br>
                    Average Rating: ".calcAvgRating($dbc, $row['user_id'])."<br>";
                    if ($proReg) {
                       echo "<b class='proReg'>Is Registered Pro</b><br>";
                    }
                echo "Available From: {$row['availableDateFrom']} | Up To: {$row['availableDateTo']}
                </p>     
                <button class='button profileButton'>
                <strong><a href='{$url}profile.php?user_id={$row['user_id']}'>View Profile</a></strong>
                </button>
                </fieldset></div>";                          
            }       
        }
        else
        {
            echo 'There are no results matching your search!';
        }
    }   
?>

<?php
    // calculate total pages with results
    /*$q2 = "SELECT COUNT(fk_user_id) AS total FROM tradesmen";
    $result = $dbc->query($q2);
    $row = $result->fetch_assoc(); */
    //$total_pages = ceil($num / $results_per_page); 
    //echo "<a href='search.php?page=0&search=".$search."'>0</a>";
    if (isset($total_pages))
    {
        for ($i=1; $i<=$total_pages; $i++) 
        {  // print links for all pages
            echo "<a href='search.php?page=".$i."&search=".$search."' class='pageNums'";
            if ($i==$page) { echo " id='curPage'"; }
            echo ">".$i."</a> "; 
        }
    }
?>

<?php
    include ("includes/footer.html");
?>