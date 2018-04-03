<div class="infoPage">
<?php
if (isset($_POST["title"])) {
    $title = $_POST["title"];
    $text = $_POST["text"];
    $text = str_replace("\n", "<br />", $text);
    $parent = isset($_POST["parent"]) ? $_POST["parent"] : 0;
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : -1;
    $status = isset($_POST["status"]) ? $_POST["status"] : 1;
    $owner = isset($_SESSION["user"]) ? $_SESSION["user"] : 0;
    $parent_type = isset($_POST["parentType"]) ? $_POST["parentType"] : "";
    
    $insert_sql = "INSERT INTO checkpoint (title, text, parent, sort, status, owner, created_date) ";
    $insert_sql .= "VALUES (?, ?, ?, ?, ?, ?, " . time() . ");";
    $cleaned_title = htmlspecialchars($title);
    $cleaned_text = htmlspecialchars($text);
    $params = array($cleaned_title, $cleaned_text);
    if (ENCRYPT_DATA) {
        $params = array_map(function($data) {
            return easy_crypt('encrypt', $data);
        }, $params);
    }
    $params[] = $parent;
    $params[] = $sort;
    $params[] = $status;
    $params[] = $owner;
    $result = query($insert_sql, $params, false);
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