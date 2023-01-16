document.getElementById('submitBtn').addEventListener('click', function(event) {

    event.preventDefault();

    // Hämtar innehållet från inputfälten
    let inputname = jQuery("#name").val();
    let inputemail = jQuery("#email").val();
    let inputsubject = jQuery("#subject").val();
    let inputmessage = jQuery("#message").val();

    // Skickar post-request med innehållet från inputfälten - används i funktionen wpform_send_mail (wpform.php) för att skicka mail med innehållet
    let request = new Request('/wp-admin/admin-ajax.php', { // fördefinierad php-fil som hanterar förfrågningarna
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        },
        body: String(new URLSearchParams({
            action: 'wpform_send_mail',
            name: inputname,
            email: inputemail,
            subject: inputsubject,
            message: inputmessage
        }))
    });

    fetch(request)
        .then(res => {
            return res.json();
        })
        .then(json => {
            console.log(json);
            // Kollar om nyckeln error/success finns och visar antingen ett error-meddelande eller en bekräftelse på att mailet skickats
            // Nycklarna + deras värden kommer från funktionen wpform_send_mail i wpform.php
            if (json.error) {
                jQuery("#success").html('');
                document.getElementById('error').innerHTML = `${json.error}`;
            } else if (json.success) {
                jQuery("#error").html('');
                document.getElementById('success').innerHTML = `${json.success}`;

                jQuery("#name").val('');
                jQuery("#email").val('');
                jQuery("#subject").val('');
                jQuery("#message").val('');
            }

        });
});