<?php
namespace Extasy\ForgotPassword\tests\Integrational;

use Extasy\Model\NotFoundException;
use Extasy\ForgotPassword\tests\TokensHelperTrait;
use PHPUnit_Framework_TestCase;
use Extasy\ForgotPassword\Infrastructure\TokenRepositoryInterface;
use Extasy\ForgotPassword\Token;

abstract class RepositoryTest extends PHPUnit_Framework_TestCase
{
    use TokensHelperTrait;
    /**
     * @var TokenRepositoryInterface
     */
    protected $tokenRepository = null;

    public function setUp()
    {
        parent::setUp();
        $this->tokenRepository = $this->factoryRepository();
    }

    abstract function factoryRepository();


    public function testInsertsGet()
    {
        $fixture1 = 31337;
        $fixture2 = 65535;
        $this->fixtureTokens([
            ['user_id' => $fixture1],
            ['user_id' => $fixture2],
        ]);
        //
        $receivedToken = $this->tokenRepository->get(1);
        $this->assertEquals($fixture1, $receivedToken->user_id->getValue());
        //
        $receivedToken = $this->tokenRepository->get(2);
        $this->assertEquals($fixture2, $receivedToken->user_id->getValue());

    }

    /**
     * @expectedException \Extasy\Model\NotFoundException
     */
    public function testGetByUnknownHash()
    {
        $this->tokenRepository->getByHash('blablabla');
    }

    public function testGetByHash()
    {
        $hash = 31337;
        $hash2 = 65535;
        $this->fixtureTokens([
            ['hash' => $hash],
            ['hash' => $hash2],
        ]);
        //
        $receivedToken = $this->tokenRepository->getByHash($hash);
        $this->assertEquals($hash, $receivedToken->hash->getValue());
        //
        $receivedToken = $this->tokenRepository->getByHash($hash2);
        $this->assertEquals($hash2, $receivedToken->hash->getValue());

    }

    /**
     * @expectedException \Extasy\Model\NotFoundException
     */
    public function testGetWithUknown()
    {
        $this->tokenRepository->get(-1);
    }

    public function testClear()
    {
        $this->fixtureTokens([
            ['invalidate_date' => $this->toDate('-1 minute')],
            ['invalidate_date' => $this->toDate('-1 second')],
            ['invalidate_date' => $this->toDate('+1 minute')],
        ]);
        $this->tokenRepository->cleanup();

        for ($i = 1; $i < 2; $i++) {
            try {
                $this->tokenRepository->get($i);
                $this->fail("$i must be cleared");
            } catch (NotFoundException $e) {

            }
        }
        $result = $this->tokenRepository->get(3);
        $this->assertTrue($result instanceof Token);
    }
}