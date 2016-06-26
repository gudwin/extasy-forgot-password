<?php


namespace Extasy\ForgotPassword\Usecases;

use Extasy\ForgotPassword\Infrastructure\TokenRepositoryInterface;
use Extasy\Users\User;
use Extasy\Users\Configuration\ConfigurationRepository;
use Extasy\ForgotPassword\Infrastructure\MailerInterface;

class SendTokenRequest
{

    /**
     * @var TokenRepositoryInterface
     */
    public $tokenRepository = null;
    /**
     * @var User
     */
    public $user = null;
    /**
     * @var MailerInterface
     */
    public $mailer = null;

    public $subjectTemplate = '';
    public $emailTemplate = '';
    public $salt = '';
    public $tokenTimeout = 0;


    public function isValid()
    {
        $result = !empty($this->user) && !empty($this->tokenRepository) && !empty($this->mailer) && !empty( $this->salt )
            && !empty( $this->tokenTimeout );
        $result = $result && ($this->tokenRepository instanceof TokenRepositoryInterface);
        $result = $result && ($this->user instanceof User);
        $result = $result && ($this->mailer instanceof MailerInterface);

        return $result;
    }
}