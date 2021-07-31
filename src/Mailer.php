<?php

namespace Mailer;

use Madlib\Page;
use Madlib\Message;
use Madlib\Mailer as MadMailer;
use Madlib\Input;
use Madlib\Validator;
use Madlib\Mysql;
use Madlib\Session;

class Mailer {

    protected Page $page;
    protected Message $message;
    protected MadMailer $mailer;
    protected Input $input;
    protected Validator $validator;
    protected Mysql $mysql;
    protected Session $session;

    public function __construct(Page $page, Message $message, MadMailer $mailer, Input $input, Validator $validator, Mysql $mysql, Session $session) {
        $this->page = $page;
        $this->message = $message;
        $this->mailer = $mailer;
        $this->input = $input;
        $this->validator = $validator;
        $this->mysql = $mysql;
        $this->session = $session;
    }

    public function view(array $data = []): void {
        $this->page->show('mailer/mailer', $data);
    }

    public function send(): void {
        $user_id = (int)$this->session->get('user_id');
        $to_email = $this->input->getString('to_email');
        $from_email = $this->input->getString('from_email');
        $from_name = $this->input->getString('from_name');
        $subject = $this->input->getString('subject');
        $body = $this->input->getString('body');

        if (
            !$this->validator->validate('required', $user_id) ||
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
            INSERT INTO sent_mail (user_id, from_email, from_name, to_email, subject, body) 
            VALUES ('$user_id', '$from_email', '$from_name', '$to_email', '$subject', '$body')
        ");
        
        $phpmailer = $this->mailer->getPhpMailer();
        $phpmailer->setFrom($from_email, $from_name);
        $phpmailer->addReplyTo($from_email, $from_name);
        $phpmailer->addAddress($to_email);
        $phpmailer->Subject = $subject;
        $phpmailer->Body = \nl2br($body);
        $phpmailer->AltBody = $body;

        $phpmailer->send();
        $this->message->success('Mail sent');

        $this->view();
    }
}