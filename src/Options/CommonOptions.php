<?php


namespace DokLibs\Browserless\Options;


class CommonOptions implements OptionInterface
{

    protected $options = [

    ];

    public function getOption($key)
    {
        return $this->options[$key] ?? null;
    }

    public function getOptions() : array
    {
        return $this->options;
    }

    public function merge(OptionInterface $option): OptionInterface
    {
        $this->options = array_merge($this->options, $option->getOptions());
        return $this;
    }

}
