<?php


namespace DokLibs\Browserless\Options;


trait RequestOptions
{
    protected $options = [

    ];

    protected $resource_types = [
        'document',
        'stylesheet',
        'image',
        'media',
        'font',
        'script',
        'texttrack',
        'xhr',
        'fetch',
        'eventsource',
        'websocket',
        'manifest',
        'other',
    ];

    public function getOption($key)
    {
        return $this->options[$key] ?? null;
    }

    public function getOptions() : array
    {
        return $this->options;
    }

    public function merge(CommonOptions $option)
    {
        $this->options = array_merge($this->options, $option->getOptions());
        return $this;
    }

    public function rejectRequestPattern() : self {return $this;}

    /**
     * can be 'document','stylesheet','image','media','font','script','texttrack','xhr','fetch','eventsource',
     * 'websocket','manifest','other'
     * @return $this
     */
    public function setRejectResourceTypes(string ...$types) : self {
        if($types[0] === null){
            if($this->options['rejectResourceTypes'] ?? false){
                unset($this->options['rejectResourceTypes']);
            }
        }else{
            $this->options['rejectResourceTypes'] = $types;
        }
        return $this;
    }
    public function setExtraHTTPHeaders(?array $headers) : self {
        if($headers == null){
            if($this->options['setExtraHTTPHeaders'] ?? false){
                unset($this->options['setExtraHTTPHeaders']);
            }
            return $this;
        }

        if(empty($this->options['setExtraHTTPHeaders'])){
            $this->options['setExtraHTTPHeaders'] = [];
        }

        foreach ($headers as $k => $header){
            if(is_numeric($k)){
                list($header_name, $header_content) = explode(":", $header, 2);
                $this->options['setExtraHTTPHeaders'][trim($header_name)] = trim($header_content);
            }else{
                $this->options['setExtraHTTPHeaders'][$k] = trim($header);
            }
        }
        return $this;
    }
    public function setJavaScriptEnabled(?bool $enabled = null) : self {
        if($enabled === null){
            if($this->options['setJavaScriptEnabled'] ?? false){
                unset($this->options['setJavaScriptEnabled']);
            }
        }else{
            $this->options['setJavaScriptEnabled'] = $enabled;
        }
        return $this;
    }
    public function userAgent() : self {return $this;}
    public function gotoOptions() : self {return $this;}
    public function waitFor() : self {return $this;}

}