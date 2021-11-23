<?php


namespace DokLibs\Browserless\Options;


class DownloadUrl extends CommonOptions
{
    protected $options = [
        "context" => [

            ]
    ];

    protected $script = <<<JS
module.exports = async ({ page:a, context:b }) => {
    if(b.foundOn){
      await a.goto(b.foundOn)
    }else{
        await a.setContent(`<!doctype html>
  <html>
    <head><meta charset='UTF-8'><title>Test</title></head>
    <body><a href='#'>Link</a></body>
  </html>`);
    }
    await a.evaluate((url) => {
        var link = document.querySelector('a');
        link.href = url;
        link.click();
        console.log('Done');
      }, b.url);
};
JS;

    public function __construct($url, $foundOn)
    {
        $this->options['context'] = [
            'url' => $url,
            'foundOn' => $foundOn,
        ];

        $this->options['code'] = $this->script;

    }

}
