<?php

// Simplified PHP functions
function query ($query) {
    return mysqli_query(connection(), $query);
}
function num_rows ($query_results) {
    return mysqli_num_rows($query_results);
}
function fetch_assoc ($query_results) {
    return mysqli_fetch_assoc($query_results);
}

// PHP Helpers
function Validate_User($name, $password) {
    $hashed_pw = crypt($password, $name);
    $query = "SELECT * FROM user WHERE name='" . $name . "' AND password='" . $hashed_pw . "'";
    $users = query($query);
    
    if (num_rows($users) === 1) {
        return true;
    } else {
        return false;
    }
}
function Get_Username($id) {
    $query = "SELECT name FROM user WHERE id=" . $id;
    $users = query($query);
    
    if (num_rows($users) > 0) {
        if (num_rows($users) === 1) {
            while($user = fetch_assoc($users)) {
                return $user["name"];
            }
        } else {
            return "More than one username returned!";
        }
    } else {
        return "No User";
    }
}
function Get_User_Id($username) {
    $query = "SELECT id FROM user WHERE name='" . $username . "'";
    $users = query($query);
    
    if (num_rows($users) > 0) {
        if (num_rows($users) === 1) {
            while($user = fetch_assoc($users)) {
                return $user["id"];
            }
        } else {
            return "More than one username returned!";
        }
    } else {
        return "No User";
    }
}

?>