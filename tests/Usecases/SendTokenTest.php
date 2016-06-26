<?php
namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\Users\User;
use Extasy\ForgotPassword\tests\Samples\MemoryMailer;
use Extasy\ForgotPassword\Usecases\SendToken;
use Extasy\ForgotPassword\Usecases\SendTokenRequest;

class SendTokenTest extends BaseTest
{
    const SubjectFixture = 'hello from send token!';
    const ContentFixture = '<?=$user["email"]?> - <?=$token["hash"]?>';
    const MailFixture = 'send-token@test.com';

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSendWithInvalidRequest()
    {
        $request = $this->factoryValidRequest();
        $request->tokenRepository = null;
        $usecase = new SendToken($request);
        $usecase->execute();
    }

    public function testSend()
    {
        $request = $this->factoryValidRequest();
        $usecase = new SendToken($request);
        $usecase->execute();

        $recipients = $request->mailer->getRecipients();
        $subject = $request->mailer->getSubject();
        $content = $request->mailer->getContent();

        // Token created!
        $token = $request->tokenRepository->get(1);

        $expectedContent = sprintf('%s - %s', self::MailFixture, $token->hash->getValue());

        $this->AssertTrue(is_array($recipients));
        $this->assertEquals(self::MailFixture, $recipients[0]);
        $this->assertEquals(self::SubjectFixture, $subject);
        $this->assertEquals($expectedContent, $content);

    }

    /**
     * @return SendTokenRequest
     */
    protected function factoryValidRequest()
    {
        $sendPasswordRequest = new SendTokenRequest();
        $sendPasswordRequest->user = new User(['email' => self::MailFixture], $this->configurationRepository);
        $sendPasswordRequest->subjectTemplate = self::SubjectFixture;
        $sendPasswordRequest->tokenTimeout = 3600;
        $sendPasswordRequest->emailTemplate = self::ContentFixture;
        $sendPasswordRequest->tokenRepository = $this->tokenRepository;
        $sendPasswordRequest->mailer = new MemoryMailer();
        $sendPasswordRequest->salt = '1234';

        return $sendPasswordRequest;
    }
}