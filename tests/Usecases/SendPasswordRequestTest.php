<?php


namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\ForgotPassword\tests\Samples\MemoryMailer;
use Extasy\ForgotPassword\Usecases\SendPasswordRequest;
use Extasy\Users\tests\Samples\MemoryUsersRepository;

class SendPasswordRequestTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    /**
     * @return SendPasswordRequest
     */
    protected function factoryValidRequest() {
        $sendPasswordRequest = new SendPasswordRequest();
        $sendPasswordRequest->hash = '123456';
        $sendPasswordRequest->subjectTemplate = 'subject';
        $sendPasswordRequest->emailTemplate = 'content';
        $sendPasswordRequest->tokenRepository = $this->tokenRepository;
        $sendPasswordRequest->usersConfigurationRepository = $this->configurationRepository;
        $sendPasswordRequest->usersRepository = new MemoryUsersRepository();
        $sendPasswordRequest->mailer = new MemoryMailer();
        return $sendPasswordRequest;
    }
    public function testIsValid() {
        $properties = ['hash', 'tokenRepository', 'usersConfigurationRepository', 'usersRepository', 'mailer'];
        foreach ( $properties as $property ) {
            $requst = $this->factoryValidRequest();
            $requst->$property = null;
            $this->assertFalse( $requst->isValid(), "Failed on field '$property'" );
        }
        //
        $properties = [ 'tokenRepository', 'usersConfigurationRepository', 'usersRepository', 'mailer'];
        foreach ( $properties as $property ) {
            $requst = $this->factoryValidRequest();
            $requst->$property = new \stdClass();
            $this->assertFalse( $requst->isValid() );
        }
        $request = $this->factoryValidRequest();
        $this->assertTrue( $request->isValid() );
    }
}