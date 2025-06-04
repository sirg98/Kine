<?php 
$mensaje_enviado = false;
$error_envio = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify Turnstile token
    $token = $_POST['cf-turnstile-response'] ?? '';
    $secret = $_ENV['CLOUDFLARE_TURNSTILE_SECRET_KEY']; // Replace with your Turnstile secret key
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://challenges.cloudflare.com/turnstile/v0/siteverify");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if (!$result['success']) {
        $error_envio = 'Por favor, completa la verificación de seguridad.';
    } else {
        $nombre   = htmlspecialchars($_POST['nombre'] ?? '');
        $correo   = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
        $telefono = htmlspecialchars($_POST['telefono'] ?? '');
        $asunto   = htmlspecialchars($_POST['asunto'] ?? '');
        $mensaje  = htmlspecialchars($_POST['mensaje'] ?? '');

        if ($correo && $nombre && $asunto && $mensaje) {
            $body = "
                <h2>📩 Nuevo mensaje desde el formulario de contacto</h2>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Correo:</strong> $correo</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Asunto:</strong> $asunto</p>
                <p><strong>Mensaje:</strong><br>$mensaje</p>
            ";

            $respuesta = "
                <h2>📩 Hemos recibido tu mensaje</h2>
                <p>Hola <strong>$nombre</strong>,</p>
                <p>Gracias por contactar con <strong>ReflexioKineTP</strong>.</p>
                <p>Tu mensaje ha sido recibido correctamente y uno de nuestros profesionales lo revisará en breve. Nos pondremos en contacto contigo lo antes posible.</p>
                <p>Si tu consulta es urgente, también puedes llamarnos o escribirnos por WhatsApp:</p>
                <ul>
                    <li>📞 <strong>Teléfono:</strong> +34 600 132 456</li>
                    <li>💬 <strong>WhatsApp:</strong> <a href='https://wa.me/34600132456' target='_blank'>Haz clic aquí para chatear</a></li>
                </ul>
                <p>Gracias por confiar en nosotros.</p>
                <p>Un saludo,<br>El equipo de ReflexioKineTP</p>
            ";

            try {
                enviarEmail('info@reflexiokinetp.es', "Contacto: $asunto", $body);
                enviarEmail($correo, "Hemos recibido tu mensaje", $respuesta);
                $mensaje_enviado = true;
            } catch (Exception $e) {
                $error_envio = $e->getMessage();
            }
        } else {
            $error_envio = 'Todos los campos obligatorios deben estar completos y el correo debe ser válido.';
        }
    }
}
?>

<main>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-center mb-4 text-kinetic-900">Pide tu cita o contacta con nosotros</h1>
            <p class="text-center text-base text-kinetic-700 mb-8 max-w-2xl mx-auto">Estamos aquí para responder a tus preguntas y ayudarte a comenzar tu viaje hacia la salud óptima.</p>

            <?php if ($mensaje_enviado): ?>
                <div class="bg-green-100 text-green-800 p-4 rounded mb-6 text-center">✅ Tu mensaje ha sido enviado correctamente.</div>
            <?php elseif ($error_envio): ?>
                <div class="bg-red-100 text-red-800 p-4 rounded mb-6 text-center">❌ Error al enviar el mensaje: <?= $error_envio ?></div>
            <?php endif; ?>
        </div>
    </section>

    <section class="container mx-auto px-4 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- FORMULARIO -->
            <form method="POST" class="bg-card dark:bg-gray-900 rounded-xl shadow-sm p-8 flex flex-col space-y-4">
                <h2 class="font-semibold text-xl mb-2 text-main">Envíanos un mensaje</h2>
                <div>
                    <label class="block text-sm font-medium mb-1 text-secondary">Nombre completo</label>
                    <input type="text" name="nombre" required class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Tu nombre completo" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-secondary">Correo electrónico</label>
                    <input type="email" name="correo" required class="w-full border border-gray-300 rounded px-3 py-2" placeholder="tu@correo.com" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-secondary">Teléfono (opcional)</label>
                    <input type="text" name="telefono" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="+34 600 000 000" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-secondary">Asunto</label>
                    <input type="text" name="asunto" required class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Consulta o motivo del mensaje" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-secondary">Mensaje</label>
                    <textarea name="mensaje" required class="w-full border border-gray-300 rounded px-3 py-2" rows="4" placeholder="Cuéntanos en qué podemos ayudarte..."></textarea>
                </div>
                
                <div class="flex justify-center">
                    <div class="cf-turnstile" data-sitekey="<?php echo $_ENV['CLOUDFLARE_TURNSTILE_SITE_KEY'] ?>"></div>
                </div>
                
                <button type="submit" class="w-full bg-kinetic-500 text-white font-semibold rounded py-2 hover:bg-kinetic-600 transition">Enviar mensaje</button>
            </form>

            <!-- INFORMACIÓN DE CONTACTO -->
            <div class="bg-card text-kinetic-900 dark:bg-gray-900 rounded-xl shadow-sm p-8 flex flex-col space-y-6">
                <h2 class="font-semibold text-xl mb-2 text-main">Información de contacto</h2>
                <div class="text-sm text-kinetic-700 space-y-2">
                    <p><strong>📍 Dirección:</strong><br>123 Wellness Avenue, Healing City, HC 12345, España</p>
                    <p><strong>📞 Teléfono:</strong><br>+34 600 132 456 / +34 910 456 789</p>
                    <p><strong>📧 Email:</strong><br><a href="mailto:info@reflexiokinetp.es" class="text-kinetic-600 hover:underline">info@reflexiokinetp.es</a></p>
                    <p><strong>⏰ Horario:</strong><br>Lunes - Viernes: 9:00 - 20:00<br>Sábados: 10:00 - 14:00<br>Domingos: Cerrado</p>
                </div>
                <div class="flex items-center justify-center mt-4">
                    <a href="https://wa.me/34600132456?text=Hola,%20quiero%20información%20o%20reservar%20una%20cita" target="_blank" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-3 rounded-full shadow-lg transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A12.07 12.07 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.97L0 24l6.22-1.63A12.07 12.07 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.21-1.25-6.23-3.48-8.52zM12 22c-1.85 0-3.68-.5-5.25-1.44l-.38-.22-3.69.97.99-3.59-.25-.37A9.94 9.94 0 0 1 2 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.2-7.8c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.28.7.9.86.16.18.32.2.6.07.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.28-.48.09-.18.05-.36-.02-.5-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.36-.01-.56-.01-.2 0-.52.07-.8.34-.28.28-1.08 1.06-1.08 2.58 0 1.52 1.1 2.99 1.25 3.2.15.21 2.17 3.32 5.27 4.52.74.32 1.32.51 1.77.65.74.24 1.41.21 1.94.13.59-.09 1.65-.67 1.88-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/></svg>
                        Escribir por WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <!-- MAPA -->
        <div class="mt-10">
            <h3 class="font-semibold mb-2">Ubicación</h3>
            <div class="rounded-xl overflow-hidden shadow-md w-full h-96">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d759.5727940873511!2d-3.7011288013112935!3d40.402399887505794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd42263285d747bb%3A0x24ad438bfa94b663!2sGoiko!5e0!3m2!1ses!2ses!4v1746457740126!5m2!1ses!2ses"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>
</main>