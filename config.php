<?php
// United Services Academy - Configuration File
// Updated to match With Your Shield mail system

// Email Configuration using array structure like With Your Shield
$config = [
    // Email settings
    //'to_email' => 'doshevlin@us.academy',  // Primary contact email
    'to_email' => 'amerlocfr@gmail.com',  // Primary contact email
    'to_email_secondary' => 'support@us.academy',  // Secondary contact email
    'from_email' => 'noreply@us.academy',  // Change this to your domain email
    
    // Using PHP's built-in mail() function
    
    // Form settings
    'max_message_length' => 2000,
    'allowed_subjects' => [
        'General Inquiry',
        'Program Information', 
        'Veteran Benefits',
        'Enrollment',
        'Financial Aid',
        'Other'
    ],
    
    // Security settings
    'rate_limit' => 5,  // Max submissions per hour per IP
    'honeypot_field' => 'website',  // Hidden field name for spam protection
    
    // Notification settings
    'send_notifications' => true,
    'notification_email' => 'admin@us.academy',  // Admin notification email
];

// Email Subject Configuration
define('EMAIL_SUBJECT_PREFIX', 'USA Contact Form - '); // Prefix for email subjects
define('AUTO_REPLY_SUBJECT', 'Thank you for contacting United Services Academy');

// Website Configuration
define('SITE_NAME', 'United Services Academy');
define('SITE_URL', 'https://us.academy');
define('SITE_DESCRIPTION', 'Hands-on skilled trades training in Puerto Rico. Six-week program focusing on HVAC/R, electrical, and plumbing with veteran-friendly support.');

// Contact Information
define('CONTACT_PHONE', '(555) 123-4567'); // Update with your actual phone
define('CONTACT_ADDRESS', 'Puerto Rico Campus');
define('CONTACT_CITY', 'Puerto Rico');
define('CONTACT_COUNTRY', 'US');

// Program Information
define('PROGRAM_DURATION', '6 weeks');
define('STUDENT_TEACHER_RATIO', '25:1');
define('PLACEMENT_GOAL', '100%');
define('CAMPUS_LOCATION', 'Puerto Rico');
define('AIRPORT_CODE', 'SJU');

// Email Templates Configuration
define('ENABLE_AUTO_REPLY', true); // Set to false to disable auto-reply emails
define('AUTO_REPLY_DELAY', 0); // Delay in seconds before sending auto-reply (0 = immediate)

// Security Configuration
define('ENABLE_CSRF_PROTECTION', true); // Enable CSRF protection (requires session handling)
define('MAX_MESSAGE_LENGTH', 2000); // Maximum message length
define('RATE_LIMIT_ENABLED', true); // Enable rate limiting
define('RATE_LIMIT_ATTEMPTS', 5); // Max attempts per hour
define('RATE_LIMIT_WINDOW', 3600); // Time window in seconds (1 hour)

// reCAPTCHA Configuration
define('RECAPTCHA_SECRET_KEY', getenv('RECAPTCHA_SECRET_KEY') ?: '6Lfom9ErAAAAADF5fECmD0AEXNWgN0GaivgkR8va'); // Your actual secret key
define('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify');
define('RECAPTCHA_SCORE_THRESHOLD', 0.5); // Minimum score for spam detection

// Debug Configuration
define('DEBUG_MODE', true); // Set to true for debugging (shows errors)
define('LOG_EMAILS', true); // Log email attempts to file
define('LOG_FILE', 'email_log.txt'); // Log file name

// SMTP Configuration (Optional - for better email delivery)
define('USE_SMTP', false); // GoDaddy blocks SMTP, using PHP mail() instead
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'doshevlin@us.academy');
// SMTP_PASSWORD should be set via environment variable for security
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: 'gbth gfok cpkj kizq'); // Your actual password
define('SMTP_ENCRYPTION', 'ssl'); // Gmail requires SSL on port 465

// Social Media Links (for email signatures)
define('FACEBOOK_URL', 'https://facebook.com/unitedservicesacademy');
define('TWITTER_URL', 'https://twitter.com/us.academy');
define('LINKEDIN_URL', 'https://linkedin.com/company/united-services-academy');
define('INSTAGRAM_URL', 'https://instagram.com/unitedservicesacademy');

// Program Features (for auto-reply email)
define('PROGRAM_FEATURES', [
    '6-week intensive skilled trades training',
    'HVAC/R, electrical, and plumbing fundamentals',
    '25:1 student-teacher ratio',
    '100% placement goal',
    'FREE for qualified veterans',
    'Located in Puerto Rico with airport access via SJU'
]);

// Veteran Benefits Information
define('VETERAN_BENEFITS', [
    'GI Bill acceptance',
    'Full tuition coverage for qualified veterans',
    'Housing support',
    'Comprehensive career placement assistance',
    'Specialized transition assistance'
]);

// Error Messages
define('ERROR_MESSAGES', [
    'required_field' => 'This field is required.',
    'invalid_email' => 'Please enter a valid email address.',
    'message_too_long' => 'Message is too long. Please keep it under ' . MAX_MESSAGE_LENGTH . ' characters.',
    'rate_limit_exceeded' => 'Too many requests. Please try again later.',
    'email_send_failed' => 'Sorry, there was an error sending your message. Please try again later.',
    'invalid_csrf' => 'Security token invalid. Please refresh the page and try again.'
]);

// Success Messages
define('SUCCESS_MESSAGES', [
    'form_submitted' => 'Thank you! Your message has been sent successfully. We\'ll respond within 24 hours.',
    'auto_reply_sent' => 'A confirmation email has been sent to your email address.'
]);

// Timezone Configuration
date_default_timezone_set('America/Puerto_Rico'); // Set to your local timezone

// Error Reporting (only in debug mode)
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Helper function to get configuration value
function get_config($key, $default = null) {
    return defined($key) ? constant($key) : $default;
}

// Helper function to log messages
function log_message($message, $type = 'INFO') {
    if (LOG_EMAILS) {
        $timestamp = date('Y-m-d H:i:s');
        $log_entry = "[$timestamp] [$type] $message" . PHP_EOL;
        file_put_contents(LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Rate limiting function (from With Your Shield)
function checkRateLimit($ip) {
    global $config;
    $rate_limit_file = 'rate_limit.json';
    
    if (!file_exists($rate_limit_file)) {
        file_put_contents($rate_limit_file, '{}');
    }
    
    $rate_data = json_decode(file_get_contents($rate_limit_file), true);
    $current_time = time();
    $hour_ago = $current_time - 3600;
    
    // Clean old entries
    foreach ($rate_data as $ip_addr => $times) {
        $rate_data[$ip_addr] = array_filter($times, function($time) use ($hour_ago) {
            return $time > $hour_ago;
        });
        if (empty($rate_data[$ip_addr])) {
            unset($rate_data[$ip_addr]);
        }
    }
    
    // Check current IP
    if (!isset($rate_data[$ip])) {
        $rate_data[$ip] = [];
    }
    
    if (count($rate_data[$ip]) >= $config['rate_limit']) {
        return false;
    }
    
    // Add current submission
    $rate_data[$ip][] = $current_time;
    file_put_contents($rate_limit_file, json_encode($rate_data));
    
    return true;
}

// Honeypot validation (from With Your Shield)
function validateHoneypot() {
    global $config;
    $honeypot_field = $config['honeypot_field'];
    
    if (isset($_POST[$honeypot_field]) && !empty($_POST[$honeypot_field])) {
        return false; // Bot detected
    }
    
    return true;
}
?>
