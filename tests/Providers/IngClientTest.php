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
    public function testGetSignature(): void
    {
        $kernel = self::bootKernel();
        $ingClient = static::getContainer()->get(IngClient::class);
        $this->assertEquals([
            'authorization' => 'Signature keyId="5ca1ab1e-c0ca-c01a-cafe-154deadbea75",algorithm="rsa-sha256",headers="(request-target) date digest",signature="T9q4eglBin/DS85oN649MfsWRJCl150rL1SAjI30e8UXNRW21468KBiUPPImIOCHo6PTpAZnVQhaRrICoY4zVBWLHHcePNb59x9lEoV2u/uZvsySMgsEQu3oRbeA1GWa+Tl3Y9nSjmSOj2oY10TGtuG46tQueXvtOnNlQHpQihkt2OLjwanh1e+3vgw1tnvD80huTAVKZ3oOJdFLnL69yUK14FugTRu6fojabuCqkoDIoVZJgy4du/7UtVHq31eNwvLwRBZS9MkNgfheYHgImGq0Z8/Mu6wLXgLoirQxrzMPjAadMOQu7rvThBAImMdIjrFDDJIHcoXsxEMWib/MiQ=="'
        ], $ingClient->getSignatureHeader('post', '/foo?param=value&pet=dog', 'Sun, 05 Jan 2014 21:31:40 GMT', 'SHA-256=X48E9qOokqqrvdts8nOJRJN3OWDUoyWxBf7kbu9DBPE='));
    }
}
