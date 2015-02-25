<html>
<head>
    <title><?php echo SITE_NAME; ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>
<div id="header">
    <a href="."><h1 id="heading">Checkpointer</h1></a>
    <?php
    if (isset($_SESSION["user"])) {
    ?>
    <div class="headerButton">
        <span id="logOutButton"><a href="?action=logout">Log Out</a></span>
    </div>
    
    <div class="headerButton">
        <span id="newGoalButton" class="clickable">New Goal</span>
        <div id="newGoalForm">
            <span id="cancelNewGoalButton" class="clickable">Cancel</span>
            <form method="post" action="?action=add" onsubmit="return validateMilestone()">
            <p>Title:<br />
            <span id="newTitleMessage" class="hidden"><br /></span>
            <input id="newTitleInput" type="text" name="title" value="" length="199"></p>
            <p>Details:<br />
            <textarea rows="4" name="text"></textarea></p>
            <p>Sort Order:<br />
            <input type="text" name="sort" value="0" length="3"></p>
            <input type="hidden" name="parent" value="0">
            <p><input type="submit" value="Submit"></p>
            </form>
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
            <br />
            <input type="submit" value="Submit" class="hidden">
            </form>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<div id="page">