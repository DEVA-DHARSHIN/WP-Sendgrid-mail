<?php
/*
Plugin Name: eMail Sender
Description: Used to send a mail using SendGrid.
Author: DD
*/

require_once __DIR__ . '/vendor/autoload.php';
use SendGrid\Mail\Mail;


function email_sender_form() {

    if (isset($_POST['send_email'])) {
        
        $to = sanitize_email($_POST['to']);
        $subject = sanitize_text_field($_POST['subject']);
        $message = sanitize_textarea_field($_POST['message']);

        
        $from_email = 'srissv420@gmail.com';  
        $from_name = 'Devadharshini';  

        $sendgrid_api_key = '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';

        if (!class_exists('\SendGrid\Mail\Mail')) {
            echo '<div class="notice notice-error"><p>SendGrid Mail class not found. Please check if the SendGrid library is properly installed.</p></div>';
            return;
        }

        $sendgrid = new \SendGrid($sendgrid_api_key);

        $email = new Mail();
        $email->setFrom($from_email, $from_name); 
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/plain", $message);

        try {
            $response = $sendgrid->send($email);
            if ($response->statusCode() == 202) {
                echo '<div class="notice notice-success"><p>Email sent successfully!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Failed to send the email. Error: ' . $response->body() . '</p></div>';
            }
        } catch (Exception $e) {
            echo '<div class="notice notice-error"><p>Failed to send the email. Exception: ' . $e->getMessage() . '</p></div>';
        }
    }
//     <div class="image">
//     <img src="images/pr.avif" alt="">
//      </div>
    echo '
    <div class="container">
    
    <form method="post" action="" class="overall">
        <p class="heading"> Email Sender </p>
        <p>
            <label for="to">  To:</label>
            <input type="email" name="to" class="sendgrid-input" required />
        </p>
        <p>
            <label for="subject">Subject:</label>
            <input type="text" name="subject" class="sendgrid-input1" required />
        </p>
        <p>
            <label for="message">Message:</label>
            <textarea name="message" class="sendgrid-textarea" required></textarea>
        </p>
        <p>
            <input type="submit" name="send_email" class="sendgrid-button" value="Send Email" />
        </p>
    </form>
    </div>';
}

function sender_menu() {
    add_menu_page(
        'Simple Email Sender',      
        'Email Sender',             
        'manage_options',           
        'simple-email-sender',      
        'email_sender_form'  
    );
}
add_action('admin_menu', 'sender_menu');
function sgMailAddStyles() {
    wp_enqueue_style('sendgrid-styles', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('admin_enqueue_scripts', 'sgMailAddStyles');
?>