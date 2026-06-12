<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$name    = trim($_POST['name'] ?? '');
$mobile  = trim($_POST['mobile'] ?? '');
$email   = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? 'General Inquiry');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($mobile)) {
    die("Name and Mobile Number are required.");
}

$mail = new PHPMailer(true);

try {

    // ================= SMTP SETTINGS =================
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // Gmail Address
    $mail->Username   = 'hindsolvns@gmail.com';

    // Gmail App Password (16 Characters)
    $mail->Password   = 'juhqyivrvxhfbjqk';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Uncomment for Debug
    // $mail->SMTPDebug = 2;

    // ================= EMAIL SETTINGS =================

    $mail->setFrom(
        'hindsolvns@gmail.com',
        'Bikathnath Food & Organic Website'
    );

    // Jis email par enquiry receive karni hai
    $mail->addAddress('shribikatnathfoodandorganic@gmail.com');

    // Customer email par reply karne ke liye
    if (!empty($email)) {
        $mail->addReplyTo($email, $name);
    }

    $mail->isHTML(true);

    $mail->Subject = "New Contact Inquiry - " . date('d-m-Y H:i:s');

    $mail->Body = '
    <div style="font-family:Arial,sans-serif;padding:20px;">
        <h2 style="color:#0b8f4d;">New Contact Form Submission</h2>

        <table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse:collapse;">
            <tr>
                <td><strong>Full Name</strong></td>
                <td>' . htmlspecialchars($name) . '</td>
            </tr>

            <tr>
                <td><strong>Mobile Number</strong></td>
                <td>' . htmlspecialchars($mobile) . '</td>
            </tr>

            <tr>
                <td><strong>Email Address</strong></td>
                <td>' . htmlspecialchars($email) . '</td>
            </tr>

            <tr>
                <td><strong>Inquiry Type</strong></td>
                <td>' . htmlspecialchars($subject) . '</td>
            </tr>

            <tr>
                <td><strong>Message</strong></td>
                <td>' . nl2br(htmlspecialchars($message)) . '</td>
            </tr>

            <tr>
                <td><strong>Date & Time</strong></td>
                <td>' . date('d-m-Y h:i:s A') . '</td>
            </tr>
        </table>

        <br>

        <p>
            This enquiry was submitted from the
            <strong>Bikathnath Food & Organic Website Contact Form</strong>.
        </p>
    </div>';

    $mail->send();

    echo "
    <script>
        alert('Inquiry Sent Successfully!');
        window.location='../index.php#contact';
    </script>
    ";

} catch (Exception $e) {

    echo '
    <div style="padding:20px;font-family:Arial;">
        <h2 style="color:red;">Mail Sending Failed</h2>
        <p><strong>Error:</strong> ' . $mail->ErrorInfo . '</p>
    </div>';
}