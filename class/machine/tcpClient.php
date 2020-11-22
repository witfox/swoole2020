<?php

$client = new Swoole\Client(SWOOLE_SOCK_TCP);

if(!$client->connect("192.168.3.141", "9501", -1)){
    exit("connect failed");
}
$data = [
    'code' => 9,
    'msg' => 'you are dog',
    'method' => 'machineStop'
];
$client->send(json_encode($data));
echo $client->recv().'\n';
$client->close();