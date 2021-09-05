<?php

namespace App\Yahoo;

use App\EmailBodyParser;
use App\EmailTransformer;
use App\Entity\Email;

class YahooTransformer implements EmailTransformer
{
    private $delimiter = '<<<<<<';

    private EmailBodyParser $parser;

    public function __construct(EmailBodyParser $parser)
    {
        $this->parser = $parser;
    }

    public function fromArray(array $data): Email
    {

        $data['sender'] = isset($data['y_sender']) ? $data['y_sender'] : '';
        $data['recipients'] = isset($data['y_recipients']) ? explode(',', $data['y_recipients']) : [];
        $data['cc'] = isset($data['y_cc']) ? explode(',', $data['y_cc']) : [];
        $data['bcc'] = isset($data['y_bcc']) ? explode(',', $data['y_bcc']) : [];
        $data['subject'] = isset($data['y_subject']) ? $data['y_subject'] : '';
        $data['body'] = isset($data['y_body']) ? $data['y_body'] : '';

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
        return 'yahoo' === strtolower($platform);
    }
}
