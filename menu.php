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

function executeSelect()
{
    $mysqli = connectDB();

    $selectStmt = "SELECT SORTORDER, PARENT, HREF, TARGET, DISPLAY
                    FROM MENU M1
                    WHERE PARENT =0
                    UNION
                    SELECT CONCAT( P.SORTORDER, '.', C.SORTORDER ) , C.PARENT, C.HREF, C.TARGET, C.DISPLAY
                    FROM MENU C
                    INNER JOIN MENU P ON C.parent = P.id
                    WHERE C.PARENT <>0
                    ORDER BY 1";

    if (!($stmt = $mysqli->prepare($selectStmt))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error . $selectStmt;
    }
    /* Execute Statement */
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $out_sortorder = NULL;
    $out_parent = NULL;
    $out_href = NULL;
    $out_target = NULL;
    $out_display = NULL;
    $wasPriorRecordParent = true;
    if (!$stmt->bind_result($out_sortorder, $out_parent, $out_href, $out_target, $out_display)) {
        echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }
    echo "<ul>\n";
    while ($stmt->fetch()) {
        if ($out_parent == 0)
        {
            if ($wasPriorRecordParent == false)
            {
                echo "</ul>\n</li>\n";
            }
            echo "<li><a href=" . $out_href . " target=" . $out_target . ">" . $out_display . "</a>\n";
            $wasPriorRecordParent = true;
        }
        else
        {
            if ($wasPriorRecordParent == true)
            {
                echo "<ul>\n";
            }
            echo "<li><a href=" . $out_href . " target=" . $out_target . ">" . $out_display . "</a></li>\n";
            $wasPriorRecordParent = false;
        }
    }
    if ($wasPriorRecordParent == false)
    {
        echo "</ul>\n";
    }
    echo "</li></ul>\n";
    /* explicit close recommended */
    $stmt->close();
}
    executeSelect();
?>