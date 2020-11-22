<?php

use Swoole\Server;

class AdminServer 
{
    private $server;
    private $ip = '0.0.0.0';
    private $port = '9501';

    public function __construct()
    {
        echo "server is start...";
        $this->server = new Server($this->ip, $this->port);
        $this->onEvnet();
    }

    public function connect(Server $server, $fd)
    {
        echo "Client: Connect.\n";
    }
    public function receive(Server $server, $fd, $from_id, $data)
    {
        echo "接收到机器人的消息";
        var_dump($data);
        $data = json_decode($data, true);
        $this->{$data['method']}($server, $fd, $from_id, $data);
    }

    public function machineInfo(Server $server, $fd, $from_id, $data)
    {
        echo "machineInfo \n";
        $server->send($fd, json_encode($data));
    }
    public function machineStop(Server $server, $fd, $from_id, $data)
    {
        echo "machineStop \n";
        //停止机器
        $this->send('192.168.3.141', '9555', json_encode($data));
        $server->send($fd, json_encode(['code'=> 200, 'msg'=> 'ok']));
    }

    public function send($ip, $port, $data)
    {
        $client = new Swoole\Client(SWOOLE_SOCK_TCP);
        if(!$client->connect($ip, $port)){
            exit("connect faild");
        }
        $client->send($data);
        $res = $client->recv();
        $client->close();
        return $res;
    }

    public function close(Server $server, $fd)
    {
        echo "Client: Close.\n";
    }

    protected function onEvnet()
    {
        $this->server->on('connect', [$this, 'connect']);
        $this->server->on('receive', [$this, 'receive']);
        $this->server->on('close'  , [$this, 'close']);
    }
    public function start()
    {
        $this->server->start();
    } 
}

//启动服务器
(new AdminServer)->start();