function validateLogin() {
    if (validateLoginName() && validateLoginPw()) {
        return true;
    } else {
        return false;
    }
}

function validateLoginName () {
    console.log("validating name");
    $("#nameMessage").html("");
    $("#nameMessage").attr("class", "hidden");
    var name = $("#nameInput").val();
    
    if (name == null || name == "") {
        $("#nameMessage").html("Username cannot be blank.");
        $("#nameMessage").attr("class", "invalid");
        return false;
    } else {
        return true;
    }
}
function validateLoginPw () {
    console.log("validating pw");
    $("pwMessage").html("");
    $("#pwMessage").attr("class", "hidden");
    var pw = $("#pwInput").val();
    
    if (pw == null || pw == "") {
        $("#pwMessage").html("Password cannot be blank.");
        $("#pwMessage").attr("class", "invalid");
        return false;
    } else {
        return true;
    }
}

function validateMilestone () {
    console.log("validating goal");
    $("newTitleMessage").html("");
    $("#newTitleMessage").attr("class", "hidden");
    var title = $("#newTitleInput").val();
    
    if (title == null || title == "") {
        $("#newTitleMessage").html("Title cannot be blank.");
        $("#newTitleMessage").attr("class", "invalid");
        return false;
    } else {
        return true;
    }
}

function validateCheckpoint (id) {
    console.log("validating checkpoint");
    var checkpointMessageId = "checkpointTitleMessage" + id.toString();
    var checkpointValueId = "checkpointTitleInput" + id.toString();
    $("#" + checkpointMessageId).html("");
    $("#" + checkpointMessageId).attr("class", "hidden");
    var title = $("#" + checkpointValueId).val();
    
    if (title == null || title == "") {
        $("#" + checkpointMessageId).html("Title cannot be blank.");
        $("#" + checkpointMessageId).attr("class", "invalid");
        return false;
    } else {
        return true;
    }
}