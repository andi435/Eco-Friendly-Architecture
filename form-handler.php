<?php
// Configuration
$recipient_email = "info@verdistudio.ca";
$email_subject = "New Form Submission from Verdi Studio Website";
$redirect_url = "thank-you.html"; // Redirect after successful submission

// Security configurations
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate input data
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    
    // Optional fields - check if they exist in the form
    $subject = isset($_POST['subject']) ? filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING) : "No subject provided";
    $service = isset($_POST['service']) ? filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING) : "No service specified";
    $project_details = isset($_POST['project_details']) ? filter_input(INPUT_POST, 'project_details', FILTER_SANITIZE_STRING) : $message;
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format. Please go back and enter a valid email address.");
    }
    
    // Check for empty required fields
    if (empty($name) || empty($email) || empty($message)) {
        die("Please fill in all required fields (Name, Email, and Message).");
    }
    
    // Prepare email content
    $email_content = "New website form submission:\n\n";
    $email_content .= "Name: $name\n";
    $email_content .= "Email: $email\n";
    
    // Add optional fields if they exist
    if (isset($_POST['subject'])) {
        $email_content .= "Subject: $subject\n";
    }
    
    if (isset($_POST['service'])) {
        $email_content .= "Service Required: $service\n";
    }
    
    // Message/Project Details
    $email_content .= "Message/Project Details:\n$message\n";
    
    // Prepare email headers
    $headers = "From: Verdi Studio Website <noreply@verdistudio.ca>\r\n";
    $headers .= "Reply-To: $name <$email>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($recipient_email, $email_subject, $email_content, $headers)) {
        // If successful, redirect to thank you page
        header("Location: $redirect_url");
        exit;
    } else {
        // If mail function fails
        die("Error: Unable to send your message. Please try again later or contact us directly at $recipient_email");
    }
} else {
    // If not a POST request
    die("Error: Invalid form submission method.");
}
?>
