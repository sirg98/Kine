<?php
// Directorio de logs
$logs_dir = __DIR__ . '/../logs';
$log_files = array_filter(scandir($logs_dir), function($f) use ($logs_dir) {
    return is_file("$logs_dir/$f") && preg_match('/\\.log$/', $f);
});
?>
<style>
.mark-highlight {
    background-color: #facc15; /* amarillo */
    color: #1f2937; /* texto gris oscuro para contraste */
    font-weight: bold;
    padding: 0 2px;
    border-radius: 2px;
}
</style>

<div class="flex h-[500px] w-full max-w-5xl overflow-hidden">

    <!-- Panel lateral -->
    <div class="w-1/4 bg-gray-100 bg-card rounded-l-xl p-4 overflow-y-auto border-r border-card">
        <h3 class="text-lg font-semibold mb-4 text-kinetic-900 dark:text-gray-100">Archivos de Logs</h3>
        <ul id="logList" class="space-y-2">
            <?php foreach ($log_files as $file): ?>
                <li>
                    <button type="button" class="log-btn w-full text-left px-3 py-2 rounded-lg hover:bg-blue-100 dark:hover:bg-gray-700 transition text-kinetic-900 dark:text-gray-100" data-filename="<?= htmlspecialchars($file) ?>">
                        <?= htmlspecialchars($file) ?>
                    </button>
                </li>
            <?php endforeach; ?>
            <?php if (empty($log_files)): ?>
                <li class="text-gray-400">No hay archivos de log.</li>
            <?php endif; ?>
        </ul>
    </div>
    <!-- Área de visualización -->
    <div class="flex-1 bg-white dark:bg-gray-900 rounded-r-xl p-6 overflow-y-auto border border-card relative">
        <!-- Filtros -->
        <div class="mb-4 flex flex-col md:flex-row items-start md:items-center gap-4">
            <input type="text" id="logSearchInput" placeholder="Buscar texto..." class="px-3 py-2 rounded border w-full md:w-1/3 dark:bg-gray-800 dark:text-white" />
            <input type="date" id="logDateMin" class="px-3 py-2 rounded border w-full md:w-1/4 dark:bg-gray-800 dark:text-white" />
            <input type="date" id="logDateMax" class="px-3 py-2 rounded border w-full md:w-1/4 dark:bg-gray-800 dark:text-white" />
        </div>

        <!-- Visor -->
        <div id="logViewer" class="w-full h-96 min-w-[400px] max-w-full overflow-y-auto bg-gray-900 text-green-200 rounded p-4 whitespace-pre-wrap text-sm">
            <div class="text-gray-400 text-center mt-20">Selecciona un archivo de log para visualizar su contenido.</div>
        </div>
    </div>

</div>
<script>
document.querySelectorAll('.log-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.log-btn').forEach(b => b.classList.remove('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400'));
        this.classList.add('bg-blue-600', 'text-white', 'shadow', 'ring-2', 'ring-blue-400');
        const filename = this.dataset.filename;
        const viewer = document.getElementById('logViewer');
        viewer.innerHTML = '<div class="text-center text-gray-400 mt-20 animate-pulse">Cargando...</div>';
        fetch(`/src/pages/admin/logs/${filename}`)
            .then(r => {
                if (!r.ok) throw new Error('No se pudo cargar el archivo');
                return r.text();
            })
            .then(text => {
                const viewer = document.getElementById('logViewer');
                viewer.dataset.raw = text; // Guardamos texto original para filtrado
                viewer.innerHTML = `<pre class='bg-gray-900 text-green-200 rounded p-4 whitespace-pre-wrap text-sm'>${escapeHtml(text)}</pre>`;

            })
            .catch(err => {
                viewer.innerHTML = `<div class='text-red-500 text-center mt-20'>Error: ${err.message}</div>`;
            });
    });
});
function escapeHtml(text) {
    return text.replace(/[&<>"']/g, function(m) {
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;','\'':'&#39;'})[m];
    });
}

function applyLogFilters() {
    const text = document.getElementById('logViewer').dataset.raw || '';
    const search = document.getElementById('logSearchInput').value.toLowerCase();
    const minDate = document.getElementById('logDateMin').value;
    const maxDate = document.getElementById('logDateMax').value;

    const lines = text.split('\n').filter(line => {
        const matchText = search === '' || line.toLowerCase().includes(search);

        let matchDate = true;
        const dateMatch = line.match(/^\[(\d{4}-\d{2}-\d{2})/);
        if (dateMatch) {
            const logDate = dateMatch[1];
            if (minDate && logDate < minDate) matchDate = false;
            if (maxDate && logDate > maxDate) matchDate = false;
        }

        return matchText && matchDate;
    });

    let highlighted = lines.map(line => {
    let safeLine = escapeHtml(line);
    if (search) {
        const regex = new RegExp(`(${search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
        safeLine = safeLine.replace(regex, '<mark class="mark-highlight">$1</mark>');
    }
    return safeLine;
}).join('\n');

document.getElementById('logViewer').innerHTML = `<pre>${highlighted}</pre>`;

}

// Eventos para los filtros
document.getElementById('logSearchInput').addEventListener('input', applyLogFilters);
document.getElementById('logDateMin').addEventListener('change', applyLogFilters);
document.getElementById('logDateMax').addEventListener('change', applyLogFilters);

</script>
