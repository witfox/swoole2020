<?php

use Swoole\Server;
class Machine 
{
    private $server;
    private $host = "0.0.0.0";
    private $port = "9505";

    function __construct()
    {
        $this->server = new Server($this->host, $this->port);
        $this->onEvent();

        (new Listen($this->server));
    }

    public function connect(Server $server, $fd)
    {
        echo "Client: Connect.\n";
    }
    public function receive(Server $server, $fd, $from_id, $data)
    {
        $return = $this->send("192.168.3.141", "9501", $data);
        var_dump($return);
        $server->send($fd, $return);
        
    }
    public function close(Server $server, $fd)
    {
        echo "Client: Close.\n";
    }

    public function send($host, $port, $data)
    {
        $client = new Swoole\Client(SWOOLE_SOCK_TCP);
        if(!$client->connect($host, $port, -1)){
            exit("connect failed: {$client->errCode}");
        }
        $client->send($data);
        $return = $client->recv();
        $client->close();
        return $return;
    }

    public function onEvent()
    {
        $this->server->on('connect', [$this, 'connect']);
        $this->server->on('receive', [$this, 'receive']);
        $this->server->on('close', [$this, 'close']);
    }

    public function start()
    {
        $this->server->start();
    }

}

(new Machine)->start();