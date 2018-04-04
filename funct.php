<?php

// Simplified PHP functions
function query ($query_string, $params = array(), $return_results = true) {
    $db_connection = connection();
    try {
        $query_results = $db_connection->prepare($query_string);
        $query_results->execute($params);
        if ($return_results) {
            return $query_results->fetchAll();
        } else {
            return $query_results;
        }
    }
    catch (PDOException $ex) {
        echo '<pre>' . var_export($ex, true) . '<pre>';
        return false;
    }
}

function getStatuses () {
    if (isset($GLOBALS['statuses'])) {
        return $GLOBALS['statuses'];
    }
    $status_query = "SELECT * FROM status WHERE id > 0 ORDER BY id ASC";
    $GLOBALS['statuses'] = query($status_query);
    return $GLOBALS['statuses'];
}

/**
 * simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting
 * PHP 5.4.9
 * 
 * Retrieved from:
 * https://naveensnayak.wordpress.com/2013/03/12/simple-php-encrypt-and-decrypt/
 *
 * this is a beginners template for simple encryption decryption
 * before using this in production environments, please read about encryption
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 *
 * @return string
 */
function easy_crypt($action, $string) {
    $encrypt_method = "AES-256-CBC";
    $secret_key = ENCRYPT_KEY;
    $secret_iv = ENCRYPT_IV;

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        return base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        return openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return false;
}

// PHP Helpers
function Validate_User($name, $password) {
    $query = "SELECT * FROM user WHERE name=?";
    $users = query($query, array($name));
    // echo '<pre>' . var_export($users, true) . '</pre>';
    if ($users && count($users) === 1) {
        echo '<pre>' . var_export($users, true) . '</pre>';
        return password_verify($password, $users[0]['password']);
    }
    return false;
}
function Get_Username($id) {
    $query = "SELECT name FROM user WHERE id=" . $id;
    $users = query($query);
    
    if ($users && count($users) > 0) {
        if (count($users) === 1) {
            return $users[0]['name'];
        } else {
            return "More than one username returned!";
        }
    } else {
        return "No User";
    }
}
function Get_User_Id($username) {
    $query = "SELECT id FROM user WHERE name=?";
    $users = query($query, array($username));

    if ($users && count($users) > 0) {
        if (count($users) === 1) {
            return $users[0]['id'];
        } else {
            return "More than one username returned!";
        }
    }
    return "No User";
}

?>