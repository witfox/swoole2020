<?php

use Swoole\Server;

class AdminServer 
{
    private $server;
    private $ip = '0.0.0.0';
    private $port = '9501';

    public function __construct()
    {
        $this->server = new Server($this->ip, $this->port);
    }

    public function connect(Server $server, $fd)
    {
        echo "Client: Connect.\n";
    }
    public function receive(Server $server, $fd, $from_id, $data)
    {
        echo "接收到机器人的消息";
        $data = json_decode($data, true);
        $this->{$data['method']}($server, $fd, $from_id, $data);
    }

    public function machineInfo(Server $server, $fd, $from_id, $data)
    {
        $server->send($fd, json_encode($data));
    }
    public function machineStop(Server $server, $fd, $from_id, $data)
    {
        //停止机器
        $this->send('127.0.0.1', '9505', json_encode($data));
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
    public function start()
    {
        $this->server->start();
    } 
}

//启动服务器
(new AdminServer)->start();