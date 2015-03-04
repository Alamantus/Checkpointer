<div class="infoPage">
<?php
if (isset($_POST["name"])) {
    $name = str_replace("\\", "&#34;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["name"]))));
    $password = $_POST["pw"];
    $hashed_pw = crypt($password, $name);
    
    $insert_sql = "INSERT INTO user (name, password, created_date) ";
    $insert_sql .= "VALUES ('" . $name . "', '" . $hashed_pw . "', " . time() . ");";
    if (query($insert_sql)) {
        echo "<h2>Account created successfully!</h2>";
        Show_Login_Form($name);
        //header('Location: .');
    } else {
        echo "Error: " . $insert_sql . "<br>" . mysqli_error(connection());
    }
} else {
    echo "<strong>No Username specified!</strong>";
}
?>
</div>