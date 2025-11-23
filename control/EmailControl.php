<?php
// Incluye el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';
// Incluye tus configuraciones (donde definiste las constantes)
require_once __DIR__ . '/../configuracion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarFactura($destinatarioEmail, $nombreCliente, $rutaFacturaPDF)
{

    $mail = new PHPMailer(true);

    try {
        // Configuración del Servidor SMTP (Usando tus constantes)
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_SECURE;
        $mail->Port       = MAIL_PORT;

        // Remitente (Quién envía)
        $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);

        // Destinatario (A quién va dirigido)
        $mail->addAddress($destinatarioEmail, $nombreCliente);

        // Adjuntar la factura PDF
        // Asegúrate de que $rutaFacturaPDF contenga la ruta completa al archivo en el servidor
        $mail->addAttachment($rutaFacturaPDF, 'Factura_Compra.pdf');

        // Contenido del Email
        $mail->isHTML(true);
        $mail->Subject = 'Confirmación de Compra y Factura - Tienda PWD';
        $mail->Body    = "Hola **$nombreCliente**,<br><br>"
            . "Gracias por tu compra. Adjunto encontrarás la factura en formato PDF.<br><br>"
            . "Saludos,<br>El equipo de la Tienda PWD.";
        $mail->AltBody = "Hola $nombreCliente, Gracias por tu compra. Adjunto encontrarás la factura.";

        $mail->send();
        return true; // Éxito al enviar
    } catch (Exception $e) {
        // En caso de error, puedes registrarlo o mostrar un mensaje.
        echo "El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}";
        return false; // Error al enviar
    }
}
