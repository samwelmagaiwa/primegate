<?php
// Simple secure form handler for Primegate website quote/contact forms
// - Accepts POST from homepage quote forms (index.html, index-2.html)
// - Sends an email to info@primegateinternational.co.tz
// - Shows a friendly success / error message page

// CONFIGURATION ------------------------------------------------------------
$recipientEmail = 'info@primegateinternational.co.tz';
$fromEmail      = 'info@primegateinternational.co.tz'; // real mailbox on your domain (better for inbox)
$fromName       = 'Primegate Website';

// basic rate limiting (per IP, seconds between submissions)
$rateLimitSeconds = 60;
$rateFile         = __DIR__ . '/quotes_rate_limit.json';
$logFile          = __DIR__ . '/quotes_log.txt';

// -------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Direct access: just send user back to the homepage
    header('Location: index.html');
    exit;
}

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Helper to sanitize simple text fields
function pg_clean_text($value) {
    $value = trim((string)$value);
    // Prevent header injection
    $value = str_replace(array("\r", "\n"), ' ', $value);
    return strip_tags($value);
}

// Collect fields from both homepage forms (names must match the HTML)
$fullName       = pg_clean_text($_POST['full_name']       ?? '');
$email          = pg_clean_text($_POST['email']           ?? '');
$phone          = pg_clean_text($_POST['phone']           ?? '');
$cargoType      = pg_clean_text($_POST['cargo_type']      ?? '');
$originCountry  = pg_clean_text($_POST['origin_country']  ?? '');
$destination    = pg_clean_text($_POST['destination']     ?? '');
$quantity       = pg_clean_text($_POST['quantity']        ?? '');
$weight         = pg_clean_text($_POST['weight']          ?? '');
$width          = pg_clean_text($_POST['width']           ?? '');
$height         = pg_clean_text($_POST['height']          ?? '');

// Honeypot field for spam bots (must stay empty)
$honeypot       = trim($_POST['website'] ?? '');

// Optional context (e.g. which page/form); can be added as a hidden input later
$formContext    = pg_clean_text($_POST['form_context']    ?? 'Homepage Control Tower Quote');

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

$errors      = [];
$isSpam      = false;
$isRateLimit = false;

// Honeypot check
if ($honeypot !== '') {
    $isSpam   = true;
    $errors[] = 'Spam protection triggered.';
}

if ($fullName === '') {
    $errors[] = 'Full name is required.';
}
if ($phone === '') {
    $errors[] = 'Phone number is required.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'A valid email address is required.';
}

// Simple rate limiting per IP
if (!$isSpam && $rateLimitSeconds > 0 && $ipAddress !== 'unknown') {
    $data = [];
    if (is_file($rateFile)) {
        $json = file_get_contents($rateFile);
        $tmp  = json_decode($json, true);
        if (is_array($tmp)) {
            $data = $tmp;
        }
    }
    $now = time();
    if (isset($data[$ipAddress]) && ($now - (int)$data[$ipAddress]) < $rateLimitSeconds) {
        $isRateLimit = true;
        $errors[]    = 'You are sending requests too quickly. Please wait a moment and try again.';
    } else {
        $data[$ipAddress] = $now;
        @file_put_contents($rateFile, json_encode($data));
    }
}

// Build email if no validation / spam / rate-limit errors
$sent = false;
if (empty($errors) && !$isSpam && !$isRateLimit) {
    $subject = 'New Control Tower Quote Request - ' . $fullName;

    // Build an HTML email with a clean table
    $rows = [
        'Form'        => $formContext,
        'Full name'   => $fullName,
        'Email'       => $email,
        'Phone'       => $phone,
        'Cargo type'  => $cargoType,
        'Origin'      => $originCountry,
        'Destination' => $destination,
        'Quantity'    => $quantity,
        'Weight'      => $weight,
        'Width'       => $width,
        'Height'      => $height,
        'Time'        => date('Y-m-d H:i:s'),
        'Source IP'   => $ipAddress,
    ];

    $tableRows = '';
    foreach ($rows as $label => $value) {
        $tableRows .= '<tr>'
            . '<th style="padding:8px 12px;border:1px solid #dde3f0;background:#f4f6fb;text-align:left;font-weight:600;">'
            . htmlspecialchars($label)
            . '</th><td style="padding:8px 12px;border:1px solid #dde3f0;">'
            . nl2br(htmlspecialchars((string)$value))
            . '</td></tr>';
    }

    $body = '<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;background:#f5f7fb;padding:20px;"'
        . '<div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 18px 45px rgba(4,16,52,0.18);">'
        . '<div style="background:#041d7d;color:#ffffff;padding:16px 20px;font-size:18px;font-weight:bold;">New Control Tower Quote Request</div>'
        . '<div style="padding:18px 20px;font-size:14px;color:#24304a;">'
        . '<p>You have received a new mission brief request from the Primegate website.</p>'
        . '<table style="border-collapse:collapse;width:100%;margin-top:10px;font-size:13px;">'
        . $tableRows
        . '</table>'
        . '</div>'
        . '</div>'
        . '</body></html>';

    // Build headers: use a domain-based From and put the visitor email in Reply-To
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    $headers   = '';
    $headers  .= 'From: ' . $fromName . ' <' . $fromEmail . ">\r\n";
    if ($email !== '') {
        $headers .= 'Reply-To: ' . $fullName . ' <' . $email . ">\r\n";
    }
    $headers  .= "MIME-Version: 1.0\r\n";
    $headers  .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Send the email using PHP's mail(). For best inbox placement, configure
    // SPF/DKIM on the server for the domain used in $fromEmail.
    $sent = @mail($recipientEmail, $encodedSubject, $body, $headers);

    // LOCALHOST TESTING ONLY: Remove this block before deploying to production!
    // On localhost/XAMPP, mail() often fails because no SMTP is configured.
    // This override allows you to test the form flow locally.
    if (!$sent && (isset($_SERVER['SERVER_NAME']) && ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1'))) {
        $sent = true; // REMOVE THIS LINE IN PRODUCTION
    }
}

// Determine public status + message
if (!empty($errors) || !$sent) {
    $status = 'error';
    if ($isSpam) {
        $publicMessage = 'We could not verify this request. Please try again or contact us directly.';
    } elseif ($isRateLimit) {
        $publicMessage = 'You are sending requests too quickly. Please wait a bit and try again.';
    } elseif (!empty($errors)) {
        $publicMessage = 'Please correct the highlighted fields and try again.';
    } else {
        $publicMessage = 'There was an unexpected error while sending your request. Please try again later.';
    }
} else {
    $status        = 'success';
    $publicMessage = 'Our mission control team has received your brief and will respond shortly.';
}

// Logging
$logLine = sprintf(
    "[%s] status=%s ip=%s name=%s email=%s phone=%s context=%s%s\n",
    date('Y-m-d H:i:s'),
    $status,
    $ipAddress,
    $fullName,
    $email,
    $phone,
    $formContext,
    $isSpam ? ' SPAM' : ($isRateLimit ? ' RATE_LIMIT' : '')
);
@file_put_contents($logFile, $logLine, FILE_APPEND);

// AJAX JSON response -------------------------------------------------------
if ($isAjax) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'status'  => $status,
        'message' => $publicMessage,
        'errors'  => $errors,
    ]);
    exit;
}

// Simple HTML response (non-JS fallback) ----------------------------------
$pageTitle = ($status === 'success') ? 'Message Sent' : 'Something Went Wrong';

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pageTitle); ?> - Primegate International</title>
  <link rel="stylesheet" href="css/style-core.css">
  <link rel="stylesheet" href="css/logisco-style-custom.css">
  <link rel="stylesheet" href="css/modern-primegate.css">
  <style>
    body { display:flex;align-items:center;justify-content:center;min-height:100vh;background:#041d7d;font-family: "Poppins", sans-serif; }
    .pg-feedback-card {
      max-width:520px;width:90%;background:#ffffff;border-radius:18px;padding:28px 26px;
      box-shadow:0 24px 70px rgba(0,0,0,0.45);text-align:center;animation:pgFadeIn .4s ease-out;
    }
    .pg-feedback-card h1 { margin:0 0 10px;font-size:1.6rem;color:#041d7d; }
    .pg-feedback-card p { margin:0 0 16px;color:#4d5674;font-size:0.98rem;line-height:1.6; }
    .pg-feedback-card ul { text-align:left;margin:0 0 12px 1.2rem;color:#b00020;font-size:0.9rem; }
    .pg-feedback-card a.pg-btn {
      display:inline-block;margin-top:10px;padding:10px 22px;border-radius:999px;
      background:linear-gradient(120deg,#e52021,#ff7a18);color:#fff;font-weight:700;
      letter-spacing:0.12em;text-transform:uppercase;text-decoration:none;font-size:0.8rem;
      box-shadow:0 16px 32px rgba(229,32,33,0.35);
    }
    @keyframes pgFadeIn { from { opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
  </style>
</head>
<body>
  <div class="pg-feedback-card">
    <?php if ($status === 'success'): ?>
      <h1>Thank you, your request has been sent.</h1>
      <p><?php echo htmlspecialchars($publicMessage); ?></p>
    <?php else: ?>
      <h1>We could not send your request.</h1>
      <p><?php echo htmlspecialchars($publicMessage); ?></p>
      <?php if (!empty($errors)): ?>
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php endif; ?>

    <a class="pg-btn" href="javascript:history.back();">Back to previous page</a>
  </div>
</body>
</html>
