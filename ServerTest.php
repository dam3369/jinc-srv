<?php
const WARNING = 'warning';
const ERROR = 'error';
const DEFAUT = 'default';
const VALID = 'valid';
const SUCCESS = 'success';
const URL = 'http://ramno.12.gy/session.php';

put('J\'inc Server test', VALID);
put('Connecting : ' . URL);


$ch = curl_init();

$post = array(
    'ash' => 'srvtest',
    'id' => 1,
    'targ' => 2,
    'lat' => 12.06,
    'lng' => 6.12
);

$post2 = array(
    'ash' => 'srvtest',
    'targ' => 1,
    'id' => 2,
    'lng' => 12.06,
    'lat' => 6.12
);

postRequest($ch, URL, $post);
$response = postRequest($ch, URL, $post2);

$start = microtime(true);
for ($i = 0; $i < 6000; $i++) {
    postRequest($ch, URL, $post2);
}
put(sprintf('6000 post do in : %f s', microtime(true) - $start), VALID);
echo "\n";
if ($response != null) {
    foreach (json_decode($response)->position as $key => $value) {
        if ($value != $post[$key]) {
            put(sprintf('Non conforme value get %f, need %f', $value, $post[$key]), ERROR);
        }
    }
} else {
    put('Mauvaise réponse serveur', ERROR);
}

sleep(60);
postRequest($ch, URL, $post2);

function put($text, $type = DEFAUT) {
    $colors = array(
        'success' => "\033[0;32m",
        'valid' => "\033[0;36m",
        'default' => "\033[0;37m",
        'warning' => "\033[1;33m",
        'error' => "\033[1;31m"
    );

    echo $colors[$type] . $text . "\033[0m\n";
}

function postRequest($ch, $url, $fields) {
    $start = microtime(true);
    $fieldset = [];
    foreach ($fields as $key => $value) {
        $fieldset[] = $key . '=' . $value;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $fieldset));

    $result = curl_exec($ch);

    var_dump(strlen($result) - 1);

    if ($result === false) {
        put(curl_error($ch), ERROR);
    }

    put(sprintf('post do in : %f s', microtime(true) - $start), VALID);
    echo "\n";
    return substr($result, 0, strlen($result) - 1);
}

curl_close($ch);