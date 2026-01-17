<?php
<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
    exit;
}

// CHANGE THIS to your real receiving email
$to = 'you@example.com';

$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

if ($name === '' || $email === '' || $message === '') {
    http_response_code(422);
    echo json_encode(['status'=>'error','message'=>'Please fill all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['status'=>'error','message'=>'Invalid email address.']);
    exit;
}

$subject = "Website message from {$name}";
$body = "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}\n";
$headers = "From: {$name} <{$email}>\r\nReply-To: {$email}\r\n";

// Try PHP mail() — may require SMTP setup on server
$sent = @mail($to, $subject, $body, $headers);

if ($sent) {
    echo json_encode(['status'=>'success','message'=>'Message sent successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Failed to send. Server may require SMTP configuration.']);
}
?>