<?php


namespace DokLibs\Browserless;


class LoadBalancer
{

    const ROUND_ROBIN = 0;
    const ROUND_ROBIN_IP = 1;

    protected int $algo;

    protected array $servers = [

    ];
    protected int $last_server_index = 0;

    public function __construct(array $servers, $algo = self::ROUND_ROBIN)
    {
        $this->algo = $algo;
        foreach ($servers as $server){
            if($server instanceof Host){
                $this->servers[] = $server;
            }elseif(!is_array($server)){
                $this->addServer($server);
            }else{
                $this->addServer(...$server);
            }
        }
    }

    public function addServer($host, $token = '', $ip = '')
    {
        $server = new Host($host, $token, $ip);
        $this->servers[] = $server;
    }

    public function getServer() : Host{
        if(count($this->servers) == 1){
            return $this->servers[0];
        }elseif (count($this->servers) == 0){
            throw new \Exception("No server/host config");
        }
        $this->last_server_index = ($this->last_server_index + 1)%count($this->servers);
        return $this->servers[$this->last_server_index];
    }

}
