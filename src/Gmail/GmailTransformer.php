<?php

namespace App\Gmail;

use App\EmailTransformer;
use App\Entity\Email;

class GmailTransformer implements EmailTransformer
{
    private $delimiter = '>>>>>';

    public function fromArray(array $data): Email
    {
        $data['sender'] = isset($data['g_sender']) ? $data['g_sender'] : '';
        $data['recipients'] = isset($data['g_recipients']) ? explode(',', $data['g_recipients']) : [];
        $data['cc'] = isset($data['g_cc']) ? explode(',', $data['g_cc']) : [];
        $data['bcc'] = isset($data['g_bcc']) ? explode(',', $data['g_bcc']) : [];
        $data['subject'] = isset($data['g_subject']) ? $data['g_subject'] : '';
        $data['body'] = isset($data['g_body']) ? $data['g_body'] : '';

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
        return 'gmail' === strtolower($platform);
    }
}
