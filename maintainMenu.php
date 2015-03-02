<?php
ini_set('display_errors', 'On');

function connectDB()
{
    include 'storedInfo.php';
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu","thomkevi-db", $myPassword, "thomkevi-db");
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } else {
        /* echo "Connection worked!<br>"; */
    }
    return $mysqli;
}

if (isset($_POST['deleteMenuID']))
{
    $mysqli = connectDB();
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("DELETE FROM MENU WHERE ID = ?"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $menu_id = $_POST['deleteMenuID'];
    if (!$stmt->bind_param("i", $menu_id)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    /* Execute Statement */
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    /* explicit close recommended */
    $stmt->close();
}

if (isset($_POST['action']) && $_POST['action'] == 'addMenu' ) {

    $mysqli = connectDB();
    /* Prepared statement, stage 1: prepare */
    if (!($stmt = $mysqli->prepare("INSERT INTO MENU (SORTORDER, PARENT, HREF, TARGET, DISPLAY) VALUES (?, ?, ?, ?, ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    /* Prepared statement, stage 2: bind and execute */
    $menuSort = $_POST['menuSort'];
    $menuParent = $_POST['menuParent'];
    $menuHREF = $_POST['menuHREF'];
    $menuTarget = $_POST['menuTarget'];
    $menuDisplay = $_POST['menuDisplay'];

    if (!$stmt->bind_param("iisss", $menuSort, $menuParent, $menuHREF, $menuTarget, $menuDisplay)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    /* Execute Statement */
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    /* explicit close recommended */
    $stmt->close();
}

function executeSelect()
{
    $mysqli = connectDB();

    $selectStmt = "SELECT id, sortorder, parent, href, target, display FROM MENU ORDER BY sortorder";

    if (isset($_POST['filter']) && $_POST['filter'] != 'all')
    {
        $selectStmt = $selectStmt . " WHERE category = ?";
    }

    if (!($stmt = $mysqli->prepare($selectStmt))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . $selectStmt;
    }

    if (isset($_POST['filter']) && $_POST['filter'] != 'all') {
        $filter = $_POST['filter'];
        if (!$stmt->bind_param("s", $filter)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    /* Execute Statement */
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $out_id = NULL;
    $out_sortorder = NULL;
    $out_parent = NULL;
    $out_href = NULL;
    $out_target = NULL;
    $out_display = NULL;
    if (!$stmt->bind_result($out_id, $out_sortorder, $out_parent, $out_href, $out_target, $out_display)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    while ($stmt->fetch()) {
        if ($out_parent == 0)
        {
            $out_parent = "";
        }
        echo "<tr><td>$out_id<td>$out_sortorder<td>$out_parent<td>$out_href<td>$out_target<td>$out_display";
        $clickAction = "{deleteMenuID: '$out_id'}";
        echo "<td><input type='submit' value='Delete' " . 'onclick="updateMenu(' . $clickAction . ')">';
    }

    /* explicit close recommended */
    $stmt->close();
}

function loadFilter() {
    $mysqli = connectDB();

    if (!($stmt = $mysqli->prepare("SELECT distinct id FROM MENU WHERE PARENT = 0"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

    $out_id = NULL;

    if (!$stmt->bind_result($out_id)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    echo "<option value='all' selected>none</option>";

    while ($stmt->fetch()) {
        echo "<option value='$out_id'>$out_id</option>";
    }

    /* explicit close recommended */
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <title>Maintain Menu</title>
    <script>
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
    </script>
</head>
<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Maintain Menu Items</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID
                    <th>Sort Order
                    <th>Parent ID
                    <th>HREF
                    <th>Target
                    <th>Display
                    <th>
                </tr>
                </thead>
                <tbody>
                <?php

                executeSelect();

                ?>
                </tbody>
                    <tfoot>
                        <tr>
                            <form action="maintainMenu.php" method="POST">
                                <td>
                                <td><input type="text" name="menuSort">
                                <td>
                                    <select name="menuParent">
                                        <?php
                                        loadFilter();
                                        ?>
                                    </select>
                                <td><input type="text" name="menuHREF">
                                <td>
                                    <select name="menuTarget">
                                        <option value="_self">_self</option>
                                        <option value="_blank">_blank</option>
                                        <option value="_parent">_parent</option>
                                        <option value="_top">_top</option>
                                    </select>
                                <td><input type="text" name="menuDisplay">
                                <td><input type="hidden" name="action" value="addMenu">
                                    <input type="submit" value="Add">
                            </form>
                        </tr>
                    </tfoot>

            </table>

        </div>
    </div>
</div>
</body>
</html>