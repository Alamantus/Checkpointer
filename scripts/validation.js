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

/*function validateCheckpoint () {
    console.log("validating goal");
    $("#newTitleMessage").html("");
    $("#newTitleMessage").attr("class", "hidden");
    var title = $("#newTitleInput").val();
    $("#newSortMessage").html("");
    $("#newSortMessage").attr("class", "hidden");
    var sort = $("#newSortInput").val();
    
    if (title == null || title == "") {
        $("#newTitleMessage").html("Title cannot be blank.");
        $("#newTitleMessage").attr("class", "invalid");
        return false;
    }
    if (sort == null || sort == "") {
        $("#newSortMessage").html("Sort Order cannot be blank.");
        $("#newSortMessage").attr("class", "invalid");
        return false;
    }
    if (!isNumeric(sort)) {
        $("#newSortMessage").html("Sort Order must be a number.");
        $("#newSortMessage").attr("class", "invalid");
        return false;
    }
    
    return true;
}*/

function validateCheckpoint (id) {
    console.log("validating checkpoint");
    var validationErrors = false;
    var checkpointMessageId = "checkpointTitleMessage" + id.toString();
    var checkpointValueId = "checkpointTitleInput" + id.toString();
    $("#" + checkpointMessageId).html("");
    $("#" + checkpointMessageId).attr("class", "hidden");
    var title = $("#" + checkpointValueId).val();
    
    var checkpointSortMessageId = "checkpointSortMessage" + id.toString();
    var checkpointSortValueId = "checkpointSortInput" + id.toString();
    $("#" + checkpointSortMessageId).html("");
    $("#" + checkpointSortMessageId).attr("class", "hidden");
    var sort = $("#" + checkpointSortValueId).val();
    
    if (title == null || title == "") {
        $("#" + checkpointMessageId).html("Title cannot be blank.");
        $("#" + checkpointMessageId).attr("class", "invalid");
        validationErrors = true;
    }
    if (sort == null || sort == "") {
        $("#" + checkpointSortMessageId).html("Sort Order cannot be blank.");
        $("#" + checkpointSortMessageId).attr("class", "invalid");
        validationErrors = true;
    }
    if (!isNumber(sort)) {
        $("#" + checkpointSortMessageId).html("Sort Order must be a number.");
        $("#" + checkpointSortMessageId).attr("class", "invalid");
        validationErrors = true;
    }
    
    if (validationErrors) {
        return false;
    } else {
        return true;
    }
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}