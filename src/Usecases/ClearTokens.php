<?php
namespace Extasy\ForgotPassword\Usecases;

use Extasy\ForgotPassword\Infrastructure\TokenRepositoryInterface;
use Extasy\Usecase\Usecase;

class ClearTokens
{
    use Usecase;
    /**
     * @var TokenRepositoryInterface
     */
    protected $tokenRepository = null;
    public function __construct( $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }


    protected function action() {
        $this->tokenRepository->cleanup();
    }
}