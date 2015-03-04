$( document ).ready(function() {
    hideElements();
    
    // Login form
    $("#loginButton").click(function (event) {
        $(this).parent().css("background-color", "#D9D9FF");
        $("#loginForm").slideDown("fast");
        $("#nameInput").focus();
    });
    $("#cancelLoginButton, #createAccountButton").click(function (event) {
        $("#loginButton").parent().css("background-color", "");
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
    
    // Create Account form
    $("#createAccountButton").click(function (event) {
        $(this).parent().css("background-color", "#D9D9FF");
        $("#createAccountForm").slideDown("fast");
        $("#createAccountUsername").focus();
    });
    $("#cancelCreateAccountButton, #loginButton").click(function (event) {
        $("#createAccountButton").parent().css("background-color", "");
        $("#createAccountForm").slideUp("fast");
    });
    $("#createAccountUsername").focusout(function () {
        if ($(this).is(":visible")) {
            var usernameValue = $(this).val();
            if (usernameValue.length <= 1) {
                $("#createAccountUsernameMesssage").text("Must be more than 1 character long.");
                $("#createAccountUsernameMesssage").attr("class", "invalid");
            } else if (usernameValue.indexOf(" ") >= 0) {
                $("#createAccountUsernameMesssage").text("You cannot include spaces in your username.");
                $("#createAccountUsernameMesssage").attr("class", "invalid");
            } else {
                var usernamePostData = { username: usernameValue };
                
                $.post("ajax/check_username.php", usernamePostData)
                    .done(function (data) {
                        if (data != "No User") {
                            $("#createAccountUsernameMesssage").text(usernameValue + " is already taken.");
                            $("#createAccountUsernameMesssage").attr("class", "invalid");
                        } else {
                            $("#createAccountUsernameMesssage").text("");
                            $("#createAccountUsernameMesssage").attr("class", "hidden");
                        }
                    });
            }
        }
    });
    $("#createAccountPassword").focusout(function () {
        if ($(this).is(":visible")) {
            var pwValue = $(this).val();
            if (pwValue.length < 4) {
                $("#createAccountPasswordMesssage").text("Must be at least 4 characters long.");
                $("#createAccountPasswordMesssage").attr("class", "invalid");
            } else {
                $("#createAccountPasswordMesssage").text("");
                $("#createAccountPasswordMesssage").attr("class", "hidden");
            }
        }
    });
    $("#createAccountForm").submit(function () {
        if ($(this).is(":visible")) {
            if ($("#createAccountUsernameMesssage").text() == "" && $("#createAccountPasswordMesssage").text() == "") {
                return true;
            } else {
                return false;
            }
        }
    });
    
    // New Goal form
    $("#newGoalButton").click(function (event) {
        $(this).parent().css("background-color", "#D9D9FF");
        $("#newGoalForm").slideDown("fast");
        $("#newTitleInput").focus();
    });
    $("#cancelNewGoalButton").click(function (event) {
        $("#newGoalButton").parent().css("background-color", "");
        $("#newGoalForm").slideUp("fast");
    });
    
    // New Checkpoint Form Validation
    $(".checkpointTitleInput").focusout(function () {
        if ($(this).is(":visible")) {
            validateCheckpoint($(this).attr("parentID"));
        }
    });
    
    // Update Status
    StyleCheckpointStatusOnLoad();
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
            StyleChildCheckpointStatus ($(this))
        } else if ($(this).val() == 3) {   // Changed to Cancelled
            // Update all children to be completed, too.
            $(this).parent().parent().find("select").each(function () {
                $(this).val(3);
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
            StyleChildCheckpointStatus ($(this))
        } else {
            $(this).parent().parent().removeClass("complete");
            $(this).parent().parent().find("div").each(function () {
                $(this).removeClass("complete");
            });
            // Re-check statuses for incorrectly un-styled checkpoints and re-add the class.
            ReStyleCheckpointStatus(false);
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
        var privacy = $(this).attr('privacy');
        
        if ($(this).text() == "Edit") {
            $(this).text("Done");
            
            var titleText = $(titleID).children(".title").text();
            var titleEditBox = "<input type='text' id='" + titleID.replace("#", "") + "_editbox' class='titleEditBox' name='title' value='" + titleText + "' length='199'>";
            var detailsText = $(detailsID).children(detailsClass + "_text").html().replace(/<br[ ]?\/?>/g, "\n");
            var detailsEditBox = "<textarea id='" + detailsID.replace("#", "") + "_editbox' class='detailsEditBox'>" + detailsText + "</textarea><br /><br />";
            if (typeof privacy !== "undefined") {
                detailsEditBox += "Goal Privacy: <select id='" + titleID.replace("#", "") + "_privacy' class='privacySelect'><option value='0'" + ((privacy != 1) ? "selected='selected'" : "") + ">Private</option><option value='1'" + ((privacy == 1) ? "selected='selected'" : "") + ">Public</option></select> <span class='privacyExplanation clickable'>What's this?</span>";
            }
            detailsEditBox += "<span id='" + titleID.replace("#", "") + "_delete' class='deleteButton clickable'>Delete?</span>";
            detailsEditBox += "<div id='" + titleID.replace("#", "") + "_confirm' class='deleteConfirm'>";
            if (detailsClass.indexOf("goal") >= 0) {
                detailsEditBox += "Are you sure you want to delete this goal and all its checkpoints?<br />";
            } else {
                detailsEditBox += "Are you sure you want to delete this checkpoint and all its sub-checkpoints?<br />";
            }
            detailsEditBox += "<span id='" + titleID.replace("#", "") + "_yesButton' class='clickable'>Yes</span>";
            detailsEditBox += "&nbsp;&nbsp;&nbsp;&nbsp;";
            detailsEditBox += "<span id='" + titleID.replace("#", "") + "_noButton' class='clickable'>No</span>";
            detailsEditBox += "</div>";
            
            $(titleID).children(".title").html(titleEditBox);
            
            $(detailsSnipID).hide("fast");
            $(detailsID).show("fast");
            $(detailsID).children(detailsClass + "_text").html(detailsEditBox);
            $("#" + titleID.replace("#", "") + "_confirm").hide();
                        
            $(".privacyExplanation").click(function (event) {
                var text = "If this goal's privacy is set to Public, anyone who accesses www.checkpointer.tk/?user=<Your-Username-Here> will be able to see this goal and all its checkpoints.\nThis is useful if you want to share your goals with people.";
                alert(text);
            });
            
            // Delete Checkpoint Button
            $("#" + titleID.replace("#", "") + "_delete").click(function (event) {
                $("#" + titleID.replace("#", "") + "_delete").hide("fast");
                $("#" + titleID.replace("#", "") + "_confirm").show("fast");
            });
            $("#" + titleID.replace("#", "") + "_noButton").click(function (event) {
                $("#" + titleID.replace("#", "") + "_confirm").hide("fast");
                $("#" + titleID.replace("#", "") + "_delete").show("fast");
            });
            $("#" + titleID.replace("#", "") + "_yesButton").click(function (event) {
                var areYouSure = confirm("This deletes this checkpoint and any sub-checkpoints attached to it, and it is impossible to retrieve them!\n\nAre you sure you want to delete?");
                if (areYouSure == true) {
                    var postData = { id: checkpointID };
                    $.post("ajax/delete_checkpoint.php", postData)
                        .done(function (data) {
                            if (data == "success") {
                                location.reload();
                            } else {
                                alert(data);
                            }
                        });
                } else {
                    $("#" + titleID.replace("#", "") + "_confirm").hide("fast");
                    $("#" + titleID.replace("#", "") + "_delete").show("fast");
                }
            });
        
        } else {
            $(this).text("Edit");
            
            var userID = $(this).attr('user');
            var checkpointID = titleID.replace("#", "").replace("goal", "").replace("checkpoint", "");
            var titleValue = $(titleID + "_editbox").val();
            var detailsValue = $(detailsID + "_editbox").val();
            var privacyValue = $(titleID + "_privacy").val();
            
            var postData = { id: checkpointID, title: titleValue, text: detailsValue, privacy: privacyValue };
            
            $.post("ajax/update_checkpoint.php", postData)
                .done(function (data) {
                    if (data == "success") {
                        //$(titleID).children(".title").html(titleValue);
                        //console.log($(titleID).children(".title").contents());
                        //$(detailsID).children(detailsClass + "_text").html(detailsValue);
                        location.reload();
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
        $.cookie(childrenID.replace("#", ""), 'shown', { expires: 7, path: '/' });
    });
    $(".hideChildrenButton").click(function (event) {
        var thisID = $(this).attr('id');
        var childrenID = "#" + thisID.replace("_hide", "");
        $(childrenID + "_count").show("fast");
        $(childrenID).hide("fast");
        $.removeCookie(childrenID.replace("#", ""), { path: '/' });
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
    
    //If the page is opening after a checkpoint addition,
    //scroll the page up to see checkpoint
    if (typeof $.cookie("anchor") != "undefined")
    {
        var scrollTo = $($.cookie("anchor")).offset().top;
        console.log(window.innerWidth);
        if (window.innerWidth > 600) {
            scrollTo -= $("#header").outerHeight(true);
        }
        $("body").scrollTop(scrollTo);
        $.removeCookie("anchor");
    }
    
});

function hideElements () {
    $("#createAccountForm").hide();
    $("#loginForm").hide();
    $("#newGoalForm").hide();
    $(".checkpoint_details").hide();
    $(".children").each(function () {
        if ( typeof $.cookie($(this).attr('id')) == 'undefined' ) {
            $(this).hide();
        } else {
            if ($("#" + $(this).attr('id') + "_count").text().indexOf("sorting") >= 0) {
                $(this).hide();
            } else {
                $("#" + $(this).attr('id') + "_count").hide();
            }
        }
    });
    $(".addCheckpointForm").hide();
}

function StyleCheckpointStatusOnLoad (collapseChildren) {
    collapseChildren = (typeof collapseChildren != 'undefined') ? collapseChildren : true;
    $(".checkpoint_status").each(function () {
        if ($(this).val() == 2) {
            $(this).parent().parent().addClass("complete");
            $(this).parent().parent().find("div").each(function () {
                $(this).addClass("complete");
            });
        } else if ($(this).val() == 3) {
            $(this).parent().parent().addClass("cancelled");
            $(this).parent().parent().find("div").each(function () {
                $(this).addClass("cancelled");
            });
        }
        if ($(this).val() == 2 || $(this).val() == 3) {
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
function ReStyleCheckpointsStatus () {
    $(".checkpoint_status").each(function () {
        if ($(this).val() == 2) {
            $(this).parent().parent().addClass("complete");
            $(this).parent().parent().children().each(function () {
                $(this).addClass("complete");
            });
        } else if ($(this).val() == 3) {
            $(this).parent().parent().addClass("cancelled");
            $(this).parent().parent().children().each(function () {
                $(this).addClass("cancelled");
            });
        }
    });
}
function StyleChildCheckpointStatus (element, collapseChildren) {
    collapseChildren = (typeof collapseChildren != 'undefined') ? collapseChildren : true;
    if (element.val() == 2) {
        element.parent().parent().addClass("complete");
        element.parent().parent().find("div").each(function () {
            $(this).addClass("complete");
        });
    } else if (element.val() == 3) {
        element.parent().parent().addClass("cancelled");
        element.parent().parent().find("div").each(function () {
            $(this).addClass("cancelled");
        });
    }
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