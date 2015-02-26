<?php
if (isset($_POST["title"])) {
    $title = str_replace("\\", "&#34;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["title"]))));
    $text = str_replace("\\", "&#92;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["text"]))));
    $parent = isset($_POST["parent"]) ? $_POST["parent"] : 0;
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
    $status = isset($_POST["status"]) ? $_POST["status"] : 1;
    $owner = isset($_SESSION["user"]) ? $_SESSION["user"] : 0;
    
    $insert_sql = "INSERT INTO checkpoint (title, text, parent, sort, status, owner, created_date) ";
    $insert_sql .= "VALUES ('" . $title . "', '" . $text . "', " . $parent . ", " . $sort . ", " . $status . ", " . $owner . ", " . time() . ");";
    if (query($insert_sql)) {
        echo "New record created successfully";
        header('Location: .');
    } else {
        echo "Error: " . $insert_sql . "<br>" . mysqli_error(connection());
    }
} else {
    echo "No Title!";
}
?>