<?php


namespace DokLibs\Browserless;


use DokLibs\Browserless\Options\DownloadUrl;
use DokLibs\Browserless\Options\OptionInterface;
use DokLibs\Browserless\Options\PdfOption;
use DokLibs\Browserless\Options\ScreenShot;
use \GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;

class Client
{
    protected LoadBalancer $load_balancer;
    protected GuzzleClient $guzzle;

    public function __construct(array $servers, $load_balancer_algo = LoadBalancer::ROUND_ROBIN, $timeout = 300)
    {
        $this->load_balancer = new LoadBalancer($servers, $load_balancer_algo);
        $this->guzzle = new GuzzleClient([
            'timeout' => $timeout,
        ]);
    }

    protected function execute(string $endpoint, array $data, OptionInterface $option = null) : ResponseInterface{
        $request = $this->makeRequest($endpoint, $data, $option);
        return $this->guzzle->send($request);
    }

    public function makeRequest($endpoint, array $data = null, OptionInterface $option = null){
        if($option){
            $data = array_merge($data, $option->getOptions());
        }
        $host = $this->load_balancer->getServer();
        return new Request('post',
            $host->append($endpoint), [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $host->token,
            ],
            $data ? \GuzzleHttp\Utils::jsonEncode($data) : null
        );
    }

    public static function transformResponse(ResponseInterface $response) : ResponseInterface{
        return new Response(
            $response->getHeaderLine('x-response-code'),
            $response->getHeaders(),
            $response->getBody()->getContents(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase(),
        );
    }

    public function content($url, OptionInterface $option = null) : ResponseInterface{
        $endpoint = "/content";
        $data = [
            "url" => $url,
        ];
        $response = $this->execute($endpoint, $data, $option);
        return self::transformResponse($response);
    }

    public function pdf($url, OptionInterface $option = null, $saveTo = '') : ResponseInterface|int {
        $option = $option ?: new PdfOption;
        $endpoint = "/pdf";
        $data = [
            "url" => $url,
        ];
        $response = $this->execute($endpoint, $data, $option);
        if($saveTo && $response->getHeaderLine('content-type') == 'application/pdf'){
            return file_put_contents($saveTo, $response->getBody()->getContents());
        }
        return self::transformResponse($response);
    }

    public function screenshot($url, OptionInterface $option = null, $saveTo = '') : ResponseInterface|int {
        $option = $option ?: new ScreenShot;
        $endpoint = "/screenshot";
        $data = [
            "url" => $url,
        ];
        $response = $this->execute($endpoint, $data, $option);
        if($saveTo &&
            str_starts_with($response->getHeaderLine('content-type'), 'image/')
        ){
            return file_put_contents($saveTo, $response->getBody()->getContents());
        }
        return self::transformResponse($response);
    }

    public function download($url, $foundOn = '', $saveTo = '', OptionInterface $option = null) : ResponseInterface|int {
        $option = $option ?: new DownloadUrl($url, $foundOn);
        $endpoint = "/download";
        $data = [];
        $response = $this->execute($endpoint, $data, $option);
        if($saveTo){
            return file_put_contents($saveTo, $response->getBody()->getContents());
        }
        return self::transformResponse($response);
    }

}
