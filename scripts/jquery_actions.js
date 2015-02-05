$( document ).ready(function() {
    // Login form
    $("#loginButton").hide();
    $("#loginButton").click(function (event) {
        if ($("#loginForm").is(":visible")) {
            $("#loginForm").slideUp();
        } else {
            $("#loginForm").slideDown();
        }
    });
    
    // Show/hide milestone details
    $(".details").hide();
    $(".milestone_title").click(function (event) {
        var thisID = $(this).attr('id');
        var detailsID = "#" + thisID + "_details";
        $(detailsID).toggle("fast");
    });
 
});