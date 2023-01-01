<?php
function getuuid($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function check_email_format($email) {
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
        return false;
    } else {
        return true;
    }
}

function validate_data(object $data = null, object $schema) {
    // var_dump($data);
    // var_dump($schema);
    if($data == null) {
        return false;
    }
    $tmp = array(); $tmp2 = array();
    foreach ($schema as $key=>$value) {
        array_push($tmp, $key);
        // echo $key. ": " .$value. "\n";
    }
    foreach($data as $key=>$value) {
        array_push($tmp2, $key);
        // echo $key. ": " .$value. "\n";
    }
    // var_dump($tmp);
    // var_dump($tmp2);
    // var_dump($tmp == $tmp2);
    return $tmp == $tmp2;
}
?>