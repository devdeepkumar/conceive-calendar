<?php
if (isset($_POST['submit'])) {

    // Define recipient email (admins will receive the form data)
    $email_to = "gchauhan.dm@gmail.com, himanshu.lifelinkr@gmail.com, devdeepkr734@gmail.com ";
    //   $email_to = "devdeepkr734@gmail.com ";
   
    // $email_to = "himanshu.lifelinkr@gmail.com"; // Admin emails
    $email_subject = "Query from LifeLinkr.co.in Calender Page";



    // Sanitize and assign POST data to variables
    $name = $_POST['name']; // required
    $company_email = $_POST['email']; // required
    $phone = $_POST['phone']; // required
    $clinic_name = $_POST['clinic_name']; // required
    $city = $_POST['city'];
    $using_software = $_POST['is_using_software'];
    $selected_date = $_POST['selected_date']; // required
    $selected_time = $_POST['selected_time']; // required
    $selected_time_user = $_POST['selected_time_user']; // required
    $selected_time_zone = $_POST['time_zone']; // required
    $message = $_POST['message'];
    $guest_emails = $_POST['invities']; // Guest emails entered by the user

    // Combine date and time
    $selected_date_time = $selected_date . ' ' . $selected_time;

    // combine date and time for user
    $selected_date_time_user = $selected_date . ' ' . $selected_time_user;

    // Calculate Google Calendar event start and end time
    $start_date_time = date('Ymd\THis', strtotime($selected_date_time));
    $end_date_time = date('Ymd\THis', strtotime($selected_date_time . ' +1 hour'));

    // Prepare Google Calendar link
    // $calendar_url = "https://www.google.com/calendar/render?action=TEMPLATE&text=Meeting%20Schedule%20&dates=$start_date_time/$end_date_time&details=Details%20from%20your%20form&location=Clinic%20Location";
    $calendar_url = "https://www.google.com/calendar/render?action=TEMPLATE&text=Invitation:%20LifeLinkr%20IVF%20Software%20Demo%20|%20" . urlencode($clinic_name) . "%20|%20" . urlencode($selected_date) . ",%20" . urlencode($selected_time) . "%20(Indian%20Standard%20Time)&dates=$start_date_time/$end_date_time&details=Details%20from%20your%20form&location=Clinic%20Location";


      // Calculate Google Calendar event start and end time for user
      $start_date_time_user = date('Ymd\THis', strtotime($selected_date_time_user));
      $end_date_time_user = date('Ymd\THis', strtotime($selected_date_time_user . ' +1 hour'));

    // Prepare Google Calendar link for user
    // $calendar_url_user = "https://www.google.com/calendar/render?action=TEMPLATE&text=Meeting%20Schedule%20&dates=$start_date_time/$end_date_time&details=Details%20from%20your%20form&location=Clinic%20Location";
    $calendar_url_user = "https://www.google.com/calendar/render?action=TEMPLATE&text=Invitation:%20LifeLinkr%20IVF%20Software%20Demo%20|%20" . urlencode($clinic_name) . "%20|%20" . urlencode($selected_date) . ",%20" . urlencode($selected_time_user) . "%20($selected_time_zone)&dates=$start_date_time_user/$end_date_time_user&details=Details%20from%20your%20form&location=Clinic%20Location";


    // Retrieve the full URL of the referring page
    $full_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'N/A';

    // Function to sanitize input data
    function clean_string($string) {
        $bad = ["content-type", "bcc:", "to:", "cc:", "href"];
        return str_replace($bad, "", $string);
    }

    // Get user IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Prepare email message for the user (and guest emails)
    $email_message_user = "
        <p>Dear " . clean_string($name) . ",</p>
        <p>Thank you for scheduling a demo with LifeLinkr! We’re delighted to connect with you and demonstrate how our software can streamline your clinic’s operations with features like robust data security, one-click automation and effortless data migration.</p>
        <p>Here are the details of your demo:</p>
        <p><strong>Date:</strong> " . clean_string($selected_date) . "</p>
        <p><strong>Time:</strong> " . clean_string($selected_time_user) . "</p>
        <p><strong>Your Email:</strong> " . clean_string($company_email) . "</p>
        <p><strong>Guest Invitees:</strong> " . clean_string($guest_emails) . "</p>
        <p>To make it easier to manage your schedule, you can add this event to your Google Calendar by clicking below:</p>
        <br><a href='$calendar_url_user' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: #4285F4; text-decoration: none; border-radius: 5px; margin-top:-15px; margin-bottom:15px;'>Add to Google Calendar</a>
        <p>Our team will reach out to you shortly to understand your clinic’s specific requirements and ensure the demo is tailored to your needs.</p>
        <p>If you have any questions or require any changes to your demo schedule, feel free to reply to this email or contact us directly.</p>
        <p>We look forward to helping you discover how LifeLinkr can empower your clinic!</p>
        <p>Best regards,<br>Team LifeLinkr</p>
    ";

    // Prepare email message for the admins (same as user but for admin use)
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
        <p><strong>IP Address:</strong> " . clean_string($ip) . "</p>
        <p><strong>Full URL:</strong> " . clean_string($full_url) . "</p>
        <p>To manage this event, you can add it to your Google Calendar:</p>
        <br><a href='$calendar_url' style='display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: #4285F4; text-decoration: none; border-radius: 5px;'>Add to Google Calendar</a>
    ";

    // Email headers
    $headers = "From: leads@lifelinkr.co.in\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email to the user
    
    if (@mail($company_email, "Your LifeLinkr Demo Schedule @ $selected_date_time_user ($selected_time_zone)", $email_message_user, $headers)) {
        // Optionally send email to guest invitees
        if (!empty($guest_emails)) {
            $guest_email_array = explode(',', $guest_emails);
            foreach ($guest_email_array as $guest_email) {
                $guest_email = trim($guest_email);
                // Send the same email to each guest
                mail($guest_email, "Your LifeLinkr Demo Schedule @ $selected_date_time_user ($selected_time_zone)", $email_message_user, $headers);
            }
        }
    } else {
        echo "Failed to send email.";
    }

    // Send email to admins
    @mail($email_to, $email_subject, $email_message_admin, $headers);
}
?>


<html>

<style>
* {
    box-sizing: border-box;
}
body {
    background: #ffffff;
    background: linear-gradient(to bottom, #ffffff 0%, #e1e8ed 100%);
    height: 100%;
    margin: 0;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

.wrapper-1 {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.wrapper-2 {
    padding: 30px;
    text-align: center;
}

h1 {
    font-family: 'Kaushan Script', cursive;
    font-size: 4em;
    letter-spacing: 3px;
    color: #023acd;
    margin: 0;
    margin-bottom: 20px;
}

.wrapper-2 p {
    margin: 0;
    font-size: 20px;
    color: #aaa;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}

.go-home {
    cursor: pointer;
    color: #fff;
    background: #023acd;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    box-shadow: 0 10px 16px 1px rgba(174, 199, 251, 1);
}

.footer-like {
    margin-top: auto;
    background: #D7E6FE;
    padding: 6px;
    text-align: center;
}

.footer-like p {
    margin: 0;
    padding: 4px;
    color: #5892FF;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}

.footer-like p a {
    text-decoration: none;
    color: #5892FF;
    font-weight: 600;
}

.user_details p strong{
font-weight: 400;

}

.Share_btn{
    cursor: pointer;
    color: #fff;
    background: #0eb925;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    box-shadow: 0 10px 16px 1px #99ffa7;
}

@media (min-width: 360px) {
    h1 {
        font-size: 4.5em;
    }

    .go-home {
        margin-bottom: 20px;
    }
}

@media (min-width: 600px) {
    .content {
        max-width: 1000px;
        margin: 0 auto;
    }

    .wrapper-1 {
        height: initial;
        max-width: 620px;
        margin: 0 auto;
        margin-top: 50px;
        box-shadow: 4px 8px 40px 8px rgba(88, 146, 255, 0.2);
    }
}
</style>

<body>
    <div class="content">
        <div class="wrapper-1">
            <div class="wrapper-2">
                <h1>Thank you !</h1>
                <p>We have received your query.</p>
                <p>Our team member will contact you soon.</p>
             
                <button class="go-home"><a href="tel:+91 9667059903" style="color:white; text-decoration:none;">Call Now</a></button>
            </div>
        </div>
    </div>

  
</body>


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KCVQJS23');</script>
<!-- End Google Tag Manager -->


<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KCVQJS23"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<script>
	window.onload = function() {       
    setTimeout(function(){
    window.location.href='https://www.lifelinkr.co.in';
},8000); 
}
</script>

</html>