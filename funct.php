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
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = SITE_NAME;
    $secret_iv = SITE_CATCHPHRASE;

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
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