<?php


namespace Extasy\ForgotPassword\Usecases;

use Extasy\ForgotPassword\Token;
use Extasy\Users\User;
use Extasy\Usecase\Usecase;

class GenerateToken
{
    use Usecase;

    /**
     * @var User
     */
    protected $user = null;
    protected $salt = null;
    protected $secondsTimeout = 0;
    public function __construct( $user, $salt, $secondsTimeout  )
    {
        $this->user = $user;
        $this->salt = $salt;
        $this->secondsTimeout = $secondsTimeout;
    }
    protected function action() {
        $token = new Token();
        $token->hash = crypt(time(),$this->salt);
        $token->user_id = $this->user->id->getValue();
        $token->invalidate_date = date('Y-m-d H:i:s', time() + $this->secondsTimeout);
        return $token;
    }
}