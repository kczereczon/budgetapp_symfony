<?php

namespace App\Tests\Providers;

use App\Providers\IngClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngClientTest extends KernelTestCase
{

    public function testSomething(): void
    {
        $kernel = self::bootKernel();

//        $this->assertSame('test', $kernel->getEnvironment());
         $ingClient = static::getContainer()->get(IngClient::class);
         $token = $ingClient->getGeneralAccessToken();
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
    public function testGetSignatureDigest(): void
    {
        $kernel = self::bootKernel();
        $ingClient = static::getContainer()->get(IngClient::class);
        $this->assertEquals([
            'Authorization' => 'Signature keyId="asdf", algorithm="rsa-sha256",headers="(request-target) date digest", signature="oNzci3xvxD6CqQjYmLXXRFOtQYttDXE2am87H85OtGb3HTx4Wktmghnyxq4Jzo3ZP6L7JQzu6sHKv5M70lkbh1J7hUPOIKTlN4NIew++2kWrOkfODvSxCKQJm42xNwSB/Iw5qXPih4XlgLFQvkDJzn0v2hlt1aUyGA0LawAQ1fq6tTW6XPVvwSgEN7Cpa4xn+U7MK9s+rpPICjbyMPwQkvMuXEvTq5Vr5+nQgUjyq4dJVJtj/tKWO4zpKlABRWpB1JJjobQOVK/KW6Tpl1StTnQlGcTlEcw7/96bYAFnDPLtrB/O6PpEWwjWg0lg7ZVoHMRWtHXchqfGJsSAQq6wfw=="'
        ], $ingClient->getSignatureHeader('post', '/foo?param=value&pet=dog', 'Sun, 05 Jan 2014 21:31:40 GMT', 'SHA-256=X48E9qOokqqrvdts8nOJRJN3OWDUoyWxBf7kbu9DBPE='));
    }
}
