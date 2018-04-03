<html>
<head>
    <title><?php echo SITE_NAME; ?></title>
    <meta name="description" content="<?php echo SITE_CATCHPHRASE; ?>">
    <meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" href="./favicon.ico">
    
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="./styles/main.css">
    
    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    <!-- jQuery UI TouchPinch Plugin-->
    <script src="./scripts/jquery.ui.touch-punch.min.js"></script>
    <!-- jQuery cookies plugin -->
    <script src="./scripts/jquery.cookie.js"></script>
</head>
<body>
<div id="header">
    <a href="."><h1 id="heading"><?php echo SITE_NAME; ?></h1></a>
    <?php
    if (isset($_SESSION["user"])) {
    ?>
    <div class="headerButton">
        <span id="logOutButton" title="Logged in as <?php echo Get_Username($_SESSION['user']); ?>"><a href="?action=logout">Log Out</a></span>
    </div>
    
    <div class="headerButton">
        <span id="newGoalButton" class="clickable">New Goal</span>
        <div id="newGoalForm">
            <span id="cancelNewGoalButton" class="clickable">Cancel</span>
            <?php echo Return_Add_Checkpoint_Form (0, ""); ?>
        </div>
    </div>
    
    <?php
    } else {
    ?>
    <div class="headerButton">
        <span id="loginButton" class="clickable">Log in</span>
        <div id="loginForm">
            <span id="cancelLoginButton" class="clickable">Cancel</span>
            <form name="logIn" method="post" action="?action=login" onsubmit="return validateLogin()">
            Username:<br />
            <span id="nameMessage" class="hidden"><br /></span>
            <input type="text" id="nameInput" name="name" value="" onclick="this.select()" length="29">
            <br />
            Password:<br />
            <span id="pwMessage" class="hidden"><br /></span>
            <input type="password" id="pwInput" name="pw" value="" onclick="this.select()">
            <p><input type="submit" value="Submit"></p>
            </form>
        </div>
    </div>
    <div class="headerButton">
        <span id="createAccountButton" class="clickable">Create Account</span>
        <div id="createAccountForm">
            <span id="cancelCreateAccountButton" class="clickable">Cancel</span>
            <form name="createAccount" method="post" action="?action=createaccount">
            Username:<br />
            <span id="createAccountUsernameMesssage" class="hidden"><br /></span>
            <input type="text" id="createAccountUsername" name="name" value="" onclick="this.select()" length="29">
            <br />
            Password:<br />
            <span id="createAccountPasswordMesssage" class="hidden"><br /></span>
            <input type="password" id="createAccountPassword" name="pw" value="" onclick="this.select()">
            <p><input type="submit" value="Submit"></p>
            </form>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<div id="page">