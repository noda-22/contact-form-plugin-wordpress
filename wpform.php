<?php

/**
 * Plugin Name: WP Form
 * Description: Contact form with fields for name, email, subject and message.
 * Version: 0.1.0
 * Author: Nora
 */

// När användaren aktiverar plugin:et
function wpform_activation() {
    // var_dump('Hej');
    // exit;
}

register_activation_hook( __FILE__, 'wpform_activation' );

// När användaren avaktiverar plugin:et - tar bort sparad data
function wpform_deactivation() {
    delete_option('formTitle');
    delete_option('nameLabel');
    delete_option('emailLabel');
    delete_option('subjectLabel');
    delete_option('messageLabel');
}

register_deactivation_hook( __FILE__, 'wpform_deactivation' );

// När plugin:et är installerat och användaren besöker WordPress-installationen
function wpform_initialize() {
    // var_dump('Hej');
    // exit;
}

add_action('init', 'wpform_initialize');

// Utseendet för admin-sidan - inkluderar CSS + JS
function wpform_display_admin() {
    // Inkluderar CSS till adminsidan
    wp_register_style('wpform-admin.css', plugin_dir_url(__FILE__) . 'admin/css/wpform-admin.css');
    wp_enqueue_style('wpform-admin.css');
    // Inkluderar JS till adminsidan
    wp_register_script('wpform-admin.js', plugin_dir_url(__FILE__) . 'admin/js/wpform-admin.js', ['jquery']);
    wp_enqueue_script('wpform-admin.js');

    require_once 'includes/display-admin.php'; // Innehåller admin-formuläret där man kan anpassa rubrikerna till public-formuläret
}

// Skapar menyalternativet (huvudmeny)
function wpform_create_admin_menu() {
    add_menu_page(
        'WP Form', // page title
        'WP Form Options', // menu title
        'manage_options', // capability - vilka rättigheter som krävs av användaren
        'wpform-options', // menu slug
        'wpform_display_admin', // function - vilken funktion som anropas när användaren besöker admingränssnittet
        'dashicons-admin-customizer', // icon url - url till den icon som ska användas
        100 // position (längst ner i menyn)
    );

    // UNDERMENY
    // add_submenu_page(
    //     'wpform-options', // parent slug
    //     'WP Form', // page title
    //     'Do something', // menu title - Menyalternativet som användaren kan klicka på
    //     'manage_options', // capability/rättigheter
    //     'do-something', // menu slug
    //     'wpform_display_admin' // vilken funktion som ska köras
    // );
}

add_action('admin_menu','wpform_create_admin_menu');

// Lägger till möjlighet för att spara inställningar
function wpform_register_options() {
    // Lägger till nycklar för det som ska sparas
    register_setting('wpform-options', 'formTitle'); 
    register_setting('wpform-options', 'nameLabel');
    register_setting('wpform-options', 'emailLabel'); 
    register_setting('wpform-options', 'subjectLabel'); 
    register_setting('wpform-options', 'messageLabel'); 
}

add_action('admin_init', 'wpform_register_options');

// SHORTCODE
function wpform_create_shortcode($attributes = [], $content = null) {
    // Inkluderar CSS till shortcoden
    wp_register_style('wpform-public.css', plugin_dir_url(__FILE__) . 'public/css/wpform-public.css');
    wp_enqueue_style('wpform-public.css');
    // Inkluderar JS till shortcoden
    wp_register_script('wpform-public.js', plugin_dir_url(__FILE__) . 'public/js/wpform-public.js', ['jquery']);
    wp_enqueue_script('wpform-public.js');

    // Hämtar sparad data
    $formTitle = get_option('formTitle');
    $nameLabel = get_option('nameLabel');
    $emailLabel = get_option('emailLabel');
    $subjectLabel = get_option('subjectLabel');
    $messageLabel = get_option('messageLabel');

    // Formuläret
    $html = "<div class='wpform-div'>";
    $html .= "<form method='POST'>";

    // Har användaren sparat egen rubrik till formuläret ska detta visas, annars visas ett förbestämt alternativ
    if ($formTitle  !== false && $formTitle !== "") {
        $html .= "<h3 id='formTitle'>$formTitle</h3>";
    } elseif ($formTitle == false || $formTitle == "") {
        $html .= "<h3 id='formTitle'>Contact us</h3>";
    }

    // Feedback till användaren kommer att hamna i dessa div:ar (görs i wpform-public.js)
    $html .= "<div id='error'></div>";
    $html .= "<div id='success'></div>";

    // Har användaren sparat egen rubrik till namn-inputfältet ska detta visas, annars visas ett förbestämt alternativ
    if ($nameLabel !== false && $nameLabel !== "") {
        $html .= "<p id='nameLabel'>$nameLabel <span>*<span></p>";
    } elseif ($nameLabel == false || $nameLabel == "") {
        $html .= "<p id='nameLabel'>Name <span>*<span></p>";
    }
    $html .= "<input id='name' type='text' name='name'>";

     // Har användaren sparat egen rubrik till email-inputfältet ska detta visas, annars visas ett förbestämt alternativ
    if ($emailLabel !== false && $emailLabel !== "") {
        $html .= "<p id='emailLabel'>$emailLabel <span>*<span></p>";
    } elseif ($emailLabel == false || $emailLabel == "") {
        $html .= "<p id='emailLabel'>Email <span>*<span></p>";
    }
    $html .= "<input id='email' type='email' name='email'>";

     // Har användaren sparat egen rubrik till subject-inputfältet ska detta visas, annars visas ett förbestämt alternativ
    if ($subjectLabel !== false && $subjectLabel !== "") {
        $html .= "<p id='subjectLabel'>$subjectLabel <span>*<span></p>";
    } elseif ($subjectLabel == false || $subjectLabel == "") {
        $html .= "<p id='subjectLabel'>Subject <span>*<span></p>";
    }
    $html .= "<input id='subject' type='text' name='subject'>";

     // Har användaren sparat egen rubrik till message-inputfältet ska detta visas, annars visas ett förbestämt alternativ
    if ($messageLabel !== false && $messageLabel !== "") {
        $html .= "<p id='messageLabel'>$messageLabel <span>*<span></p>";
    } elseif ($messageLabel == false || $messageLabel == "") {
        $html .= "<p id='messageLabel'>Message <span>*<span></p>";
    }
    $html .= "<textarea id='message' type='text' name='message'></textarea>";
    
    // $html .= "<button type='submit' id='submitBtn'>Submit</button>";
    $html .= "<input id='submitBtn' type='submit' name='wpform_submit' value='Submit'>";

    $html .= "<form>";
    $html .= "</div>";

    return $html;
}

add_shortcode('wpform-shortcode', 'wpform_create_shortcode');

// Anropas när användaren anropar oss med en AJAX-förfrågan
function wpform_send_mail() {
    // Om inte alla fält är ifyllda - error-meddelande
    if ($_POST["name"] === "" || $_POST["email"] === "" || $_POST["subject"] === "" || $_POST["message"] === "") {
        echo json_encode(['error' => "Please fill in all fields"]);
        exit;
    }

    // Om alla fält är ifyllda - skicka mail
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // $to = get_option('admin_email'); // Hämtar administratörens mailadress
    $to = 'nora.darejati@hotmail.com';
    $headers = [
        "Content-Type: text/plain; charset=UTF-8",
        "From: $name <$email>"
    ];

    if (wp_mail($to, $subject, $message, $headers)) {
        echo json_encode(['success' => 'Thank you! Your message has been sent.']);
    } else {
        echo json_encode(['error' => "Couldn't send email"]);
    }

    exit;
}

// I wpform-public.js skickas nyckeln "action" med värdet "wpform_send_mail", den koppling görs här genom: "wp_ajax_wpform_send_mail"
// Detta innebär att när klienten anropar vårt plugin (servern) så kommer i detta fallet funktionen wpform_send_mail att anropas
add_action('wp_ajax_wpform_send_mail', 'wpform_send_mail');