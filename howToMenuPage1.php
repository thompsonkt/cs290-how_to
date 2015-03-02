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
            <h1>Introduction</h1>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <p>
                This how to guide presents a concept and step by step instructions for developing, deploying and
                maintaining
                a dynamic database driven menu system using html, php, mysqli and a mysql database. Why you might ask
                would
                a developer be interested in implementing a menu system this way versus hard coding it? There are many
                reasons.
            </p>
            <br>
            <ol>
                <li>Simplicity. A database driven menu with a user interface for maintenance allows any website
                    administrator
                    regardless of their level of expertise in web development to add, change or delete menu items.
                </li>
                <li>Consistency. A single function call made on each page in a given site, will return the same menu
                    every time if that is what is desired. If a new menu item needs to be added, it is done from the
                    administration page rather than having to change code on multiple html pages.
                </li>
                <li>Re-use. Once the components are in place, a developer can easily redeploy the same system to other
                    web
                    projects.
                </li>
            </ol>
            <br>
            <p>
                The how to guide will cover the steps necessary to serve and maintain a dynamic database driven menu and
                present a live version of the user interface to maintain the menu. Continue on to the second page for
                instructions on creating the database objects.
            </p>
            <p class="text-center">
                <a href="howToMenuPage2.php" class="btn btn-success btn-large">Next Page</a>
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