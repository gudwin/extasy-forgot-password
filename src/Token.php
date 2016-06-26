<?php


namespace Extasy\ForgotPassword;

use Extasy\Model\Model;

/**
 * Class Token
 * @package Extasy\ForgotPassword
 * @property \Extasy\Model\Columns\Index $id
 * @property \Extasy\Model\Columns\BaseColumn $user_id
 * @property \Extasy\Model\Columns\Input $token
 * @property \Extasy\Model\Columns\Datetime $invalidate_date
 * @property \Extasy\Model\Columns\Input $hash
 */
class Token extends Model
{
    public function getFieldsInfo()
    {
        return [
            'fields' => [
                'id' => '\\Extasy\\Model\\Columns\\Index',
                'user_id' => [
                    'class' => '\\Extasy\\Model\\Columns\\BaseColumn',
                    'parse_field' => 'getValue',
                ],
                'token' => [
                    'class' => '\\Extasy\\Model\\Columns\\Input',
                    'parse_field' => 'getValue',
                ],
                'hash' => [
                    'class' => '\\Extasy\\Model\\Columns\\Input',
                    'parse_field' => 'getValue',
                ],
                'invalidate_date' => [
                    'class' => '\\Extasy\\Model\\Columns\\Datetime',
                    'parse_field' => 'getValue',
                ]
            ]
        ];
    }
}