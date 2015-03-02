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
            <h1>The Menu Code</h1>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <p>
                This section covers the code necessary to query the menu structure from the database, format and display
                the menu to the user.
            </p>
            <br>
            <h3>The Pieces</h3>
            <ol>
                <li>The menu function call</li>
                <li>menu.php
                <ol>
                    <li>Select statement</li>
                    <li>Building the menu html</li>
                    <li>Styling the menu with CSS</li>
                </ol>
                </li>
            </ol>
            <br>
            <h4>The menu function call</h4>
            <p>
                Each page that you want to display the menu will need to include the menu.php file and make a call
                to the function in that page which will output the menu html.
            </p>
            <pre>
<code>&lt;?php
    ini_set('display_errors', 'On');

    function displayMenu()
    {
        include 'menu.php';
    }

?&gt;</code></pre>

            <br>


            <h4>menu.php - Select statement</h4>
            <p>
                Within the menu.php code, we use the same set of steps seen before to connect to the database
                and run a query.  This select statement ensures that the rows are ordered such that child menu items directly
                follow the parent.
            </p>
<pre>
<code>function executeSelect()
    {
    $mysqli = connectDB();

    $selectStmt = "SELECT SORTORDER, PARENT, HREF, TARGET, DISPLAY
    FROM MENU M1
    WHERE PARENT =0
    UNION
    SELECT CONCAT( P.SORTORDER, '.', C.SORTORDER ) , C.PARENT, C.HREF, C.TARGET, C.DISPLAY
    FROM MENU C
    INNER JOIN MENU P ON C.parent = P.id
    WHERE C.PARENT &lt;&gt;0
    ORDER BY 1";

    if (!($stmt = $mysqli-&gt;prepare($selectStmt))) {
    echo "Prepare failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error . $selectStmt;
    }

    /* Execute Statement */
    if (!$stmt-&gt;execute()) {
    echo "Execute failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error;
    }</code>
</pre>
            <h4>menu.php - Building the menu html</h4>
            <p>The results from the SELECT statement are looped building the &lt;a&gt; tags using the values specified on
                the MENU database table for href, target and display.  Parent menu items will appear at the top level and
                menu items associated with a parent will appear as sub menu items to their parent due to the CSS styling
                that will be covered after this step.
            </p>
<pre>
<code>
    $out_sortorder = NULL;
    $out_parent = NULL;
    $out_href = NULL;
    $out_target = NULL;
    $out_display = NULL;
    $wasPriorRecordParent = true;
    if (!$stmt-&gt;bind_result($out_sortorder, $out_parent, $out_href, $out_target, $out_display)) {
        echo "Binding output parameters failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
    }
    echo "&lt;ul&gt;\n";
    while ($stmt-&gt;fetch()) {
        if ($out_parent == 0)
        {
            if ($wasPriorRecordParent == false)
            {
                echo "&lt;/ul&gt;\n&lt;/li&gt;\n";
            }
            echo "&lt;li&gt;&lt;a href=" . $out_href . " target=" . $out_target . "&gt;" . $out_display . "&lt;/a&gt;\n";
            $wasPriorRecordParent = true;
        }
        else
        {
            if ($wasPriorRecordParent == true)
            {
                echo "&lt;ul&gt;\n";
            }
            echo "&lt;li&gt;&lt;a href=" . $out_href . " target=" . $out_target . "&gt;" . $out_display . "&lt;/a&gt;&lt;/li&gt;\n";
            $wasPriorRecordParent = false;
            }
        }
        if ($wasPriorRecordParent == false)
        {
            echo "&lt;/ul&gt;\n";
        }
        echo "&lt;/li&gt;&lt;/ul&gt;\n";
    /* explicit close recommended */
    $stmt-&gt;close();
    }

    executeSelect();

    ?&gt;</code>
</pre>
            <br>
            <h4>menu.css - Styling the menu with CSS</h4>
            <p>
                The last piece is the CSS styling which is required to beautify the menu and also set the child menu items
                to display only when the user hovers over the parent.  This is accomplished by setting the <code>ul li ul</code>
                to <code>display: none;</code> which hides the element until the user hovers then changing the display
                from <code>none</code> to <code>block</code>.
            </p>
            <p>
                First add a link to the menu.css stylesheet in each page you wish to display the menu.
            </p>
<pre><code>&lt;link href="css/menu.css" rel="stylesheet"&gt;</code></pre>
            <p>
                The contents of menu.css look like so:
            </p>
<pre><code>
            ul{
                padding: 0;
                list-style: none;
            }
            ul li{
                float: left;
                width: 200px;
                text-align: center;
            }
            ul li a{
                display: block;
                padding: 5px 10px;
                color: #333;
                background: #f2f2f2;
                text-decoration: none;
            }
            ul li a:hover{
                color: #fff;
                background: #939393;
            }
            ul li ul{
                display: none;
            }
            ul li:hover ul{
                display: block; /* display the dropdown */
            }
</code></pre>
            <br>

            <p class="text-center">
                <a href="howToMenuPage5.php" class="btn btn-success btn-large">Next Page</a>
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