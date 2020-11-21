<?php

class WebsocketServer {

    private $server;
    private $ip = "0.0.0.0";
    private $port = "9503";

    public function __construct()
    {
        $this->server = new \Swoole\WebSocket\Server($this->ip, $this->port);
        $this->onInit();
    }

    public function open($server, $request)
    {
        var_dump($request->fd, $request->server);
        $server->push($request->fd, "hello, welcome\n");
    }

    public function message($server, $frame)
    {
        echo "Message: {$frame->data}\n";
        $server->push($frame->fd, "server: {$frame->data}");
    }

    public function close($fd)
    {
        echo "client-{$fd} is closed\n";
    }

    public function onInit()
    {
        $this->server->on('open', [$this, 'open']);
        $this->server->on('message', [$this, 'message']);
        $this->server->on('close', [$this, 'close']);
    }
    public function start()
    {
        $this->server->start();
    }
}

(new WebsocketServer)->start();