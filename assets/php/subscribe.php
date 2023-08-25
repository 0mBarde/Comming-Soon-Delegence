<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate the email address
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "Invalid Email Address.";
        exit();
    }

    // Honeypot check
    if (!empty($_POST['phone'])) {
        echo "Honeypot validation failed.";
        exit();
    }

    $api_key = "mlsn.d50ae60c48d1ed5861677f68757fc74c1696850bdc467c1d890909b20dee2b7d"; // Replace with your MailerSend API key
    
    $recipient_email = $_POST['email']; // Replace with the recipient's email address
    
    $request_data = [
        "to" => [
            ["email" => $recipient_email]
        ],
        "subject" => "New Subscription",
        "text" => "New subscriber email: {$_POST['email']}"
    ];
    
    $ch = curl_init('https://api.mailersend.com/v1/email');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $response_data = json_decode($response, true);
    
    if (isset($response_data['id'])) {
        echo "Successfully Subscribed!";
    } else {
        echo "Error subscribing.";
    }
}
?>
