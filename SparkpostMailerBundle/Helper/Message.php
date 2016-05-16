<?php
/**
 * Created by PhpStorm.
 * User: pretorian41
 * Date: 3/29/16
 * Time: 4:27 PM
 */

namespace Braem\SparkpostMailerBundle\Helper;

//curl -X POST \
//https://api.sparkpost.com/api/v1/transmissions \
//  -H "Authorization: fc16b2e9de58e5a878b45236ee28308a9e2c0171" \
//-H "Content-Type: application/json" \
//-d '{
//    "content": {
//      "from": "sandbox@sparkpostbox.com",
//      "subject": "Thundercats are GO!!!",
//      "text": "Sword of Omens, give me sight BEYOND sight"
//    },
//    "recipients": [{ "address": "pretorian42@gmail.com" }]
//  }'

class Message
{

    private $recipients = array();
    private $subject;
    private $html;
    private $fromEmail;
    private $companyName;
    private $bccAddress;

    /**
     * @return mixed
     */
    public function getBccAddress()
    {
        return $this->bccAddress;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    public function setBccAddress($bccAddress)
    {
        $this->bccAddress = $bccAddress;
        $this->addTo($bccAddress, 'BCC '. $bccAddress);
    }

    /**
     * @param mixed $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }



    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function addTo($email, $name = '', $type = 'to')
    {
        $recipient = array();
        $recipient['address'] = $email;
        $recipient['name'] = $name;
        $this->recipients[] = $recipient;
        return $this;
    }

    public function setFromName($fromName)
    {
        $this->companyName = $fromName;
        return $this;
    }

    public function getTo()
    {
        return $this->recipients;
    }

    public function resetRecipients()
    {
         $this->recipients = array();
         return $this;
    }

    public function getContent()
    {
        $content = array(
            'content' => array(
                'from' => $this->getFromEmail(),
                'subject'=> $this->getSubject(),
                'html' => $this->getHtml(),
            )
        );

        return $content;
    }

    public function getPreparedRecipients()
    {
        $return = array();
        foreach($this->getTo() as $email){
            $return[] = array(
                'address' => $email
            );
        }
        return $return;
    }

    public function getMessageBody()
    {
        return array_merge($this->getContent(), array('recipients' => $this->getTo()));
    }

}
