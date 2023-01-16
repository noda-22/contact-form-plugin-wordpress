<!-- Har en egen php-fil (finns redan) som tar emot allting -->
<form method="POST" action="options.php"> 
    <?php
    // Gör så att vi kan spara datan i wordpress inställningar
    settings_fields('wpform-options');
    ?>

    <h1 class="wpform-header">WP Form Options</h1>
    <h3>Form title</h3>
    <input type="text" name="formTitle" value="<?php echo get_option('formTitle'); ?>">
    <h3>Name field label</h3>
    <input type="text" name="nameLabel" value="<?php echo get_option('nameLabel'); ?>"> <!-- get_option("nameLabel") hämtar sparad data - gör så att det syns tex även efter att sidan uppdateras -->
    <h3>Email field label</h3>
    <input type="text" name="emailLabel" value="<?php echo get_option('emailLabel'); ?>"> 
    <h3>Subject field label</h3>
    <input type="text" name="subjectLabel" value="<?php echo get_option('subjectLabel'); ?>">
    <h3>Message field label</h3>
    <input type="text" name="messageLabel" value="<?php echo get_option('messageLabel'); ?>"> 

    <!-- Knapp  med den inbyggda stilmallen -->
    <?php submit_button(); ?>
    <!-- <button type='submit'>Save</button> -->
</form>