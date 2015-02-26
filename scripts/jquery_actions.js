$( document ).ready(function() {
    hideElements();
    
    // Login form
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
    
    // Refresh List Button
    $("#refreshButton").click(function (event) {
        var userID = $(this).children().attr('id');
        var originalText = $("#" + userID).text();
        var loadingText = "Loading...";
        var doneText = "Done!";
        if (originalText != loadingText && originalText != doneText) {
            $("#" + userID).text(loadingText);
            $.get( "ajax/get_list.php", "id=" + userID)
                .done(function (data) {
                    $("#page").html(data);
                    hideElements();
                    $("#" + userID).text(doneText);
                    setTimeout(function () {
                            $("#" + userID).fadeOut(function() {
                                $(this).text(originalText)
                            }).fadeIn();
                            //$("#" + userID).text(originalText);
                        }, 2000);
                });
        }
    });
    
    // Edit Checkpoint Button
    $(".editButton").click(function (event) {
        var thisButtonID = $(this).attr('id');
        var titleID = "#" + thisButtonID.replace("_edit", "");
        var detailsID = "#" + thisButtonID.replace("_edit", "_details");
        var detailsSnipID = detailsID + "_snip";
        
        if ($(this).text() == "Edit") {
            $(this).text("Done");
            
            var titleText = $(titleID).children(".title").text();
            var titleEditBox = "<textarea id='" + titleID.replace("#", "") + "_editbox' class='titleEditBox'>" + titleText + "</textarea>";
            // Need to fix this -- Prevents changing root-level "goals"
            var detailsText = $(detailsID).children(".checkpoint_details_text").text();
            var detailsEditBox = "<textarea id='" + detailsID.replace("#", "") + "_editbox' class='detailsEditBox'>" + detailsText + "</textarea>";
            detailsEditBox += "<br /><br /><span id=" + titleID.replace("#", "") + "_delete' class='deleteButton clickable'>Delete?</span>";
            
            $(titleID).children(".title").html(titleEditBox);
            
            $(detailsSnipID).hide("fast");
            $(detailsID).show("fast");
            $(detailsID).children(".checkpoint_details_text").html(detailsEditBox);
            
            // Delete Checkpoint Button
            $(".deleteButton").click(function (event) {
                var checkpointID = titleID.replace("#", "").replace("goal", "").replace("checkpoint", "");
                
                console.log("Deleting checkpoint: " + checkpointID)
                
                var postData = { id: checkpointID };
                
                $.post("ajax/delete_checkpoint.php", postData)
                    .done(function (data) {
                        if (data == "success") {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    });
            });
        
        } else {
            $(this).text("Edit");
            
            var checkpointID = titleID.replace("#", "").replace("goal", "").replace("checkpoint", "");
            var titleValue = $(titleID + "_editbox").val();
            var detailsValue = $(detailsID + "_editbox").val();
            
            var postData = { id: checkpointID, title: titleValue, text: detailsValue };
            
            $.post("ajax/update_checkpoint.php", postData)
                .done(function (data) {
                    if (data == "success") {
                        $(titleID).children(".title").html(titleValue);
                        //console.log($(titleID).children(".title").contents());
                        $(detailsID).children(".checkpoint_details_text").html(detailsValue);
                    } else {
                        alert(data);
                    }
                });
        }
    });
    
    /*
    // Delete Checkpoint Button
    $(".deleteButton").click(function (event) {
        var thisButtonID = $(this).attr('id');
        var checkpointID = thisButtonID.replace("_delete", "").replace("goal", "").replace("checkpoint", "");
        
        console.log("Deleting checkpoint: " + checkpointID)
        
        var postData = { id: checkpointID };
        
        $.post("ajax/delete_checkpoint.php", postData)
            .done(function (data) {
                if (data == "success") {
                    location.reload();
                } else {
                    alert(data);
                }
            });
    });
    */
    
    // Show/hide checkpoint details
    $(".checkpoint_details_snip").click(function (event) {
        var thisID = $(this).attr('id');
        var detailsID = "#" + thisID.replace("_snip", "");
        $(this).hide("fast");
        $(detailsID).show("fast");
    });
    $(".hideDetailsButton").click(function (event) {
        var thisID = $(this).attr('id');
        var detailsID = "#" + thisID.replace("_hide", "");
        var detailsSnipID = "#" + thisID.replace("hide", "snip");
        $(detailsID + "_snip").show("fast");
        $(detailsID).hide("fast");
    });
    
    // Show/hide checkpoint children
    $(".childCount").click(function (event) {
        var thisID = $(this).attr('id');
        var childrenID = "#" + thisID.replace("_count", "");
        $(this).hide("fast");
        $(childrenID).show("fast");
    });
    $(".hideChildrenButton").click(function (event) {
        var thisID = $(this).attr('id');
        var childrenID = "#" + thisID.replace("_hide", "");
        $(childrenID + "_count").show("fast");
        $(childrenID).hide("fast");
    });
    
    // Show/hide add checkpoint
    $(".addCheckpointButton").click(function (event) {
        var thisButtonID = $(this).attr('id');
        var checkpointID = "#" + thisButtonID + "_form";
        if ($(this).text() == "+") {
            $(this).html("&ndash;");
        } else {
            $(this).html("+");
        }
        $(checkpointID).toggle("fast");
    });
    
});

function hideElements () {
    $("#loginForm").hide();
    $("#newGoalForm").hide();
    $(".checkpoint_details").hide();
    $(".children").hide();
    $(".addCheckpointForm").hide();
}