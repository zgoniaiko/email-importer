<?php

namespace App\Outlook;

use App\EmailTransformer;
use App\Entity\Email;

class OutlookTransformer implements EmailTransformer
{
    private $delimiter = '=====';

    public function fromArray(array $data): Email
    {
        $data['sender'] = isset($data['o_sender']) ? $data['o_sender'] : '';
        $data['recipients'] = isset($data['o_recipients']) ? explode(',', $data['o_recipients']) : [];
        $data['cc'] = isset($data['o_cc']) ? explode(',', $data['o_cc']) : [];
        $data['bcc'] = isset($data['o_bcc']) ? explode(',', $data['o_bcc']) : [];
        $data['subject'] = isset($data['o_subject']) ? $data['o_subject'] : '';
        $data['body'] = isset($data['o_body']) ? $data['o_body'] : '';

        $data['body'] = $this->parser->parse($data['body'], $this->delimiter);

        $email = new Email();
        $email->setSender($data['sender']);
        $email->setRecipients($data['recipients']);
        $email->setCc($data['cc']);
        $email->setBcc($data['bcc']);
        $email->setSubject($data['subject']);
        $email->setBody($data['body']);

        return $email;
    }

    public function supports($platform): bool
    {
        return 'outlook' === strtolower($platform);
    }
}
