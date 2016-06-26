<?php


namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\Users\User;
use Extasy\ForgotPassword\tests\Samples\MemoryMailer;
use Extasy\ForgotPassword\Usecases\SendTokenRequest;
use Extasy\Users\tests\Samples\MemoryUsersRepository;

class SendTokenRequestTest extends BaseTest
{
    const MailFixture = 'test@test.com';
    /**
     * @return SendTokenRequest
     */
    protected function factoryValidRequest() {
        $sendPasswordRequest = new SendTokenRequest();
        $sendPasswordRequest->user = new User(['email' => self::MailFixture], $this->configurationRepository);
        $sendPasswordRequest->subjectTemplate = 'subject';
        $sendPasswordRequest->emailTemplate = 'content';
        $sendPasswordRequest->salt = 'Hello world!';
        $sendPasswordRequest->tokenRepository = $this->tokenRepository;
        $sendPasswordRequest->mailer = new MemoryMailer();
        $sendPasswordRequest->tokenTimeout = 100;

        return $sendPasswordRequest;
    }
    public function testIsValid() {
        $properties = ['user', 'tokenRepository','mailer','salt'];
        foreach ( $properties as $property ) {
            $requst = $this->factoryValidRequest();
            $requst->$property = null;
            $this->assertFalse( $requst->isValid() );
        }
        //
        $properties = [ 'tokenRepository', 'mailer'];
        foreach ( $properties as $property ) {
            $requst = $this->factoryValidRequest();
            $requst->$property = new \stdClass();
            $this->assertFalse( $requst->isValid() );
        }
        $request = $this->factoryValidRequest();
        $this->assertTrue( $request->isValid() );
    }
}