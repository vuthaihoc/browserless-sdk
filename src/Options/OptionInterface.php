<?php


namespace DokLibs\Browserless\Options;


interface OptionInterface
{
    public function getOption($key);
    public function getOptions();
    public function merge(OptionInterface $option) : self;
}
