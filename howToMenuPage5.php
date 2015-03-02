<?php
ini_set('display_errors', 'On');

function displayMenu()
{
    include 'menu.php';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- menu -->
    <link href="css/menu.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <title>How To - Database Driven Menu</title>
    <script>
    </script>
</head>
<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<div class="container">
    <div class="row">
        <div class="navbar">
            <div class="navbar-inner">
                <?php
                displayMenu();
                ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h1>Conclusion</h1>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <p>
                Once all of the pieces are put together, you will be able to easily deploy a menu with a parent
                and child structure.  It will also be easy to maintain from a user front end without having to ever
                touch the code behind unless you want to add features or change the styling.  This makes a database
                driven menu system very flexible in that anyone that is granted access to the administration page can
                make updates and not necessarily need to understand much about programming or web development.  To
                further extend this idea beyond the menu, you could also create a fully database driven website by
                building an interface that allows non-programmers to update content as well without touching code.
            </p>
            <p>
                I hope you found this How To useful.  The How To code as well as the menu.php and maintainMenu.php files
                are available in <a href="https://github.com/thompsonkt/cs290-how_to" target="_blank">github</a> and free for your use.  Bootstrap was used for the styling of this guide. Please
                read through and follow the license requirements if you intend to include the Bootstrap code as well.
            </p>
            <br>
            <br>

            <p class="text-center">
                <a href="howToMenuPage1.php" class="btn btn-success btn-large">Page 1</a>
            </p>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <h4>Navigation</h4>
            <a href="howToMenuPage1.php">Introduction</a>
            <br><a href="howToMenuPage2.php">Database</a>
            <br><a href="howToMenuPage3.php">Menu Front End</a>
            <br><a href="howToMenuPage4.php">Menu Code</a>
            <br><a href="howToMenuPage5.php">Conclusion</a>
        </div>
    </div>
</div>
</body>
</html>