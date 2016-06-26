<?php
namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\ForgotPassword\Usecases\ClearTokens;
use Extasy\Model\NotFoundException;
use Extasy\ForgotPassword\Token;
class ClearTokensTest extends BaseTest
{
    public function testClear() {
        $this->fixtureTokens([
            ['invalidate_date' => $this->toDate('-1 minute')],
            ['invalidate_date' => $this->toDate('-1 second')],
            ['invalidate_date' => $this->toDate('+1 minute')],
        ]);
        $usecase = new ClearTokens( $this->tokenRepository);

        $usecase->execute();
        //
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