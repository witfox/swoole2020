<?php

class WebsocketServer {

    private $server;
    private $ip = "0.0.0.0";
    private $port = "9503";

    public function __construct()
    {
        $this->server = new \Swoole\WebSocket\Server($this->ip, $this->port);
        $this->onInit($this->server);
    }

    public function open($ws, $request)
    {
        var_dump($request->fd, $request->server);
        $ws->push($request->fd, "hello, welcome\n");
    }

    public function message($ws, $frame)
    {
        echo "Message: {$frame->data}\n";
        $ws->push($frame->fd, "server: {$frame->data}");
    }

    public function close($fd)
    {
        echo "client-{$fd} is closed\n";
    }

    public function onInit($ws)
    {
        $ws->on('open', [$this, 'open']);
        $ws->on('message', [$this, 'message']);
        $ws->on('close', [$this, 'close']);
        $ws->start();
    }
    
    public function start($ws)
    {
        $ws->start();
    }
}