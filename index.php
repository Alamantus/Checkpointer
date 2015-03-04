<?php
require_once('config.php');
require_once('funct.php');
require_once('outputs.php');
session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$action = isset($_GET["action"]) ? $_GET["action"] : false;
$page = isset($_GET["page"]) ? $_GET["page"] : false;
$message = isset($_POST["message"]) ? $_POST["message"] : "";
$view_user = isset($_GET["user"]) ? $_GET["user"] : false;

include('header.php');

if ($page == "about") {
    include_once('templates/about.php');
}
elseif ($page == "terms") {
    include_once('templates/terms.php');
}
elseif ($action == "add") {
    include_once('templates/add.php');
}
elseif ($action == "edit") {
    include_once('templates/edit.php');
}
elseif ($action == "login") {
    include_once('templates/login.php');
}
elseif ($action == "logout") {
    include_once('templates/logout.php');
}
elseif ($action == "createaccount") {
    include_once('templates/create_account.php');
}
elseif ($view_user != false) {
    $user_id = Get_User_Id($view_user);
    if ($user_id == "No User") {
        echo "<div class='infoPage'><strong>There is nobody with the username \"". $view_user ."\" on Checkpointer.</strong></div>";
    } else {
        echo "<div class='infoPage' style='width: 90%; max-width: 90%'><h2>". Get_Username($user_id) ."'s Goals</h2>";    //Re-calculate to get username as it was entered in database.
        Output_User_Checkpoints($user_id, true);
        echo "</div>";
    }
}
elseif (!$action && $view_user == false) {
    if (!isset($_SESSION['user'])) {
?>
        <div  class="infoPage">
        <h2>Reach your goals one checkpoint at a time!</h2>
        <p><strong>"How do you eat an elephant?"</strong><br />
        <em>"One bite at a time."</em></p>
        <p>Dividing up big projects into smaller tasks makes it easier to finish by keeping you from getting discouraged along the way. Checkpointer makes it easy for you to break down your your goals into easily manageable checkpoints that you can complete one at a time. As you work toward your goal, you can see all the checkpoints you've reached along the way to encourage you to keep going!</p>
        <p style="text-align:center;"><strong><em>Got an overwhelming project ahead of you?<br />Get started with Checkpointer now so you can start working toward completion today!</em></strong></p>
        </div>
<?php
        //Show_Login_Form("");
    }
    else {
        // Update Last Active
        $update_sql = "UPDATE user SET last_active= " . time() . " WHERE id=" . $current_user . ";";
        if (query($update_sql)) {
            //success!
        } else {
            echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
        }
        
        // And show checkpoints
        Output_User_Checkpoints($current_user);
    } //end else to show checkpoints
}

include('footer.php');
?>