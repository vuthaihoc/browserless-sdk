<?php


namespace DokLibs\Browserless\Options;


class DownloadPdfjs extends CommonOptions
{
    protected $options = [

    ];

    protected $script = <<<JS
module.exports = async ({ page:a, context:b }) => {
    await a.goto(b.url, {waitUntil:'networkidle2'});
    await a.waitForFunction('window.PDFViewerApplication && window.PDFViewerApplication.pdfDocument');
    await a.evaluate(async (url) => {
        await PDFViewerApplication.pdfDocument.getData();
        PDFViewerApplication.download();
      }, b.url);
};
JS;

    public function __construct($url)
    {
        $this->options['code'] = $this->script;
        $this->options['context'] = [
            'url' => $url,
        ];
    }

}