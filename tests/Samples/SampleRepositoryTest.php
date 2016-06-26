<?php
namespace Extasy\ForgotPassword\tests\Samples;

use Extasy\ForgotPassword\tests\Integrational\RepositoryTest;

class SampleRepositoryTest extends RepositoryTest
{
    public function factoryRepository() {
        return new SampleRepository();
    }
}