<?php

test('api/content', function () {
    $url = "https://google.com";
    $servers = [new \DokLibs\Browserless\Host('http://localhost:3000/')];
    $browserless = new \DokLibs\Browserless\Client($servers);
    $content = $browserless->content($url, new \DokLibs\Browserless\Options\DisableJavascript)->getBody()->getContents();
    expect($content)->toContain('<body');
});

test('api/pdf', function () {
    $url = "https://google.com";
    $servers = [new \DokLibs\Browserless\Host('http://localhost:3000/')];
    $browserless = new \DokLibs\Browserless\Client($servers);
    $content = $browserless->pdf($url)->getBody()->getContents();
    expect($content)->toStartWith('%PDF-');
});

test('api/screenshot', function () {
    $url = "https://google.com";
    $servers = [new \DokLibs\Browserless\Host('http://localhost:3000/')];
    $browserless = new \DokLibs\Browserless\Client($servers);
    $response = $browserless->screenshot($url);
    $mime_type = $response->getHeaderLine('Content-Type');
    expect($mime_type)->toEqual('image/jpeg');
});

test('api/download', function () {
    $url = "https://image-us.samsung.com/SamsungUS/tv-ci-resources/2018-user-manuals/2018_UserManual_Q9FNSeries.pdf";
    $servers = [new \DokLibs\Browserless\Host('http://localhost:3000/')];
    $browserless = new \DokLibs\Browserless\Client($servers);
    $response = $browserless->download($url);
    $mime_type = $response->getHeaderLine('Content-Type');
    expect($mime_type)->toEqual('application/pdf');
});

test('api/function', function () {
    $url = "https://ipinfo.io/json";
    $servers = [new \DokLibs\Browserless\Host('http://localhost:3000/')];
    $browserless = new \DokLibs\Browserless\Client($servers);
    $options = new \DokLibs\Browserless\Options\CommonOptions();
    $options->setOption('code', <<<JS
module.exports = async ({ page, context }) => {
  const { url } = context;
  await page.goto(url);
  const data = await page.content();
  return {
    data,
    // Make sure to match the appropriate content here
    // You'll likely want 'application/json'
    type: 'application/html'
  };
};
JS
    );
    $options->setOption('context', [
        'url' => $url,
    ]);
    $response = $browserless->function($url, $options);
    $content = $response->getBody()->getContents();
    $mime_type = $response->getHeaderLine('Content-Type');
    expect($mime_type)->toStartWith('application/html');
    expect($content)->toContain("ip", "city", "region", "country", "loc");
});
