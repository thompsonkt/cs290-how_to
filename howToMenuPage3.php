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
            <h1>Web Front End To Menu System</h1>
        </div>
        <div class="col-md-2"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <p>
                This section covers the components necessary to build a front end to display, add and delete menu items.
            </p>
            <br>
            <h3>Components</h3>
            <ol>
                <li>HTML Table</li>
                <li>PHP Calls and MYSQLI statements
                    <ol>
                        <li>Execute Select</li>
                        <li>Load Filter</li>
                        <li>Add Menu Item</li>
                        <li>Delete Menu Item</li>
                    </ol>
                </li>
                <li>Javascript</li>
                <li>Final Product</li>
            </ol>
            <br>
            <h4>HTML Table</h4>
            <p>
                This provides the user interface for reviewing entries that already exist in the table, a form to add
                new entries and an option to delete menu items no longer needed or created in error.
            </p>
            <p>
                The table header describes provides a title for each of the elements to be displayed
                from the MENU database table including the ID, Sort Order, Parent ID, HREF, Target and Display.
            </p>
            <pre>
<code>&lt;table class="table table-bordered"&gt;
    &lt;thead&gt;
    &lt;tr&gt;
    &lt;th&gt;ID
    &lt;th&gt;Sort Order
    &lt;th&gt;Parent
    &lt;th&gt;HREF
    &lt;th&gt;Target
    &lt;th&gt;Display
    &lt;th&gt;
    &lt;/tr&gt;
    &lt;/thead&gt;</code></pre>
            <p>
                The table body calls the php executeSelect() function which will query the menu table
                and output a table row for each record returned.  The function will be covered in more detail
                further on in this section.
            </p>
<pre><code>        &lt;tbody&gt;
        &lt;?php
        executeSelect();
        ?&gt;
        &lt;/tbody&gt;</code></pre>
            <p>
                The table footer provides a form to add a menu item to the MENU database table.
                Text inputs allow the user to define the menu items sort order, href and display values.
                A drop down is provided to identify the parent if the menu item is to be a child menu item.
                The drop down is populated by the php loadFilter() function which will be reviewed further on in this page.
                And a drop down is provided to select the target window context for the link.
            </p>
            <pre>
<code>        &lt;tfoot&gt;
        &lt;tr&gt;
            &lt;form action="maintainMenu.php" method="POST"&gt;
                &lt;td&gt;
                &lt;td&gt;&lt;input type="text" name="menuSort"&gt;
                &lt;td&gt;
                    &lt;select name="menuParent"&gt;
                        &lt;?php
                        loadFilter();
                        ?&gt;
                    &lt;/select&gt;
                &lt;td&gt;&lt;input type="text" name="menuHREF"&gt;
                &lt;td&gt;
                    &lt;select name="menuTarget"&gt;
                        &lt;option value="_self"&gt;_self&lt;/option&gt;
                        &lt;option value="_blank"&gt;_blank&lt;/option&gt;
                        &lt;option value="_parent"&gt;_parent&lt;/option&gt;
                        &lt;option value="_top"&gt;_top&lt;/option&gt;
                    &lt;/select&gt;
                &lt;td&gt;&lt;input type="text" name="menuDisplay"&gt;
                &lt;td&gt;&lt;input type="hidden" name="action" value="addMenu"&gt;
                    &lt;input type="submit" value="Add"&gt;
            &lt;/form&gt;
        &lt;/tr&gt;
        &lt;/tfoot&gt;
    &lt;/table&gt;</code>
            </pre>
            <br>


            <h4>PHP Calls and MYSQLI statements</h4>
            <h5>executeSelect() function</h5>
            <p>
                The executeSelect() function makes a connection to the database,
                defines the SELECT statement to be run against the database MENU table, builds then executes the
                prepared statement.
            </p>
<pre>
<code>function executeSelect()
    {
    $mysqli = connectDB();

    $selectStmt = "SELECT id, sortorder, parent, href, target, display FROM MENU ORDER BY sortorder";

    if (isset($_POST['filter']) && $_POST['filter'] != 'all')
    {
    $selectStmt = $selectStmt . " WHERE category = ?";
    }

    if (!($stmt = $mysqli-&gt;prepare($selectStmt))) {
    echo "Prepare failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error . $selectStmt;
    }

    if (isset($_POST['filter']) && $_POST['filter'] != 'all') {
    $filter = $_POST['filter'];
    if (!$stmt-&gt;bind_param("s", $filter)) {
    echo "Binding parameters failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
    }
    }

    /* Execute Statement */
    if (!$stmt-&gt;execute()) {
    echo "Execute failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error;
    }</code>
</pre>
            <p>The columns returned by the select statement are bound to php variables then rows
                are fetched from the database until all rows have been retrieved.  The php function builds a table row
                &lt;tr&gt; for each row returned by the database to output the results to the HTML table.  A delete button
                is added to the last column which calls a javascript function that will be passed the menu items ID.
                Finally, the connection to the database is closed.
            </p>
<pre>
<code>
    $out_id = NULL;
    $out_sortorder = NULL;
    $out_parent = NULL;
    $out_href = NULL;
    $out_target = NULL;
    $out_display = NULL;
    if (!$stmt-&gt;bind_result($out_id, $out_sortorder, $out_parent, $out_href, $out_target, $out_display)) {
    echo "Binding output parameters failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
    }

    while ($stmt-&gt;fetch()) {
    if ($out_parent == 0)
    {
    $out_parent = "";
    }
    echo "&lt;tr&gt;&lt;td&gt;$out_id&lt;td&gt;$out_sortorder&lt;td&gt;$out_parent&lt;td&gt;$out_href&lt;td&gt;$out_target&lt;td&gt;$out_display";
            $clickAction = "{deleteMenuID: '$out_id'}";
            echo "&lt;td&gt;&lt;input type='submit' value='Delete' " . 'onclick="updateMenu(' . $clickAction . ')"&gt;';
            }

            /* explicit close recommended */
            $stmt-&gt;close();
            }</code>
</pre>
            <br>
            <h5>loadFilter() function</h5>
            <p>
                The loadFilter() function is a simple php function to build a &lt;select&gt; that the user will use
                when adding new child menu items to select the Parent ID of the child.  It runs a select statement against
                the MENU table limiting results to only those rows that are not child menu items.
            </p>
<pre><code>
        function loadFilter() {
        $mysqli = connectDB();

        if (!($stmt = $mysqli-&gt;prepare("SELECT distinct id FROM MENU WHERE PARENT = 0"))) {
        echo "Prepare failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error;
        }

        if (!$stmt-&gt;execute()) {
        echo "Execute failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error;
        }

        $out_id = NULL;

        if (!$stmt-&gt;bind_result($out_id)) {
        echo "Binding output parameters failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
        }

        echo "&lt;option value='all' selected&gt;none&lt;/option&gt;";

        while ($stmt-&gt;fetch()) {
        echo "&lt;option value='$out_id'&gt;$out_id&lt;/option&gt;";
        }

        /* explicit close recommended */
        $stmt-&gt;close();
        }</code></pre>
            <br>
            <h5>Add Menu Item</h5>
            <p>
                When the user clicks the Add button, the form posts the data supplied by the user which the php code below
                acts on to INSERT a new record into the MENU table.  The steps are similar to the process for running a SELECT
                including connecting the database, preparing a statement, passing the form data to php variables, binding 
                the variables to the prepared statement, executing the statement and closing the connection.
            </p>
<pre><code>        if (isset($_POST['action']) && $_POST['action'] == 'addMenu' ) {

        $mysqli = connectDB();
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli-&gt;prepare("INSERT INTO MENU (SORTORDER, PARENT, HREF, TARGET, DISPLAY) VALUES (?, ?, ?, ?, ?)"))) {
        echo "Prepare failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error;
        }

        /* Prepared statement, stage 2: bind and execute */
        $menuSort = $_POST['menuSort'];
        $menuParent = $_POST['menuParent'];
        $menuHREF = $_POST['menuHREF'];
        $menuTarget = $_POST['menuTarget'];
        $menuDisplay = $_POST['menuDisplay'];

        if (!$stmt-&gt;bind_param("iisss", $menuSort, $menuParent, $menuHREF, $menuTarget, $menuDisplay)) {
        echo "Binding parameters failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
        }

        /* Execute Statement */
        if (!$stmt-&gt;execute()) {
        echo "Execute failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
        }

        /* explicit close recommended */
        $stmt-&gt;close();
        }</code></pre>
            <br>
            <h5>Delete Menu Item</h5>
            <p>
                When the user clicks the Delete button, javascript is executed to build a form and post the ID of the menu
                item to be deleted back to the page.  The php code below runs a DELETE statement binding the ID to the prepared
                statement to delete the menu item from the MENU table.
            </p>
<pre><code>        if (isset($_POST['deleteMenuID']))
        {
        $mysqli = connectDB();
        /* Prepared statement, stage 1: prepare */
        if (!($stmt = $mysqli-&gt;prepare("DELETE FROM MENU WHERE ID = ?"))) {
        echo "Prepare failed: (" . $mysqli-&gt;errno . ") " . $mysqli-&gt;error;
        }

        $menu_id = $_POST['deleteMenuID'];
        if (!$stmt-&gt;bind_param("i", $menu_id)) {
        echo "Binding parameters failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
        }

        /* Execute Statement */
        if (!$stmt-&gt;execute()) {
        echo "Execute failed: (" . $stmt-&gt;errno . ") " . $stmt-&gt;error;
        }

        /* explicit close recommended */
        $stmt-&gt;close();
        }</code></pre>

            <h4>Javascript</h4>
            <p>
                This Javascript function takes the ID passed by the calling Delete button pressed by the user, builds
                a form and posts the ID back to the page which the php code will use to run a DELETE statement against
                the MENU table.
            </p>
            <pre>
<code>&lt;script&gt;
function updateMenu(params) {
    /* Credit: Rakesh Pai
     http://stackoverflow.com/questions/133925/javascript-post-request-like-a-form-submit
     */
    var method = "post";
    var path = "maintainMenu.php";

    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
&lt;/script&gt;</code>
            </pre>
            <p>
            </p>
            <br>
            <h4>Final Product</h4>
<p>
            <img alt="Menu Image" src="menu_final.JPG"
                 style="padding:1px; border:1px solid #0f0f0f; background-color:#e0e0e0;">
</p>

            <p class="text-center">
                <a href="howToMenuPage4.php" class="btn btn-success btn-large">Next Page</a>
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