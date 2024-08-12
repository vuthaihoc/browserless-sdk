<?php


namespace DokLibs\Browserless\Options;


class PdfOption extends CommonOptions
{
    protected $options = [
        "options" => [
            'displayHeaderFooter' => false,
            'printBackground' => true,
            'format' => 'A4',
        ]
    ];
}
