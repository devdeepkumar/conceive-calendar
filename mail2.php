<?php
if (isset($_POST['submit'])) {

    // Define recipient email (admins will receive the form data)
    $email_to = "gchauhan.dm@gmail.com, himanshu.lifelinkr@gmail.com, devdeepkr734@gmail.com";
    $email_subject = "Query from LifeLinkr.co.in Calender Page";

    // Sanitize and assign POST data to variables
    $name = $_POST['name'];
    $company_email = $_POST['email'];
    $phone = $_POST['phone'];
    $clinic_name = $_POST['clinic_name'];
    $city = $_POST['city'];
    $using_software = $_POST['is_using_software'];
    $selected_date = $_POST['selected_date'];
    $selected_time = $_POST['selected_time'];
    $selected_time_user = $_POST['selected_time_user'];
    $selected_time_zone = $_POST['time_zone'];
    $message = $_POST['message'];
    $guest_emails = $_POST['invities'];

    // Combine date and time
    $selected_date_time = $selected_date . ' ' . $selected_time;
    $selected_date_time_user = $selected_date . ' ' . $selected_time_user;

    // Calculate Google Calendar event start and end time
    $start_date_time = date('Ymd\THis', strtotime($selected_date_time));
    $end_date_time = date('Ymd\THis', strtotime($selected_date_time . ' +1 hour'));

    // Google Calendar link
    $calendar_url_user = "https://www.google.com/calendar/render?action=TEMPLATE&text=Invitation:%20LifeLinkr%20IVF%20Software%20Demo%20|%20" . urlencode($clinic_name) . "%20|%20" . urlencode($selected_date) . ",%20" . urlencode($selected_time_user) . "%20($selected_time_zone)&dates=$start_date_time/$end_date_time&details=Details%20from%20your%20form&location=Clinic%20Location";

    // Function to sanitize input data
    function clean_string($string) {
        $bad = ["content-type", "bcc:", "to:", "cc:", "href"];
        return str_replace($bad, "", $string);
    }

    // Prepare email message for the user
    $email_message_user = "
        <p>Dear " . clean_string($name) . ",</p>
        <p>Thank you for scheduling a demo with LifeLinkr!</p>
        <p><strong>Date:</strong> " . clean_string($selected_date) . "</p>
        <p><strong>Time:</strong> " . clean_string($selected_time_user) . "</p>
        <p><strong>Your Email:</strong> " . clean_string($company_email) . "</p>
        <p><strong>Guest Invitees:</strong> " . clean_string($guest_emails) . "</p>
        <p><a href='$calendar_url_user' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: #4285F4; text-decoration: none; border-radius: 5px;'>Add to Google Calendar</a></p>
        <p>Best regards,<br>Team LifeLinkr</p>
    ";

    // Prepare email for admins
    $email_message_admin = "
        <p><strong>Name:</strong> " . clean_string($name) . "</p>
        <p><strong>Email:</strong> " . clean_string($company_email) . "</p>
        <p><strong>Phone:</strong> " . clean_string($phone) . "</p>
        <p><strong>Clinic Name:</strong> " . clean_string($clinic_name) . "</p>
        <p><strong>City:</strong> " . clean_string($city) . "</p>
        <p><strong>Using Software:</strong> " . clean_string($using_software) . "</p>
        <p><strong>Message:</strong> " . clean_string($message) . "</p>
        <p><strong>Selected Date & Time:</strong> " . clean_string($selected_date_time) . "</p>
        <p><strong>Guest Emails:</strong> " . clean_string($guest_emails) . "</p>
    ";

    // Email headers
    $headers = "From: leads@lifelinkr.co.in\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Send email to the user
    @mail($company_email, "Your LifeLinkr Demo Schedule @ $selected_date_time_user ($selected_time_zone)", $email_message_user, $headers);

    // Send email to admin
    @mail($email_to, $email_subject, $email_message_admin, $headers);

    // CRM API URL (Replace with your CRM API endpoint)
    $crm_api_url = "https://your-crm.com/api/lead"; 

    // Prepare the data to send to the CRM
    $crm_data = [
        "name" => $name,
        "email" => $company_email,
        "phone" => $phone,
        "clinic_name" => $clinic_name,
        "city" => $city,
        "software_used" => $using_software,
        "selected_date_time" => $selected_date_time,
        "message" => $message,
        "guest_emails" => $guest_emails
    ];

    // Send data to CRM via cURL
    $ch = curl_init($crm_api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($crm_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $crm_response = curl_exec($ch);
    curl_close($ch);

    // Log CRM response for debugging (remove in production)
    file_put_contents("crm_log.txt", date('Y-m-d H:i:s') . " - CRM Response: " . $crm_response . "\n", FILE_APPEND);
}
?>

<html>
<head>
    <style>
        * { box-sizing: border-box; }
        body { background: #ffffff; height: 100%; margin: 0; }
        .wrapper-1 { width: 100%; height: 100vh; display: flex; flex-direction: column; }
        .wrapper-2 { padding: 30px; text-align: center; }
        h1 { font-size: 4em; color: #023acd; margin-bottom: 20px; }
        .go-home { color: #fff; background: #023acd; padding: 10px 50px; border-radius: 30px; }
        .footer-like { background: #D7E6FE; padding: 6px; text-align: center; }
        @media (min-width: 600px) { .wrapper-1 { max-width: 620px; margin: 50px auto; box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2); } }
    </style>
</head>
<body>
    <div class="wrapper-1">
        <div class="wrapper-2">
            <h1>Thank you!</h1>
            <p>We have received your query. Our team will contact you soon.</p>
            <button class="go-home"><a href="tel:+91 9667059903" style="color:white; text-decoration:none;">Call Now</a></button>
        </div>
    </div>
    <script>
        window.onload = function() { setTimeout(function(){ window.location.href='https://www.lifelinkr.co.in'; },8000); };
    </script>
</body>
</html>
