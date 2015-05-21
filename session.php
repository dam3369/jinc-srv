<?php
$sessions = __DIR__ . DIRECTORY_SEPARATOR . 'sessions' . DIRECTORY_SEPARATOR;
$request = (object)$_POST;

if (!file_exists($sessions . $request->ash)) {
    mkdir($sessions . $request->ash);
}
$position = (object)array('lat' => $request->lat, 'lng' => $request->lng);

file_put_contents($sessions . $request->ash . DIRECTORY_SEPARATOR . $request->id, json_encode($position));

$target = new stdClass();
$connected = false;
if (file_exists($sessions . $request->ash . DIRECTORY_SEPARATOR . $request->targ)) {
    $target = json_decode(file_get_contents($sessions . $request->ash . DIRECTORY_SEPARATOR . $request->targ));
    $connected = filemtime($sessions . $request->ash . DIRECTORY_SEPARATOR . $request->targ) > ((new DateTime())->format('U') - 60);
}

$response = new stdClass();
$response->position = $target;
$response->connected = $connected;

echo json_encode($response);