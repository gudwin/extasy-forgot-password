<?php
namespace Extasy\ForgotPassword\tests;

use Extasy\ForgotPassword\Token;
trait TokensHelperTrait
{
    protected function fixtureTokens($data)
    {
        foreach ($data as $row) {
            $token = new Token($row);
            $this->tokenRepository->insert($token);
        }
    }

    protected function toDate($str)
    {
        return date('Y-m-d H:i:s', strtotime($str));

    }

}