<?php

$client = new Swoole\Client(SWOOLE_SOCK_TCP);

if(!$client->connect("192.168.3.141", "9501", -1)){
    exit("connect failed");
}
$data = [
    'code' => 200,
    'msg' => 'you are nice',
    'method' => 'machineInfo'
];
$client->send(json_encode($data));
echo $client->recv().'\n';
$client->close();