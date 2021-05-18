<?php

namespace Mailer;

use Madlib\Page;
use Madlib\Message;
use Madlib\Mailer as MadMailer;
use Madlib\Input;
use Madlib\Validator;
use Madlib\Mysql;

class Mailer {

    protected Page $page;
    protected Message $message;
    protected MadMailer $mailer;
    protected Input $input;
    protected Validator $validator;
    protected Mysql $mysql;

    public function __construct(Page $page, Message $message, MadMailer $mailer, Input $input, Validator $validator, Mysql $mysql) {
        $this->page = $page;
        $this->message = $message;
        $this->mailer = $mailer;
        $this->input = $input;
        $this->validator = $validator;
        $this->mysql = $mysql;
    }

    public function view(array $data = []): void {
        $this->page->show('mailer/mailer', $data);
    }

    public function send(): void {
        $to_email = $this->input->getString('to_email');
        $from_email = $this->input->getString('from_email');
        $from_name = $this->input->getString('from_name');
        $subject = $this->input->getString('subject');
        $body = $this->input->getString('body');

        if (
            !$this->validator->validate('required', $to_email) ||
            !$this->validator->validate('email', $to_email) ||
            !$this->validator->validate('required', $from_email) ||
            !$this->validator->validate('email', $from_email) ||
            !$this->validator->validate('required', $subject) ||
            !$this->validator->validate('required', $body)
        ) {
            $this->message->error('Invalid input(s)');
            $this->view([
                'to_email' => $to_email,
                'from_email' => $from_email,
                'from_name' => $from_name,
                'subject' => $subject,
                'body' => $body,
            ]);
            return;
        }

        $this->mysql->insert("
            INSERT INTO sent_mail (from_email, from_name, to_email, subject, body) 
            VALUES ('$from_email', '$from_name', '$to_email', '$subject', '$body')
        ");
        
        $phpmailer = $this->mailer->getPhpMailer();
        $phpmailer->setFrom($from_email, $from_name);
        $phpmailer->addReplyTo($from_email, $from_name);
        $phpmailer->addAddress($to_email);
        $phpmailer->Subject = $subject;
        $phpmailer->Body = $body;
        $phpmailer->AltBody = $body;

        $phpmailer->send();
        $this->message->success('Mail sent');

        $this->view();
    }
}