<?php

namespace App\Tests\Providers;

use App\Providers\IngClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngClientTest extends KernelTestCase
{

    public function testFetchingAccessToken(): void
    {
        $kernel = self::bootKernel();

//        $this->assertSame('test', $kernel->getEnvironment());
         $ingClient = static::getContainer()->get(IngClient::class);
         $token = $ingClient->getGeneralAccessToken();

         $this->assertNotNull($token);
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testGenerateDigest(): void
    {
        $ingClientMock = $this->createPartialMock(IngClient::class, []);
        $this->assertEquals('SHA-256=47DEQpj8HBSa+/TImW+5JCeuQeRkm5NMpJWZG3hSuFU=', $ingClientMock->generateDigest(''));
    }

    /**
     * @throws \Exception
     */
    public function testSignature(): void
    {
        $kernel = self::bootKernel();
        $ingClient = static::getContainer()->get(IngClient::class);

        $method = "post";
        $url = "/foo?param=value&pet=dog";
        $date = "Sun, 05 Jan 2014 21:31:40 GMT";
        $digest = "SHA-256=X48E9qOokqqrvdts8nOJRJN3OWDUoyWxBf7kbu9DBPE=";

        $data = trim("(request-target): $method $url\ndate: $date\ndigest: $digest");
        $signature = base64_decode($ingClient->getSignature($method, $url, $date, $digest));
        $certPath = $_ENV['PEM_PATH_SIGNING'];
        $publicKey = openssl_get_publickey(file_get_contents($certPath));

        $verify = openssl_verify($data, $signature, $publicKey, OPENSSL_ALGO_SHA256);


        $this->assertTrue((bool)$verify);
    }
}
