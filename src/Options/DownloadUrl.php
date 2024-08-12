<?php

/**
 * Download file from url with found on option
 * idea: giả lập hoặc truy cập trang foundOn, sau khi inject link vào trang và trigger click action.
 * note: do link nếu không phải link download thì browserless sẽ vẫn chờ nên cần các đoạn scrip để kiểm tra idle
 * bằng cách đếm số request và response(success or fail) nếu bằng nhau và không nhận được response dạng download thì return
 * để dừng browserless
 */

namespace DokLibs\Browserless\Options;


class DownloadUrl extends CommonOptions
{
    protected $options = [
        "context" => [

            ]
    ];

    protected $script = <<<JS
export default async ({ page, context }) => {
  const sleep = ms => new Promise(res => setTimeout(res, ms));
    if(context.foundOn){
      await page.goto(context.foundOn)
    }else{
        await page.setContent(`<!doctype html>
  <html>
    <head><meta charset='UTF-8'><title>Test</title></head>
    <body><a href='#'>Link</a></body>
  </html>`);
    }
    var requested = 0;
    var responses = 0;
    var have_download = false;
    page.on('request', (request) => {
        requested++;
    })
    page.on('response', (response) => {
        responses++;
        if(response.headers()['content-disposition'] ?? false){
            have_download = true;
        }
    })
    page.on('requestfailed', (request) => {
        responses++;
    })
    await page.evaluate((url) => {
        var link = document.querySelector('a');
        link.href = url;
        link.click();
      }, context.url);
    let pair = 0;
    for (let i = 0; i<10; i++){
        sleep(500);
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
