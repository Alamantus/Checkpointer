</div>
<div id="footer">
    <?php
    if (isset($_SESSION["user"])) {
        echo '<p><a href="?action=logout">Log Out</a></p>';
    }
    ?>
    <span id="copyright">&copy; 2015 <a href="http://www.alamantus.com" target="_blank">Alamantus GameDev</a></span>
</div>
<script src="scripts/jquery_actions.js"></script>
</body>
</html>