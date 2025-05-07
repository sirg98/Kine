<?php
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - KineticCare</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="tailwind-colors.css" rel="stylesheet">
</head>
<body class="text-gray-900">
    <main>
        <section class="py-16 ">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl md:text-5xl font-bold text-center mb-4 text-kinetic-900">Pide tu cita o contacta con nosotros</h1>
                <p class="text-center text-base text-kinetic-700 mb-8 max-w-2xl mx-auto">Estamos aquí para responder a tus preguntas y ayudarte a comenzar tu viaje hacia la salud óptima.</p>
            </div>
        </section>
        <section class="container mx-auto px-4 pb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Formulario -->
                <form class="bg-card rounded-xl shadow-sm p-8 flex flex-col space-y-4">
                    <h2 class="font-semibold text-xl mb-2 text-main">Envíanos un mensaje</h2>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-secondary">Nombre completo</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Tu nombre">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-secondary">Correo electrónico</label>
                        <input type="email" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="tu@ejemplo.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-secondary">Teléfono (opcional)</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="+34 600 000 000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 #">Asunto</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="¿Cómo podemos ayudarte?">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 text-secondary">Mensaje</label>
                        <textarea class="w-full border border-gray-300 rounded px-3 py-2" rows="4" placeholder="Escribe tu mensaje aquí..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-kinetic-500 text-white font-semibold rounded py-2 hover:bg-kinetic-600 transition">Enviar mensaje</button>
                </form>
                <!-- Información de contacto -->
                <div class="bg-card rounded-xl shadow-sm p-8 flex flex-col space-y-6">
                    <h2 class="font-semibold text-xl mb-2 text-main">Información de contacto</h2>
                    <div class="flex items-start gap-3">
                        <span class="bg-kinetic-100 text-kinetic-700 rounded-full w-8 h-8 flex items-center justify-center mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin h-6 w-6 text-kinetic-600" data-lov-id="src\pages\Contact.tsx:148:22" data-lov-name="MapPin" data-component-path="src\pages\Contact.tsx" data-component-line="148" data-component-file="Contact.tsx" data-component-name="MapPin" data-component-content="%7B%22className%22%3A%22h-6%20w-6%20text-kinetic-600%22%7D"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg></span>
                        <div>
                            <div class="font-semibold text-secondary">Dirección</div>
                            <div class="text-sm text-kinetic-700">123 Wellness Avenue<br>Healing City, HC 12345<br>España</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="bg-kinetic-100 text-kinetic-700 rounded-full w-8 h-8 flex items-center justify-center mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone h-6 w-6 text-kinetic-600" data-lov-id="src\pages\Contact.tsx:162:22" data-lov-name="Phone" data-component-path="src\pages\Contact.tsx" data-component-line="162" data-component-file="Contact.tsx" data-component-name="Phone" data-component-content="%7B%22className%22%3A%22h-6%20w-6%20text-kinetic-600%22%7D"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </span>
                        <div>
                            <div class="font-semibold text-secondary">Teléfono</div>
                            <div class="text-sm text-kinetic-700">+34 600 132 456<br>+34 910 456 789</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="bg-kinetic-100 text-kinetic-700 rounded-full w-8 h-8 flex items-center justify-center mt-1"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail h-6 w-6 text-kinetic-600" data-lov-id="src\pages\Contact.tsx:173:22" data-lov-name="Mail" data-component-path="src\pages\Contact.tsx" data-component-line="173" data-component-file="Contact.tsx" data-component-name="Mail" data-component-content="%7B%22className%22%3A%22h-6%20w-6%20text-kinetic-600%22%7D"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg></span>
                        <div>
                            <div class="font-semibold text-secondary">Correo Electrónico</div>
                            <div class="text-sm text-kinetic-700">info@kineticcare.com<br>citas@kineticcare.com</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="bg-kinetic-100 text-kinetic-700 rounded-full w-8 h-8 flex items-center justify-center mt-1"><svg class="w-5 h-5 text-kinetic-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
                        <div>
                            <div class="font-semibold text-secondary">Horario</div>
                            <div class="text-sm text-kinetic-700">Lunes - Viernes: 9:00 - 20:00<br>Sábados: 10:00 - 14:00<br>Domingos: Cerrado</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center mt-6">
                        <a href="https://wa.me/34600132456?text=Hola,%20quiero%20información%20o%20reservar%20una%20cita" target="_blank" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-3 rounded-full shadow-lg transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A12.07 12.07 0 0 0 12 0C5.37 0 0 5.37 0 12c0 2.11.55 4.16 1.6 5.97L0 24l6.22-1.63A12.07 12.07 0 0 0 12 24c6.63 0 12-5.37 12-12 0-3.21-1.25-6.23-3.48-8.52zM12 22c-1.85 0-3.68-.5-5.25-1.44l-.38-.22-3.69.97.99-3.59-.25-.37A9.94 9.94 0 0 1 2 12c0-5.52 4.48-10 10-10s10 4.48 10 10-4.48 10-10 10zm5.2-7.8c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.14-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.28-.48.09-.18.05-.36-.02-.5-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.36-.01-.56-.01-.2 0-.52.07-.8.34-.28.28-1.08 1.06-1.08 2.58 0 1.52 1.1 2.99 1.25 3.2.15.21 2.17 3.32 5.27 4.52.74.32 1.32.51 1.77.65.74.24 1.41.21 1.94.13.59-.09 1.65-.67 1.88-1.32.23-.65.23-1.2.16-1.32-.07-.12-.25-.18-.53-.32z"/></svg>
                            Escribir por WhatsApp
                        </a>
                    </div>
                </div>
            </div>
            <!-- Mapa -->
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
</body>
</html>
