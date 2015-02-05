<?php
if (isset($_POST['name'])) {
    $valid = Validate_User($_POST['name'], $_POST['pw']);
    if ($valid) {
        $_SESSION['user'] = Get_User_Id($_POST['name']);
        //echo "Logged in as " . Get_Username($_SESSION['user']);
        header('Location: .');
    } else {
        echo "Incorrect username/password combination";
    }
}
else {
    Show_Login_Form("");
}
?>