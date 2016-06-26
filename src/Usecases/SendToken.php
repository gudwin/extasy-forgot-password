<?php
namespace Extasy\ForgotPassword\Usecases;

use Extasy\ForgotPassword\Token;
use Extasy\Usecase\Usecase;
use Faid\UParser;
use \InvalidArgumentException;

class SendToken
{
    use Usecase;
    /**
     * @var SendTokenRequest
     */
    protected $request = null;

    public function __construct(SendTokenRequest $request)
    {
        $this->request = $request;
        if (!$this->request->isValid()) {
            throw new InvalidArgumentException('Request invalid');
        }
    }

    protected function action()
    {
        $token = $this->generateToken();
        $this->request->tokenRepository->insert($token);
        $email = $this->request->user->email->getValue();

        $parseData = [
            'user' => $this->request->user->getParseData(),
            'token' => $token->getParseData()
        ];
        $content = UParser::parsePHPCode($this->request->emailTemplate, $parseData);
        $subject = UParser::parsePHPCode($this->request->subjectTemplate, $parseData);
        $this->request->mailer->send([$email], $subject, $content);

    }

    /**
     * @return Token
     */
    protected function generateToken()
    {
        $usecase = new GenerateToken($this->request->user, $this->request->salt, $this->request->tokenTimeout);

        return $usecase->execute();
    }


}