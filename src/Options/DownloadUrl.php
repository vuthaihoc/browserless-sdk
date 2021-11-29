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
    var requested = 0;
    var responses = 0;
    var have_download = false;
    a.on('request', (request) => {
        requested++;
        console.log('--------', requested, responses);
    })
    a.on('response', (response) => {
        responses++;
        if(response.headers()['content-disposition'] ?? false){
            have_download = true;
        }
        console.log('--------y', requested, responses, response.headers()['content-disposition'] ?? 'uuuuuu');
    })
    a.on('requestfailed', (request) => {
        responses++;
        console.log('--------n', requested, responses);
    })
    await a.evaluate((url) => {
        var link = document.querySelector('a');
        link.href = url;
        link.click();
      }, b.url);
    let pair = 0;
    for (let i = 0; i<10; i++){
        await a.waitForTimeout(500);
        if(requested === responses){
            if(requested !== pair){
                pair = requested;                
            }else if(pair > 0){
                if(!have_download){
                    return null;
                }
            }
        }else{
            pair = 0;
        }
    }
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
