<?php
namespace Extasy\ForgotPassword\Infrastructure;

use Extasy\ForgotPassword\Token;

interface TokenRepositoryInterface
{
    public function insert(Token $token);

    /**
     * @param $index
     * @return Token
     */
    public function get($index);

    public function delete( Token $token );

    /**
     * @param $hash
     * @return Token
     */
    public function getByHash( $hash );

    public function cleanup();
}