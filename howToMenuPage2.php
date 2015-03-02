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
            <h1>Database</h1>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <p>
                The database table to implement our dynamic menu must be designed to support the menu structure as well
                as the attributes of the &#60;a&#62; element we wish to use or make available to use. The menu itself
                will
                consist of one or more parent menu items and zero or more child menu items below each parent. Links
                themselves will implement the href and target attributes and include a database column to hold the
                named target destination.
            </p>

            <p>
                The following describes the database table structure including the column name and description:
            </p>
            <ol>
                <li>ID – Auto incrementing field and primary key for the table.</li>
                <li> SORTORDER – The display order for the menu items. The values in this column do not have to be
                    entered or unique, but if they are not the the order menu items are displayed is not guaranteed.
                </li>
                <li>PARENT – If populated, it refers to the ID of a parent menu item. Only child links will have a value
                    in this field.
                </li>
                <li>HREF – This attribute describes the URL or URL fragment of the hyperlinked resource.</li>
                <li>TARGET – The is the &#60;a&#62; attribute that specifies where to display the linked resource.
                    Options
                    include _self, _blank, _parent, and _top.
                </li>
                <li> DISPLAY – This is the value that will be wrapped between &#60;a&#62; and &#60;&#47;a&#62; tags.
                </li>
            </ol>

            <h3>Creating the Database
            </h3>

            <p>
                This portion of the how to assumes that you have access to a mysql database and web front end
                for executing DDL.
            </p>
            <h4>Option 1) Create the table and fields using a GUI editor.</h4>

            <p>
                Log into your mysql database GUI administration tool and create a database table titled MENU with the
                fields and attributes described in the screen captures below.
            </p>
            <img alt="Database Image" src="db_final.GIF"
                 style="padding:1px; border:1px solid #0f0f0f; background-color:#e0e0e0;">
            <br>
            <h4>Option 2) Run a script on the command line or in the GUI editor.</h4>
            <pre>


<code>    --
    -- Table structure for table 'MENU'
    --

    DROP TABLE IF EXISTS 'MENU';
    CREATE TABLE IF NOT EXISTS 'MENU' (
    'ID' int(11) NOT NULL AUTO_INCREMENT,
    'SORTORDER' int(11) DEFAULT NULL,
    'PARENT' int(11) DEFAULT NULL,
    'HREF' varchar(255) DEFAULT NULL,
    'TARGET' varchar(7) DEFAULT NULL,
    'DISPLAY' varchar(255) DEFAULT NULL,
    PRIMARY KEY ('ID')
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;</code>
            </pre>
            <p>
                Continue on to the next page for instructions on creating the web front end to populate the MENU
                table.
            </p>


            <p class="text-center">
                <a href="howToMenuPage3.php" class="btn btn-success btn-large">Next Page</a>
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