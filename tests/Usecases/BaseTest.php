<?php


namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\ForgotPassword\tests\TokensHelperTrait;
use \PHPUnit_Framework_TestCase;
use Extasy\ForgotPassword\tests\Samples\SampleRepository;
use Extasy\Users\tests\BaseTest as UsersBaseTest;

abstract class BaseTest extends UsersBaseTest
{
    /**
     * @var SampleRepository
     */
    protected $tokenRepository = null;
    use TokensHelperTrait;

    public function setUp()
    {
        parent::setUp();
        $this->tokenRepository = new SampleRepository();
    }
}