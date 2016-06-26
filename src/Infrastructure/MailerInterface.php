<?php
namespace Extasy\ForgotPassword\Infrastructure;

interface MailerInterface
{
    public function send($recipients, $subject, $content );
}