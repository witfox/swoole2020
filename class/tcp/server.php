<?php

    //创建Server对象，监听 127.0.0.1:9501 端口
    $server = new Swoole\Server('0.0.0.0', 9503);

    $server->set(array(
        'open_length_check' => true,
        'package_max_length' => 2 * 1024 * 1024,
        'package_length_type' => 'n', //see php pack()
        'package_length_offset' => 0,
        'package_body_offset' => 2,
    ));

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