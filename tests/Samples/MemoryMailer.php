<?php


namespace Extasy\ForgotPassword\tests\Samples;


use Extasy\ForgotPassword\Infrastructure\MailerInterface;

class MemoryMailer implements MailerInterface
{
    protected $recipients = [];
    protected $subject = '';
    protected $content = '';
    public function send($recipients, $subject, $content ) {
        $this->recipients = $recipients;
        $this->subject = $subject;
        $this->content = $content;
    }
    public function getRecipients() {
        return $this->recipients;
    }
    public function getSubject() {
        return $this->subject;
    }
    public function getContent() {
        return $this->content;
    }
}