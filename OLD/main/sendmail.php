<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $subject = htmlspecialchars($_POST['subject'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // Compose admin message
    $adminTo = "info@consultmee.in";
    $adminSubject = "New Contact Form Submission - $subject";
    $adminBody = "
        New message from the ConsultME website:<br><br>
        <b>Name:</b> $name<br>
        <b>Email:</b> $email<br>
        <b>Subject:</b> $subject<br>
        <b>Message:</b><br>$message
    ";
    $adminHeaders = "From: no-reply@consultmee.in\r\n";
    $adminHeaders .= "Reply-To: $email\r\n";
    $adminHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Send to Admin
    $adminSent = mail($adminTo, $adminSubject, $adminBody, $adminHeaders);

    // Send Acknowledgment to User
    $userSubject = "Thank you for contacting ConsultME";
    $userBody = "
        <h3>Dear $name,</h3>
        <p>Thank you for reaching out to ConsultME.</p>
        <p>We have received your message and will respond soon.</p>
        <blockquote>$message</blockquote>
        <p>Warm regards,<br><b>ConsultME Team</b></p>
    ";
    $userHeaders = "From: no-reply@consultmee.in\r\n";
    $userHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

    $userSent = mail($email, $userSubject, $userBody, $userHeaders);

    if ($adminSent && $userSent) {
        echo "<script>alert('Your message has been sent successfully!'); window.location='https://consultmee.in/contact';</script>";
    } else {
        echo "<script>alert('Mail sending failed. Please check your configuration.'); window.history.back();</script>";
    }
}
?>