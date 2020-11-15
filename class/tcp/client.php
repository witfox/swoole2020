<?php
$client = new Swoole\Client(SWOOLE_SOCK_TCP);
if (!$client->connect('192.168.3.141', 9501, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}

for($i=0; $i<10; $i++){
    $content = "abc";
    $len = pack('n', strlen($content));
    var_dump($len);
    $send = $len . $content;
    var_dump($send);
    $client->send($send);
}

echo $client->recv();
$client->close();