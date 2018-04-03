<div class="infoPage">
<?php
if (isset($_POST["title"])) {
    $title = str_replace("\\", "&#34;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["title"]))));
    $text = str_replace("\\", "&#92;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["text"]))));
    $text = str_replace("\n", "<br />", $text);
    $parent = isset($_POST["parent"]) ? $_POST["parent"] : 0;
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : -1;
    $status = isset($_POST["status"]) ? $_POST["status"] : 1;
    $owner = isset($_SESSION["user"]) ? $_SESSION["user"] : 0;
    $parent_type = isset($_POST["parentType"]) ? $_POST["parentType"] : "";
    
    $insert_sql = "INSERT INTO checkpoint (title, text, parent, sort, status, owner, created_date) ";
    $insert_sql .= "VALUES (?, ?, " . $parent . ", " . $sort . ", " . $status . ", " . $owner . ", " . time() . ");";
    $cleaned_title = htmlspecialchars($title);
    $cleaned_text = htmlspecialchars($text);
    $result = query($insert_sql, array($cleaned_title, $cleaned_text), false);
    if ($result && $result->rowCount() > 0) {
        // echo "New record created successfully";
        if ($parent_type != "") {
            setcookie("anchor", "#" . $parent_type . $parent);
        }
        header('Location: .');
    } else {
        echo "Error: " . $insert_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
    }
} else {
    echo "No Title!";
}
?>
</div>