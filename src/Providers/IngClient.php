<?php

namespace App\Providers;


use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class IngClient
{

    public function __construct(
        private readonly HttpClientInterface $ingClient,
        private readonly string $oauthClientId,
        private readonly string $clientBase,
        private readonly string $privateKeySigning,
        private readonly string $privateKeyTls,
        private readonly string $certificateSigning,
        private readonly string $certificateTls,
        private readonly string $pemSigningPath
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getBaseAccessToken(): ResponseInterface
    {
        return $this->ingClient->request(
            'POST',
            '/oauth2/token',
            [
                'body' => 'grant_type=client_credentials&scope=greetings,view'
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function request(string $method, string $url, array $body = []): ResponseInterface
    {
        $encodedBody = http_build_query($body);
        $digest = $this->generateDigest($encodedBody);
        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $signatureHeader = $this->getSignatureHeader($method, $url, $date, $digest);
        $headers = [
            ...$signatureHeader,
            'Date' => $date,
            'Digest' => $digest,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ];

        return $this->ingClient->request($method, $this->clientBase . $url, [
            'body' => $encodedBody,
            'headers' => $headers,
            'local_pk' => $this->privateKeyTls,
            'local_cert' => $this->certificateTls
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getGeneralAccessToken(): string
    {
        $request = $this->request(
            'POST',
            '/oauth2/token',
            [
                'grant_type' => 'client_credentials',
            ]
        );

        return $request->getContent();
    }

    public function generateDigest(string $body): string
    {
        return 'SHA-256=' . base64_encode(hash('sha256', $body, true));
    }

    public function getLoginScreen()
    {

    }

    /**
     * @throws Exception
     */
    public function getSignature(string $method, string $url, string $date, string $digest): string
    {
        $method = strtolower($method);
        $string = trim("(request-target): $method $url\ndate: $date\ndigest: $digest");
        $privateKey = openssl_pkey_get_private(file_get_contents($this->pemSigningPath));
        openssl_sign($string, $encrypted, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($encrypted);
    }

    /**
     * @throws Exception
     */
    public function getSignatureHeader(string $method, string $url, string $date, string $digest): array
    {
        $clientId = $this->oauthClientId;
        $signature = $this->getSignature($method, $url, $date, $digest);
        return ['Authorization' => "Signature keyId=\"$clientId\",algorithm=\"rsa-sha256\",headers=\"(request-target) date digest\",signature=\"$signature\""];
    }
}