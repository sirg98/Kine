<?php
// Obtener los terapeutas con los que el paciente ha tenido citas
$sql = "SELECT DISTINCT d.id, d.nombre, d.apellidos, 'terapeuta' as tipo
        FROM citas c
        JOIN usuarios d ON c.terapeuta_id = d.id AND d.tipo = 'terapeuta'
        WHERE c.paciente_id = $paciente_id
        ORDER BY d.apellidos, d.nombre";
$result = mysqli_query($conn, $sql);
$terapeutas = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div id="modalChat" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border border-card w-full max-w-6xl shadow-lg rounded-md bg-blue dark:bg-gray-800 flex flex-col min-h-[650px]">
        <!-- Título -->
        <div class="px-8 pt-8 pb-4 border-b border-card flex items-center justify-between">
            <h2 class="text-2xl font-bold text-main">Contactar Médico</h2>
            <button type="button" onclick="closeModalChat('modalCitas')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex flex-1">
            <!-- Columna izquierda: Lista de terapeutas -->
            <div class="w-1/3 bg-card dark:bg-gray-700 border-r border-card p-4 overflow-y-auto rounded-l-md">
                <h3 class="text-lg font-semibold text-main mb-4">Médicos</h3>
                <input type="text" id="buscadorTerapeutas" class="w-full mb-4 px-3 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar médico...">
                <div class="space-y-2">
                    <?php foreach ($terapeutas as $d): ?>
                        <button type="button" class="w-full text-left px-3 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition contacto-btn" 
                                data-id="<?= $d['id'] ?>" 
                                data-tipo="terapeuta">
                            Dr. <?= htmlspecialchars($d['apellidos'] . ', ' . $d['nombre']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Columna derecha: Chat -->
            <div class="w-2/3 flex flex-col p-4">
            <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-900 rounded-md p-4 mb-2 border border-card max-h-96 pr-2 scrollbar-thin scrollbar-thumb-blue-300 scrollbar-track-blue-100" id="chatMensajes">
                    <p class="text-center text-secondary">Selecciona un médico para comenzar el chat.</p>
                </div>
                <form id="chatForm" class="flex mt-2">
                    <input type="text" id="chatInput" class="flex-1 border border-card rounded-l px-2 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe tu mensaje..." disabled>
                    <button type="submit" class="bg-blue-500 text-white px-4 rounded-r disabled:opacity-50" disabled>Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let chatContactoId = null;
    let chatTipo = null;
    let chatInterval = null;

    // Buscador de terapeutas
    const buscador = document.getElementById('buscadorTerapeutas');
    buscador.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.contacto-btn').forEach(btn => {
            const nombre = btn.textContent.toLowerCase();
            btn.style.display = nombre.includes(term) ? '' : 'none';
        });
    });

    // Selección de contacto
    function asignarEventosContactos() {
        document.querySelectorAll('.contacto-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.contacto-btn').forEach(b => b.classList.remove('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400'));
                this.classList.add('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400');
                chatContactoId = this.dataset.id;
                chatTipo = this.dataset.tipo;
                document.getElementById('chatInput').removeAttribute('disabled');
                document.querySelector('#chatForm button[type=submit]').removeAttribute('disabled');
                cargarMensajesPaciente();
                if (chatInterval) clearInterval(chatInterval);
                chatInterval = setInterval(cargarMensajesPaciente, 4000);
            });
        });
    }
    asignarEventosContactos();

    // Cargar mensajes
    function cargarMensajesPaciente() {
        if (!chatContactoId || !chatTipo) return;
        fetch(`/src/ajax/chat_handler_paciente.php?contacto_id=${chatContactoId}&tipo=${chatTipo}`)
            .then(r => r.json())
            .then(mensajes => {
                const cont = document.getElementById('chatMensajes');
                cont.innerHTML = '';
                if (mensajes.length === 0) {
                    cont.innerHTML = '<p class="text-center text-secondary">No hay mensajes aún.</p>';
                } else {
                    mensajes.forEach(msg => {
                        const align = msg.emisor === 'paciente' ? 'justify-end' : 'justify-start';
                        const color = msg.emisor === 'paciente' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100';
                        cont.innerHTML += `
                            <div class="flex ${align} mb-2">
                                <div class="max-w-xs px-4 py-2 rounded-lg ${color}">
                                    <span>${msg.mensaje}</span>
                                    <div class="text-xs mt-1 text-right opacity-60">${msg.fecha.slice(11,16)}</div>
                                </div>
                            </div>
                        `;
                    });
                    cont.scrollTop = cont.scrollHeight;
                }
            });
    }

    // Enviar mensaje
    if (document.getElementById('chatForm')) {
        document.getElementById('chatForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('chatInput');
            const mensaje = input.value.trim();
            if (!mensaje || !chatContactoId || !chatTipo) return;
            
            fetch('/src/ajax/chat_handler_paciente.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    contacto_id: chatContactoId,
                    tipo: chatTipo,
                    mensaje: mensaje
                })
            })
            .then(r => r.text())
            .then(res => {
                if (res.trim() === 'ok') {
                    input.value = '';
                    cargarMensajesPaciente();
                } else {
                    alert('Error al enviar el mensaje: ' + res);
                }
            })
            .catch(err => {
                alert('Error de red al enviar el mensaje: ' + err);
            });
        });
    }
});
</script>

<script>
// Abrir y cerrar modal
function openModalChat() {
    document.getElementById('modalChat').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModalChat() {
    document.getElementById('modalChat').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
// Cerrar modal al hacer clic fuera
if (document.getElementById('modalChat')) {
    document.getElementById('modalChat').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModalChat();
        }
    });
}
// (La lógica de mensajes y selección de terapeuta se implementará después)
</script> 