<?php
declare(strict_types=1);

namespace ConsultMee\Services;

class MailService
{
    private string $from;
    private string $fromName;
    private string $replyTo;

    public function __construct()
    {
        $cfg = require APP_ROOT . '/config/mail.php';
        $this->from     = $cfg['from'];
        $this->fromName = $cfg['from_name'];
        $this->replyTo  = $cfg['reply_to'];
    }

    public function send(string $to, string $subject, string $htmlBody): bool
    {
        $headers  = "From: {$this->fromName} <{$this->from}>\r\n";
        $headers .= "Reply-To: {$this->replyTo}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return @mail($to, $subject, $htmlBody, $headers);
    }
}
