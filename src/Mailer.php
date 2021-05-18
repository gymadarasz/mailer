<?php

namespace Mailer;

use Madlib\Page;
use Madlib\Message;
use Madlib\Mailer as MadMailer;
use Madlib\Input;
use Madlib\Validator;

class Mailer {

    protected Page $page;
    protected Message $message;
    protected MadMailer $mailer;
    protected Input $input;
    protected Validator $validator;

    public function __construct(Page $page, Message $message, MadMailer $mailer, Input $input, Validator $validator) {
        $this->page = $page;
        $this->message = $message;
        $this->mailer = $mailer;
        $this->input = $input;
        $this->validator = $validator;
    }

    public function view(): void {
        $this->page->show('mailer/mailer');
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
            $this->view();
            return;
        }
        
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