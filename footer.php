</div>
<div id="footer">
    <div id="disclaimer" style="display:inline-block; margin: 0px 15px; font-size: 70%; max-width: 250px;">This is the alpha release of Checkpointer. Please report any bugs or issues to the <strong><a href="https://github.com/Alamantus/Checkpointer/issues" target="_blank">GitHub Issue Tracker</a></strong>.</div>
    <div id="aboutLink" class="footerLink"><a href=".?page=about">About</a></div>
    <div id="termsLink" class="footerLink"><a href=".?page=terms">Legal</a></div>
    <div id="githubLink" class="footerLink"><a href="https://github.com/Alamantus/Checkpointer" target="_blank">GitHub</a></div>
    <div id="userCount" class="footerLink" style="font-size: 10px;">
<?php
    $user_count_query_sql = "SELECT id FROM user WHERE id > 1;";
    $user_count_query = query($user_count_query_sql);
    $user_count = 0;
    while($user = fetch_assoc($user_count_query)) {
        $user_count++;
    }
    $checkpoints_count_query_sql = "SELECT id FROM checkpoint WHERE owner > 1;";
    $checkpoints_count_query = query($checkpoints_count_query_sql);
    $checkpoints_count = 0;
    while($checkpoint = fetch_assoc($checkpoints_count_query)) {
        $checkpoints_count++;
    }
    echo $user_count . " checkpointers registered<br />" . $checkpoints_count . " checkpoints created";
?>
    </div>
    <div id="copyright">&copy; 2015 <a href="http://www.alamantus.com" target="_blank">Alamantus GameDev</a></div>
</div>
<script src="scripts/validation.js"></script>
<script src="scripts/jquery_actions.js"></script>
<script src="scripts/jquery_ui_actions.js"></script>
</body>
</html>