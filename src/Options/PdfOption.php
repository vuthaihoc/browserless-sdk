<?php


namespace DokLibs\Browserless\Options;


class PdfOption extends CommonOptions
{
    protected $options = [
        "safeMode" => true,
        "options" => [
            'displayHeaderFooter' => false,
            'printBackground' => true,
            'format' => 'A4',
        ]
    ];

    public function usingV2(): self
    {
        if(isset($this->options['safeMode'])){
            unset($this->options['safeMode']);
        }
        return $this;
    }

}
