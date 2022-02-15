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

    public function append($endpoint, $stealth = true, $trackingId = '', $timeout = 0) : string {
        return rtrim($this->host, "/") . "/"
            . ltrim($endpoint, "/")
            . "?token=" . $this->token
            . ($stealth ? "&stealth" : "")
            . ($trackingId ? "&trackingId=" . $trackingId : "")
            . ($timeout ? "&timeout=" . $timeout : "");
    }

    public function appendWithQuery($endpoint, string $query = '') : string {
        return rtrim($this->host, "/") . "/"
            . ltrim($endpoint, "/")
            . "?token=" . $this->token
            . ($query ? "&" . $query : "");
    }

}
