# Browserless SDK


# Usage

## /content

```php
$url = "https://google.com";
$servers = [new \DokLibs\Browserless\Host('http://localhost:6130/')];
$browserless = new \DokLibs\Browserless\Client($servers);
echo $browserless->content($url, new \DokLibs\Browserless\Options\DisableJavascript)->getBody()->getContents());
```

## /pdf

```php
$browserless->pdf($url, null, './google.pdf');
```

## /download

### normal download
```php
$browserless->download('http://clean.kins.re.kr/common/fileDownload.jsp?IS_NSIC=Y&RPT_SEQ=27&FILE_NAME=%ED%95%B4%EC%96%91%EB%B0%A9%EC%82%AC%EB%8A%A5%EC%A1%B0%EC%82%AC(2006).pdf&PATH_NAME=report'
    , 'https://nsic.nssc.go.kr/dta/actvInfoList.do?divUpperCd=CMN01705'
    , 'file.pdf'); 
```
### scripts additional download
```php
$scripts = <<<JS
module.exports = async ({ page:a, context:b }) => {
    await a.goto(b.foundOn,{waitUntil: 'networkidle2'});
    await a.waitForFunction(()=> typeof PDFViewerApplication === 'object' && typeof PDFViewerApplication.pdfDocument === 'object');
    await a.evaluate(()=>{
        PDFViewerApplication.pdfDocument.getData().then(()=>{
            PDFViewerApplication.download();
        });
    })
};
JS;

$bowerless->download("", "https://docplayer.net/docview/58/42282670/#file=/storage/58/42282670/42282670.pdf", "file.pdf", $scripts);
```


## Make request to use with Guzzle Pool

```php
$url = "https://google.com";
$servers = [new \DokLibs\Browserless\Host('http://localhost:6130/')];
$browserless = new \DokLibs\Browserless\Client($servers);
$request = $browserless->makeRequest($url, []);
```

## Merge options

```php
$option = (new \DokLibs\Browserless\Options\ScreenShot)->merge(
            new \DokLibs\Browserless\Options\DisableGaAndAds()
        );
$browserless->screenshot($url, $option, './123dok.jpg'); 
```
