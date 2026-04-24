<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$to      = 'info@kin.net';
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$org     = trim(strip_tags($_POST['organisation'] ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Basic validation
if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

$subject = "KIN Contact Form – {$name}";

$body  = "Name:         {$name}\n";
$body .= "Email:        {$email}\n";
if ($org)   $body .= "Organisation: {$org}\n";
if ($phone) $body .= "Phone:        {$phone}\n";
$body .= "\nMessage:\n{$message}\n";

$headers  = "From: KIN Contact Form <noreply@kin.net>\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(['ok' => true]);
} else {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Could not send message. Please try again or email us directly.']);
}
