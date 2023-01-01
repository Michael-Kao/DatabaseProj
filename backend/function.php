<?php
$status_code = array(
    100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing', // WebDAV; RFC 2518
    103 => 'Early Hints', // RFC 8297
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information', // since HTTP/1.1
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content', // RFC 7233
    207 => 'Multi-Status', // WebDAV; RFC 4918
    208 => 'Already Reported', // WebDAV; RFC 5842
    226 => 'IM Used', // RFC 3229
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found', // Previously "Moved temporarily"
    303 => 'See Other', // since HTTP/1.1
    304 => 'Not Modified', // RFC 7232
    305 => 'Use Proxy', // since HTTP/1.1
    306 => 'Switch Proxy',
    307 => 'Temporary Redirect', // since HTTP/1.1
    308 => 'Permanent Redirect', // RFC 7538
    400 => 'Bad Request',
    401 => 'Unauthorized', // RFC 7235
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required', // RFC 7235
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed', // RFC 7232
    413 => 'Payload Too Large', // RFC 7231
    414 => 'URI Too Long', // RFC 7231
    415 => 'Unsupported Media Type', // RFC 7231
    416 => 'Range Not Satisfiable', // RFC 7233
    417 => 'Expectation Failed',
    418 => 'I\'m a teapot', // RFC 2324, RFC 7168
    421 => 'Misdirected Request', // RFC 7540
    422 => 'Unprocessable Entity', // WebDAV; RFC 4918
    423 => 'Locked', // WebDAV; RFC 4918
    424 => 'Failed Dependency', // WebDAV; RFC 4918
    425 => 'Too Early', // RFC 8470
    426 => 'Upgrade Required',
    428 => 'Precondition Required', // RFC 6585
    429 => 'Too Many Requests', // RFC 6585
    431 => 'Request Header Fields Too Large', // RFC 6585
    451 => 'Unavailable For Legal Reasons', // RFC 7725
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates', // RFC 2295
    507 => 'Insufficient Storage', // WebDAV; RFC 4918
    508 => 'Loop Detected', // WebDAV; RFC 5842
    510 => 'Not Extended', // RFC 2774
    511 => 'Network Authentication Required', // RFC 6585
);

function getuuid($data = null): string {
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

function check_email_format($email): bool {
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
        return false;
    } else {
        return true;
    }
}

function validate_data(object $data = null, object $schema): bool {
    // var_dump($data);
    // var_dump($schema);
    if ($data == null) {
        return false;
    }
    $tmp = array();
    $tmp2 = array();
    foreach ($schema as $key => $value) {
        array_push($tmp, $key);
        // echo $key. ": " .$value. "\n";
    }
    foreach ($data as $key => $value) {
        array_push($tmp2, $key);
        // echo $key. ": " .$value. "\n";
    }
    // var_dump($tmp);
    // var_dump($tmp2);
    // var_dump($tmp == $tmp2);
    return $tmp == $tmp2;
}

function authorize(): void {
    if (!(isset($_SESSION['id']) && isset($_COOKIE['user']) && $_SESSION['id'] == $_COOKIE['user'])) {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            array(
                'status' => 'Unauthorized',
                'message' => 'Unauthorized'
            )
        );
        exit();
    }
}

function handle_error(bool $condition, int $code, string $res_msg): void {
    if(!$condition) {
        return;
    }
    global $status_code;
    header('HTTP/1.1 ' . strval($code) . ' ' . $status_code[$code]);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        array(
            'status' => $status_code[$code],
            'message' => $res_msg
        )
    );
    exit();
}

function success_res(int $code, string $msg, array $data):void {
    global $status_code;
    header('HTTP/1.1 ' . strval($code) . ' ' . $status_code[$code]);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        array(
            'status' => $status_code[$code],
            'message' => $msg,
            'data' => $data
        )
    );
}
?>