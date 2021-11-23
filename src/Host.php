<?php


namespace DokLibs\Browserless;


class Host
{
    public function __construct(
        public $host,
        public $token = '',
        public $ip = '',
    ){
    }

    public function append($endpoint) : string {
        return rtrim($this->host, "/") . "/" . ltrim($endpoint, "/");
    }

}
