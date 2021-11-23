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
}
