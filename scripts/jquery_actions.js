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
    $(".checkpointSortInput").focusout(function () {
        if ($(this).is(":visible")) {
            validateCheckpoint($(this).attr("parentID"));
        }
    });
    
    // Update Status
    StyleCompletedCheckpointsOnLoad();
    $(".checkpoint_status").change(function () {
        var checkpointID = $(this).attr('id').replace("_status", "").replace("goal", "").replace("checkpoint", "");
        var postData = { id: checkpointID, status: $(this).val() };
        
        $.post("ajax/update_status.php", postData)
            .done(function (data) {
                if (data != "success") {
                    alert(data);
                    return;
                }
            });
            
        if ($(this).val() == 2) {   // Changed to Complete
            /*$(this).parent().parent().addClass("complete");
            $(this).parent().parent().find("div").each(function () {
                $(this).addClass("complete");
            });*/
            // Update all children to be completed, too.
            $(this).parent().parent().find("select").each(function () {
                $(this).val(2);
                var childCheckpointID = $(this).attr('id').replace("_status", "").replace("goal", "").replace("checkpoint", "");
                var childPostData = { id: childCheckpointID, status: $(this).val() };
        
                $.post("ajax/update_status.php", childPostData)
                    .done(function (data) {
                        if (data != "success") {
                            alert(data);
                            return;
                        }
                    });
            });
            StyleChildCompletedCheckpoints ($(this))
        } else {
            $(this).parent().parent().removeClass("complete");
            $(this).parent().parent().find("div").each(function () {
                $(this).removeClass("complete");
            });
            // Re-check statuses for incorrectly un-styled checkpoints and re-add the class.
            ReStyleCompletedCheckpoints(false);
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
        var detailsClass = "." + $(detailsID).attr('class');
        var checkpointID = titleID.replace("#", "").replace("goal", "").replace("checkpoint", "");
        
        if ($(this).text() == "Edit") {
            $(this).text("Done");
            
            var titleText = $(titleID).children(".title").text();
            var titleEditBox = "<textarea id='" + titleID.replace("#", "") + "_editbox' class='titleEditBox'>" + titleText + "</textarea>";
            // Need to fix this -- Prevents changing root-level "goals"
            var detailsText = $(detailsID).children(detailsClass + "_text").text();
            var detailsEditBox = "<textarea id='" + detailsID.replace("#", "") + "_editbox' class='detailsEditBox'>" + detailsText + "</textarea>";
            detailsEditBox += "<br /><br /><span id='" + titleID.replace("#", "") + "_delete' class='deleteButton clickable'>Delete?</span>";
            detailsEditBox += "<div class='deleteConfirm'>";
            if (detailsClass.indexOf("goal") >= 0) {
                detailsEditBox += "Are you sure you want to delete this goal and all its checkpoints?<br />";
            } else {
                detailsEditBox += "Are you sure you want to delete this checkpoint and all its sub-checkpoints?<br />";
            }
            detailsEditBox += "<span id='yesButton' class='clickable'>Yes</span>";
            detailsEditBox += "&nbsp;&nbsp;&nbsp;&nbsp;";
            detailsEditBox += "<span id='noButton' class='clickable'>No</span>";
            detailsEditBox += "</div>";
            
            $(titleID).children(".title").html(titleEditBox);
            
            $(detailsSnipID).hide("fast");
            $(detailsID).show("fast");
            $(detailsID).children(detailsClass + "_text").html(detailsEditBox);
            $(".deleteConfirm").hide();
            
            // Delete Checkpoint Button
            $(".deleteButton").click(function (event) {
                $(".deleteButton").hide("fast");
                $(".deleteConfirm").show("fast");
            });
            $("#noButton").click(function (event) {
                $(".deleteConfirm").hide("fast");
                $(".deleteButton").show("fast");
            });
            $("#yesButton").click(function (event) {
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
                        $(detailsID).children(detailsClass + "_text").html(detailsValue);
                    } else {
                        alert(data);
                    }
                });
        }
    });
    
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
        var checkpointFormID = "#" + thisButtonID + "_form";
        var checkpointTitleInputID = "#" + thisButtonID.replace("addCheckpoint", "checkpointTitleInput");
        if ($(this).text() == "+") {
            $(this).html("&ndash;");
        } else {
            $(this).html("+");
        }
        $(checkpointFormID).toggle("fast");
        $(checkpointTitleInputID).focus();
    });
    
});

function hideElements () {
    $("#loginForm").hide();
    $("#newGoalForm").hide();
    $(".checkpoint_details").hide();
    $(".children").hide();
    $(".addCheckpointForm").hide();
}

function StyleCompletedCheckpointsOnLoad (collapseChildren) {
    collapseChildren = (typeof collapseChildren != 'undefined') ? collapseChildren : true;
    $(".checkpoint_status").each(function () {
        if ($(this).val() == 2) {
            $(this).parent().parent().addClass("complete");
            $(this).parent().parent().find("div").each(function () {
                $(this).addClass("complete");
            });
            if (collapseChildren) {
                // Collapse all children
                $(this).parent().parent().find(".childCount").each(function (event) {
                    var thisID = $(this).attr('id');
                    var childrenID = "#" + thisID.replace("_count", "");
                    $(this).show("fast");
                    $(childrenID).hide("fast");
                });
            }
        }
    });
}
function ReStyleCompletedCheckpoints () {
    $(".checkpoint_status").each(function () {
        if ($(this).val() == 2) {
            $(this).parent().parent().addClass("complete");
            $(this).parent().parent().children().each(function () {
                $(this).addClass("complete");
            });
        }
    });
}
function StyleChildCompletedCheckpoints (element, collapseChildren) {
    collapseChildren = (typeof collapseChildren != 'undefined') ? collapseChildren : true;
    element.parent().parent().addClass("complete");
    element.parent().parent().find("div").each(function () {
        $(this).addClass("complete");
    });
    if (collapseChildren) {
        // Collapse all children
        element.parent().parent().find(".childCount").each(function (event) {
            var thisID = $(this).attr('id');
            var childrenID = "#" + thisID.replace("_count", "");
            $(this).show("fast");
            $(childrenID).hide("fast");
        });
    }
}