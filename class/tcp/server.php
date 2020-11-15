<?php

    echo swoole_get_local_ip()['ens33'].":9501\n";
    //创建Server对象，监听 127.0.0.1:9501 端口
    $server = new Swoole\Server('127.0.0.1', 9501);

    //监听连接进入事件
    $server->on('Connect', function ($server, $fd) {
        echo "Client: Connect.\n";
    });

    //监听数据接收事件
    $server->on('Receive', function ($server, $fd, $from_id, $data) {
        var_dump($data);

        $fooLen = unpack('n', $data, 0)[1];
        var_dump($fooLen);

        $con = substr($data, 2, $fooLen);
        var_dump($con);

        $server->send($fd, "Server: " . $con);
    });

    //监听连接关闭事件
    $server->on('Close', function ($server, $fd) {
        echo "Client: Close.\n";
    });

    //启动服务器
    $server->start(); 