<?php
namespace Extasy\ForgotPassword\Usecases;

use Extasy\Users\Columns\Password;
use Faid\UParser;
use \InvalidArgumentException;
use Extasy\Usecase\Usecase;
use Extasy\ForgotPassword\Token;
use Extasy\ForgotPassword\Infrastructure\MailerInterface;
use Extasy\ForgotPassword\Infrastructure\TokenRepositoryInterface;

class SendPassword
{
    use Usecase;

    /**
     * @var SendPasswordRequest|null
     */
    protected $request = null;

    /**
     * SendPassword constructor.
     */
    public function __construct(SendPasswordRequest $request)
    {
        if (!$request->isValid()) {
            throw new InvalidArgumentException('Request invalid');
        }
        $this->request = $request;
    }

    protected function action()
    {
        $token = $this->request->tokenRepository->getByHash($this->request->hash);
        $user = $this->request->usersRepository->get($token->id->getValue());
        $password = Password::generatePassword();
        $user->password = $password;
        $this->request->usersRepository->update( $user );
        $this->request->tokenRepository->delete($token);
        //
        $parseData = [
            'user' => $user->getParseData(),
            'password' => $password,
        ];
        $subject = UParser::parsePHPCode($this->request->subjectTemplate, $parseData);
        $content = UParser::parsePHPCode($this->request->emailTemplate, $parseData);
        $this->request->mailer->send([$user->email->getValue()], $subject, $content);
        $this->request->tokenRepository->delete($token);
    }
}