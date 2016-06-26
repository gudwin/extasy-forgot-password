<?php
namespace Extasy\ForgotPassword\tests\Usecases;

use Extasy\ForgotPassword\Usecases\SendPassword;
use Extasy\ForgotPassword\tests\Samples\MemoryMailer;
use Extasy\Model\NotFoundException;
use Extasy\Users\tests\Samples\MemoryUsersRepository;
use Extasy\Users\User;
use Extasy\ForgotPassword\Usecases\SendPasswordRequest;

class SendPasswordTest extends BaseTest
{
    const SubjectFixture = 'Hello world!';
    const HashFixture = 123456;
    const MailFixture = 'test@test.com';
    /**
     * @var SendPasswordRequest
     */
    protected $request = null;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSendWithInvalidRequest() {
        $this->request = $this->factoryValidRequest();
        $this->request->tokenRepository = null;
        $usecase = new SendPassword($this->request);
        $usecase->execute();
    }
    public function testWithKnownToken()
    {
        $this->request = $this->factoryValidRequest();
        $user = new User([], $this->request->usersConfigurationRepository);
        $user->email = self::MailFixture;
        $this->request->usersRepository->insert($user);
        $this->fixtureTokens([
            ['user_id' => 1, 'hash' => self::HashFixture]
        ]);
        //
        //
        $usecase = new SendPassword($this->request);
        $usecase->execute();
        //
        $recipients = $this->request->mailer->getRecipients();
        $this->assertTrue(is_array($recipients));
        $this->assertEquals(self::MailFixture, $recipients[0]);

        $subject = $this->request->mailer->getSubject();
        $this->assertEquals(self::SubjectFixture, $subject);
        $content = $this->request->mailer->getContent();
        $this->assertEquals(self::MailFixture, $content);


        try {
            $this->tokenRepository->get(1);
            $this->fail();
        } catch (NotFoundException $e) {

        } catch (\Exception $e) {
            $this->fail();
        }
    }

    /**
     * @return SendPasswordRequest
     */
    protected function factoryValidRequest()
    {
        $sendPasswordRequest = new SendPasswordRequest();
        $sendPasswordRequest->hash = '123456';
        $sendPasswordRequest->subjectTemplate = self::SubjectFixture;
        $sendPasswordRequest->emailTemplate = '<?=$user["email"]?>';
        $sendPasswordRequest->tokenRepository = $this->tokenRepository;
        $sendPasswordRequest->usersConfigurationRepository = $this->configurationRepository;
        $sendPasswordRequest->usersRepository = new MemoryUsersRepository();
        $sendPasswordRequest->mailer = new MemoryMailer();

        return $sendPasswordRequest;
    }
}