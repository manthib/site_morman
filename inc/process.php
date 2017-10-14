<?php
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['choice']) && isset($_POST['comment'])) {
    //Retrieve form data. 
    //POST - user submitted data using AJAX
    //POST - in case user does not support javascript, we'll use POST instead
    $name = ($_POST['name']) ? $_POST['name'] : $_POST['name'];
    $email = ($_POST['email']) ?$_POST['email'] : $_POST['email'];
    $choice = ($_POST['choice']) ?$_POST['choice'] : $_POST['choice'];
    $comment = ($_POST['comment']) ?$_POST['comment'] : $_POST['comment'];

    //flag to indicate which method it uses. If POST set it to 1
    if ($_POST) $post=1;

    //Simple server side validation for POST data, of course, you should validate the email
    if (!$name) $errors[count($errors)] = 'Veuillez entrer votre nom';
    if (!$email) $errors[count($errors)] = 'Veuillez entrer votre email.';
    if (!$choice) $errors[count($errors)] = 'Veuillez entrer votre objectif.'; 
    if (!$comment) $errors[count($errors)] = 'Veuillez entrer votre commentaire'; 

    //if the errors array is empty, send the mail
    $errors = Array();
    if (!$errors) {

        //recipient - YOUR EMAIL.. or whatever
        $to = 'Morman Design <morman.design@gmail.com>';
        //sender - from the form
        $from = $name . ' <' . $email . '>';

        //subject and the html message
        $subject = 'Message de ' . $name;
        $message = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head></head>
        <body>
        <table>
            <tr><td>Nom</td><td>' . $name . '</td></tr>
            <tr><td>Email</td><td>' . $email . '</td></tr>
            <tr><td>Objectif</td><td>' . $choice . '</td></tr>
            <tr><td>Commentaire</td><td>' . nl2br($comment) . '</td></tr>
        </table>
        </body>
        </html>';

        //send the mail
        $result = sendmail($to, $subject, $message, $from);

        //if POST was used, display the message straight away
        if ($_POST) {
            if ($result) echo 'Merci! Nous avons reçu votre email !<br/> <a href="https://morman.fr">Retour</a>';
            else echo 'Désolé, nous avons rencontré une erreur. Veuillez réessayer plus tard';

        //else if POST was used, return the boolean value so that 
        //ajax script can react accordingly
        //1 means success, 0 means failed
        } else {
            echo $result;	
        }

    //if the errors array has values
    } else {
        //display the errors message
        for ($i=0; $i<count($errors); $i++) echo $errors[$i] . '<br/>';
        echo '<a href="https://morman.fr">Retour</a>';
        exit;
    }

} else {
    $reponse = 'Tous les champs ne sont pas parvenus';
}
//Simple mail function with HTML header
function sendmail($to, $subject, $message, $from) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";

        $result = mail($to,$subject,$message,$headers);

        if ($result) return 1;
        else return 0;
    }
?>