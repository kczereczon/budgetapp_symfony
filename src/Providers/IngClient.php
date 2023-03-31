<?php

namespace App\Providers;


use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class IngClient
{
    private HttpClientInterface $ingClient;

    public function __construct(HttpClientInterface $ingClient)
    {
        $this->ingClient = $ingClient;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getBaseAccessToken()
    {
        $this->ingClient->request(
            'POST',
            '/oauth2/token',
            [
                'headers' => [
                    'Date' => 'text/plain',
                ]
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function request(string $method, string $url, string $body): ResponseInterface
    {
        $body = urlencode($body ?? '');
        $digest = $this->generateDigest($body);
        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $signatureHeader = $this->getSignatureHeader($method, $url, $date, $digest);

        return $this->ingClient->request($method, $url, [
            'body' => $body,
            'headers' => [
                ...$signatureHeader,
                'Date' => $date,
                'Digest' => $digest,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);
    }

    public function generateDigest(string $body): string
    {
        return hash('sha256', base64_encode($body));
    }

    public function getSignature(string $method, string $url, string $date, string $digest): string
    {
        $string = "(request-target): $method $url\ndate: $date\ndigest: $digest";
        openssl_private_encrypt($string, $encrypted, file_get_contents(env('CERT_PATH')));
        return $encrypted;
    }

    public function getSignatureHeader(string $method, string $url, string $date, string $digest): array
    {
        $clientId = env('OAUTH_ING_CLIENT_ID');
        $signature = $this->getSignature($method, $url, $date, $digest);
        return ['Authorization' => "Signature keyId=\"$clientId\",algorithm=\"rsa-sha256\",headers=\"(request-target) date digest\",signature=\"$signature\""];
    }
}