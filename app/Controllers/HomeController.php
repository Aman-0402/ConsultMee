<?php
declare(strict_types=1);

namespace ConsultMee\Controllers;

use ConsultMee\Core\Controller;
use ConsultMee\Services\MailService;

class HomeController extends Controller
{
    public function home(): void
    {
        $this->view('pages/home', ['title' => 'ConsultMee - Find Expert Consultants']);
    }

    public function about(): void
    {
        $this->view('pages/about', ['title' => 'About Us | ConsultMee']);
    }

    public function services(): void
    {
        $this->view('pages/services', ['title' => 'Services | ConsultMee']);
    }

    public function contact(): void
    {
        $this->view('pages/contact', [
            'title'   => 'Contact Us | ConsultMee',
            'success' => get_flash('success'),
            'error'   => get_flash('error'),
        ]);
    }

    public function sendContact(): void
    {
        $name    = trim($this->request()->post('name', ''));
        $email   = trim($this->request()->post('email', ''));
        $subject = trim($this->request()->post('subject', 'Contact Form Enquiry'));
        $message = trim($this->request()->post('message', ''));

        if (empty($name) || empty($email) || empty($message)) {
            set_flash('error', 'Please fill in all fields.');
            $this->redirect('/contact');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Invalid email address.');
            $this->redirect('/contact');
            return;
        }

        $mail = new MailService();
        $body = "<p><strong>Name:</strong> " . e($name) . "</p>"
              . "<p><strong>Email:</strong> " . e($email) . "</p>"
              . "<p><strong>Subject:</strong> " . e($subject) . "</p>"
              . "<p><strong>Message:</strong><br>" . nl2br(e($message)) . "</p>";

        $mail->send('info@consultmee.in', e($subject) . ' — from ' . e($name), $body);

        set_flash('success', 'Thank you! We will get back to you soon.');
        $this->redirect('/contact');
    }
}
