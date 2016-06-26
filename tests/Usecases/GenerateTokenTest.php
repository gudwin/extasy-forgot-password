<?php


namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\ForgotPassword\Usecases\GenerateToken;
use Extasy\Users\User;
use Extasy\Users\tests\Samples\MemoryConfigurationRepository;

class GenerateTokenTest extends BaseTest
{
    public function testGenerate()
    {
        $salt = '123';
        $id = 66;

        $repository = new MemoryConfigurationRepository();
        $user = new User(['id' => $id], $repository);

        $timestamp = time();
        $usecase = new GenerateToken($user, $salt, 10);
        $token = $usecase->execute();
        $this->assertNotEmpty($token->hash->getValue());
        $this->assertEquals($id, $token->user_id->getValue());

        $date = strtotime($token->invalidate_date->getValue());

        $this->assertTrue(($date - $timestamp >= 9) and ($date - $timestamp <= 11));
    }
}