<?php
    $page_title = 'Welcome to Rater';
    session_start();
    if (isset($_SESSION["userObj"]))
    {
        include ('includes/home_header.php');
    }
    else
    {
        include ('includes/header.php');
    }
?>

<h1>Welcome to Rater</h1>
<p>
    Find a tradesman:
</p>

<form action='search.php' method="POST">
<input type="text" name="mySearch" placeholder="search">
<button type="submit" name="submit-search">Search</button>

<?php
    include ("includes/footer.html")
?>
