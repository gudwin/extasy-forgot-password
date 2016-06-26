<?php


namespace Extasy\ForgotPassword\Usecases;


use Extasy\ForgotPassword\Infrastructure\MailerInterface;
use Extasy\ForgotPassword\Infrastructure\TokenRepositoryInterface;
use Extasy\Users\Configuration\ConfigurationRepository;
use Extasy\Users\RepositoryInterface;

class SendPasswordRequest
{
    public $hash = '';
    /**
     * @var TokenRepositoryInterface
     */
    public $tokenRepository = null;
    /**
     * @var RepositoryInterface
     */
    public $usersRepository = null;
    /**
     * @var ConfigurationRepository
     */
    public $usersConfigurationRepository = '';
    /**
     * @var MailerInterface
     */
    public $mailer = null;

    public $subjectTemplate = '';
    public $emailTemplate = '';


    public function isValid()
    {
        $result = !empty($this->hash) && !empty($this->tokenRepository)
            && !empty($this->usersRepository) && !empty($this->usersConfigurationRepository) && !empty($this->mailer);
        $result = $result && ($this->tokenRepository instanceof TokenRepositoryInterface);
        $result = $result && ($this->usersRepository instanceof RepositoryInterface);
        $result = $result && ($this->usersConfigurationRepository instanceof ConfigurationRepository);
        $result = $result && ($this->mailer instanceof MailerInterface);

        return $result;
    }
}