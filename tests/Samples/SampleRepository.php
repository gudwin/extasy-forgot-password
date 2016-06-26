<?php


namespace Extasy\ForgotPassword\tests\Samples;

use Extasy\ForgotPassword\Token;
use Extasy\ForgotPassword\Infrastructure\TokenRepositoryInterface;
use Extasy\Model\NotFoundException;

class SampleRepository implements TokenRepositoryInterface
{
    protected $autoIncrement = 1;
    /**
     * @var Token[]
     */
    protected $data = [];
    public function insert(Token $token) {
        $this->data[] = $token;
        $token->id = $this->autoIncrement;
        $this->autoIncrement++;
    }
    public function delete( Token $token ) {
        foreach ( $this->data as $key=>$row ) {
            if ( $token == $row ) {
                unset( $this->data[$key]);
            }
        }
    }

    public function get($index) {
        foreach ( $this->data as $key=>$row ) {
            if ( $index == $row->id->getValue() ) {
                return $row;
            }
        }
        throw new NotFoundException('Not found');
    }
    public function getByHash( $hash ) {
        foreach ( $this->data as $key=>$row ) {
            if ( $hash == $row->hash->getValue() ) {
                return $row;
            }
        }
        throw new NotFoundException('Not found');
    }

    public function cleanup() {
        $time = date('Y-m-d H:i:s', time());
        $this->data = array_filter( $this->data, function ( $row ) use ( $time ) {
            return ( $row->invalidate_date->getValue() > $time );
        });
    }
}