<?php


namespace DokLibs\Browserless\Options;


trait QueryOptions
{
    protected $query_options = [
        'blockAds' => null,
        'ignoreHTTPSErrors' => null,
        'slowMo' => null,
        '--proxy-server' => null,
    ];

    public function setBlockAds($blockAds = null){
        $this->query_options['blockAds'] = $blockAds;
        return $this;
    }

    public function setIgnoreHTTPSErrors($ignored = null){
        $this->query_options['ignoreHTTPSErrors'] = $ignored;
        return $this;
    }

    public function setSlowMo($slowMo = null){
        $this->query_options['slowMo'] = $slowMo;
        return $this;
    }

    public function setProxy($proxy = null){
        $this->query_options['--proxy-server'] = $proxy;
        return $this;
    }

    /**
     * @param array|string $as_array
     */
    public function getQuery($as_array = false){
        $options = array_filter($this->query_options, fn($i) => $i !== null );
        if($as_array){
            return $options;
        }elseif(count($options)){
            $queries = [];
            foreach ($options as $k => $v){
                if($v === true){
                    $queries[] = $k;
                }else{
                    $queries[] = $k . "=" . $v;
                }
            }
            return implode("&", $queries);
        }else{
            return '';
        }
    }

}