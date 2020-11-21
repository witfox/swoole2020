<?php

class WebsocketServer {

    private $server;
    private $ip = "0.0.0.0";
    private $port = "9500";

    public function __construct()
    {
        $this->server = new \Swoole\WebSocket\Server($this->ip, $this->port);
        $this->onInit();
    }

    public function open($server, $request)
    {
        $server->push($request->fd, "hello, welcome\n");
    }

    public function message($server, $frame)
    {
        $server->push($frame->fd, json_encode(["msg" => "Hello"]));
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