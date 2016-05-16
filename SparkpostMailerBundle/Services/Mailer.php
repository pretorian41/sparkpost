<?php
namespace Braem\SparkpostMailerBundle\Services;

use Braem\SparkpostMailerBundle\Helper\Message;

class Mailer
{
    private $apiKey;
    private $sendingDomain;
    private $sparkPostUri;
    private $fromName;
    private $fromEmail;
    private $emailForTestEnv;
    private $environment;
    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * @param mixed $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    public function getEmailForTestEnv()
    {
        return $this->emailForTestEnv;
    }

    /**
     * @param mixed $emailForTestEnv
     */
    public function setEmailForTestEnv($emailForTestEnv)
    {
        $this->emailForTestEnv = $emailForTestEnv;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param mixed $emailForTestEnv
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return mixed
     */
    public function getFromName()
    {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }


    public function send(Message $message)
    {
        $this->writeLog('send mail');

        if ($this->getEnvironment() == 'dev' || $this->getEnvironment() == 'test') {
            $email = $this->getEmailForTestEnv();
            $emails = explode(',', $email);
            $message->resetRecipients();

            foreach ($emails as $email) {
                $message->addTo($email);
            }

            $bcc = $message->getBccAddress();

            if ($bcc) {
                $message->addTo($bcc, 'BCC '. $bcc);        
            }
        }

        $message->setCompanyName($this->getFromName());

        if (is_null($message->getFromEmail())) {
            $message->setFromEmail($this->getFromEmail());
        }

        $messageBody = $message->getMessageBody();
        $this->writeLog(var_export($messageBody, TRUE));

        $ch = $this->buildBaseBaseCurlSession($messageBody);
        $result = $this->curlExecute($ch);

        $this->writeLog($result);

        if (false === $result){
            $this->writeError(curl_error($ch));
        }

        return $result;
    }

    public function writeLog($msg)
    {
        $dt = new \DateTime();

        $msg = sprintf('[%s] %s', $dt->format('Y/m/d H:i:s'), $msg);

        file_put_contents('sparkpost.log', $msg.PHP_EOL, FILE_APPEND);
    }

    public function buildBaseBaseCurlSession($data)
    {
        $ch = curl_init();
        $data_string = json_encode($data);

        curl_setopt($ch, CURLOPT_URL, $this->sparkPostUri);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                sprintf('Authorization: %s', $this->apiKey),
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch, CURLOPT_FTPLISTONLY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);

        return $ch;
    }

    public function curlExecute($ch)
    {
        $result = curl_exec($ch);
        return $result;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @param mixed $sparkPostUri
     */
    public function setSparkPostUri($sparkPostUri)
    {
        $this->sparkPostUri = $sparkPostUri;
    }

    public function setSendingDomain($sendingDomain)
    {
        $this->sendingDomain = $sendingDomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSparkPostUri()
    {
        return $this->sparkPostUri;
    }
}
