<?php
// Obtener todos los pacientes
$sql = "SELECT id, nombre, apellidos, 'paciente' as tipo FROM usuarios WHERE tipo = 'paciente' ORDER BY apellidos, nombre";
$result = mysqli_query($conn, $sql);
$pacientes = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Obtener todos los terapeutaes (excluyendo al terapeuta actual)
$sql = "SELECT id, nombre, apellidos, 'terapeuta' as tipo FROM usuarios WHERE tipo = 'terapeuta' AND id != $terapeuta_id ORDER BY apellidos, nombre";
$result = mysqli_query($conn, $sql);
$terapeutas = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div id="modalChat" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 max-h-screen">
    <div class="relative top-10 mx-auto p-0 border border-card w-full max-w-6xl shadow-lg rounded-md bg-blue dark:bg-gray-800 flex flex-col min-h-[650px]">
        <!-- Título -->
        <div class="px-8 pt-8 pb-4 border-b border-card flex items-center justify-between">
            <h2 class="text-2xl font-bold text-main">Chat</h2>
            <button type="button" onclick="closeModalChatDoctor()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex flex-1">
            <!-- Columna izquierda: Lista de contactos -->
            <div class="w-1/3 bg-card dark:bg-gray-700 border-r border-card p-4 overflow-y-auto rounded-l-md">
                <!-- Tabs -->
                <div class="flex mb-4 border-b border-card">
                    <button onclick="switchTab('pacientes')" class="tab-btn px-4 py-2 text-sm font-medium text-secondary hover:text-kinetic-900 border-b-2 border-transparent hover:border-kinetic-500" data-tab="pacientes">
                        Pacientes
                    </button>
                    <button onclick="switchTab('terapeutas')" class="tab-btn px-4 py-2 text-sm font-medium text-secondary hover:text-kinetic-900 border-b-2 border-transparent hover:border-kinetic-500" data-tab="terapeutas">
                        Terapeutas
                    </button>
                </div>

                <!-- Buscador -->
                <input type="text" id="buscadorContactos" class="w-full mb-4 px-3 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar contacto...">

                <!-- Lista de Pacientes -->
                <div id="listaPacientes" class="space-y-2">
                    <?php foreach ($pacientes as $p): ?>
                        <button type="button" class="w-full text-left px-3 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-600 transition contacto-btn" 
                                data-id="<?= $p['id'] ?>" 
                                data-tipo="paciente">
                            <?= htmlspecialchars($p['apellidos'] . ', ' . $p['nombre']) ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Lista de terapeutas -->
                <div id="listaDoctores" class="space-y-2 hidden">
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
                    <p class="text-center text-secondary">Selecciona un contacto para comenzar el chat.</p>
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
let chatContactoId = null;
let chatTipo = null;
let chatInterval = null;

// Función para cambiar entre tabs
function switchTab(tab) {
    // Actualizar botones
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-kinetic-500', 'text-kinetic-900');
        btn.classList.add('border-transparent', 'text-secondary');
    });
    document.querySelector(`[data-tab="${tab}"]`).classList.add('border-kinetic-500', 'text-kinetic-900');
    document.querySelector(`[data-tab="${tab}"]`).classList.remove('border-transparent', 'text-secondary');

    // Mostrar/ocultar listas
    document.getElementById('listaPacientes').classList.toggle('hidden', tab !== 'pacientes');
    document.getElementById('listaDoctores').classList.toggle('hidden', tab !== 'terapeutas');

    // Limpiar selección actual
    document.querySelectorAll('.contacto-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400');
    });
    chatContactoId = null;
    chatTipo = null;
    document.getElementById('chatInput').disabled = true;
    document.querySelector('#chatForm button[type=submit]').disabled = true;
    document.getElementById('chatMensajes').innerHTML = '<p class="text-center text-secondary">Selecciona un contacto para comenzar el chat.</p>';
    if (chatInterval) clearInterval(chatInterval);
}

// Buscador de contactos
const buscador = document.getElementById('buscadorContactos');
buscador.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('.contacto-btn').forEach(btn => {
        const nombre = btn.textContent.toLowerCase();
        btn.style.display = nombre.includes(term) ? '' : 'none';
    });
});

// Selección de contacto
if (document.querySelectorAll('.contacto-btn').length) {
    document.querySelectorAll('.contacto-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.contacto-btn').forEach(b => b.classList.remove('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400'));
            this.classList.add('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400');
            chatContactoId = this.dataset.id;
            chatTipo = this.dataset.tipo;
            document.getElementById('chatInput').disabled = false;
            document.querySelector('#chatForm button[type=submit]').disabled = false;
            cargarMensajesDoctor();
            if (chatInterval) clearInterval(chatInterval);
            chatInterval = setInterval(cargarMensajesDoctor, 4000);
        });
    });
}

// Cargar mensajes
function cargarMensajesDoctor() {
    if (!chatContactoId || !chatTipo) return;
    fetch(`/src/ajax/chat_handler_teraupeuta.php?contacto_id=${chatContactoId}&tipo=${chatTipo}`)
        .then(r => r.json())
        .then(mensajes => {
            const cont = document.getElementById('chatMensajes');
            cont.innerHTML = '';
            if (mensajes.length === 0) {
                cont.innerHTML = '<p class="text-center text-secondary">No hay mensajes aún.</p>';
            } else {
                mensajes.forEach(msg => {
                    const align = msg.emisor === 'terapeuta' ? 'justify-end' : 'justify-start';
                    const color = msg.emisor === 'terapeuta' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100';
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
        
        fetch('/src/ajax/chat_handler_teraupeuta.php', {
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
                cargarMensajesDoctor();
            } else {
                alert('Error al enviar el mensaje: ' + res);
            }
        })
        .catch(err => {
            alert('Error de red al enviar el mensaje: ' + err);
        });
    });
}

function closeModalChatDoctor() {
    document.getElementById('modalChat').classList.add('hidden');
    document.body.style.overflow = 'auto';
    if (chatInterval) clearInterval(chatInterval);
}
</script> 