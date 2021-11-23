<?php


namespace DokLibs\Browserless\Options;


class DisableGaAndAds extends CommonOptions
{
    protected $options = [
        'rejectRequestPattern' => [
            'googletagmanager.com\/',
            'google-analytics.com\/',
            'clarity.ms\/',
            'googlesyndication.com\/',
            '\/adsbygoogle.js',
        ]
    ];
}