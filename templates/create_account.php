<div class="infoPage">
<?php
if (isset($_POST["name"])) {
    $name = str_replace("\\", "&#34;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["name"]))));
    $password = $_POST["pw"];
    $hashed_pw = crypt($password, $name);
    
    $insert_sql = "INSERT INTO user (name, password, created_date) ";
    $insert_sql .= "VALUES (?, ?, " . time() . ");";
    $result = query($insert_sql, array($name, $hashed_pw), false);
    if ($result && $result->rowCount() > 0) {
        echo "<h2>Account created successfully!</h2>";
        Show_Login_Form($name);
        //header('Location: .');
    } else {
        echo "Error: " . $insert_sql;
    }
} else {
    echo "<strong>No Username specified!</strong>";
}
?>
</div>