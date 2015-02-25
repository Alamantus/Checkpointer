$( document ).ready(function() {
    // Login form
    $("#loginForm").hide();
    $("#loginButton").click(function (event) {
        $("#loginForm").slideDown("fast");
        $("#nameInput").focus();
    });
    $("#cancelLoginButton").click(function (event) {
        $("#loginForm").slideUp("fast");
    });
    $("#nameInput").focusout(function () {
        if ($("#loginForm").is(":visible")) {
            validateLoginName();
        }
    });
    $("#pwInput").focusout(function () {
        if ($("#loginForm").is(":visible")) {
            validateLoginPw();
        }
    });
    
    // New Goal form
    $("#newGoalForm").hide();
    $("#newGoalButton").click(function (event) {
        $("#newGoalForm").slideDown("fast");
        $("#newTitleInput").focus();
    });
    $("#cancelNewGoalButton").click(function (event) {
        $("#newGoalForm").slideUp("fast");
    });
    
    // New Checkpoint Form Validation
    $(".checkpointTitleInput").focusout(function () {
        if ($(this).is(":visible")) {
            validateCheckpoint($(this).attr("parentID"));
        }
    });
    
    // Show/hide checkpoint details
    $(".checkpoint_details").hide();
    $(".checkpoint_title").click(function (event) {
        var thisID = $(this).attr('id');
        var detailsID = "#" + thisID + "_details";
        $(detailsID).toggle("fast");
    });
    
    // Show/hide add checkpoint
    $(".addCheckpointForm").hide();
    $(".addCheckpointButton").click(function (event) {
        var thisButtonID = $(this).attr('id');
        var checkpointID = "#" + thisButtonID + "_form";
        $(checkpointID).toggle("fast");
    });
    
});