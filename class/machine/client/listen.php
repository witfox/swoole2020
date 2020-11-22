<?php

use Swoole\Server;
class Listen
{
    private $server;
    private $ip = '';
    private $port = '9505';
    private $listen;
    public function __construct($server)
    {
        $this->server = $server;
        $this->listen = $server->listen($this->ip, $this->port, SWOOLE_SOCK_TCP);
        $this->onEvent();
    }
    public function connect(Server $server, $fd)
    {
        echo "Client: Connect.\n";
    }
    public function receive(Server $server, $fd, $from_id, $data)
    {
        $data = json_decode($data, true);
        if($data['code'] == 9){
            echo "stop server.\n";
            $this->server->shutdown();
        }else{
            $server->send($fd, "listen:",$data['msg']);
        }
    }
    public function close(Server $server, $fd)
    {
        echo "Client: Close.\n";
    }

    public function onEvent()
    {
        $this->listen->on('connect', [$this, 'connect']);
        $this->listen->on('receive', [$this, 'receive']);
        $this->listen->on('close', [$this, 'close']);
    }
}