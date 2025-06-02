<!-- Modal Todas las Citas (Calendario Vanilla) -->
<div id="modalGestionarCitas" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border border-card w-full max-w-6xl shadow-lg rounded-md bg-blue dark:bg-gray-800 flex flex-col min-h-[750px]">
        <!-- Título -->
        <div class="px-8 pt-8 pb-4 border-b border-card flex items-center justify-between">
            <h2 class="text-2xl font-bold text-main">Todas las Citas</h2>
            <button type="button" onclick="closeModalGestionarCitas()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex flex-1 min-h-[600px]">
            <!-- Calendario Vanilla -->
            <div class="w-1/2 border-r border-card p-4 bg-card dark:bg-gray-700 rounded-l-md flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <button id="prevMonthBtn" class="px-2 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">&#8592;</button>
                    <div id="calendarMonthLabel" class="font-semibold text-lg text-main"></div>
                    <button id="nextMonthBtn" class="px-2 py-1 rounded bg-blue-100 text-blue-700 hover:bg-blue-200">&#8594;</button>
                </div>
                <input type="date" id="calendarDateInput" class="mb-4 px-3 py-2 border border-card rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full" />
                <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>
            </div>
            <!-- Panel de detalles de la cita -->
            <div class="w-1/2 p-4 flex flex-col">
                <h3 class="text-lg font-semibold text-main mb-4">Detalle de la cita</h3>
                <div id="detalleCitaPanel" class="flex-1 overflow-y-auto">
                    <p class="text-secondary text-center">Selecciona un día para ver los detalles.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.vanilla-calendar-day {
  width: 2.7rem;
  height: 5.4rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 9999px;
  transition: background 0.2s, color 0.2s;
  font-weight: 500;
  cursor: pointer;
  margin: 0 auto;
  position: relative;
  font-size: 1.1rem;
}
.vanilla-calendar-day.selected {
  background: #2563eb;
  color: #fff;
}
.vanilla-calendar-day.today {
  border: 2px solid #0ea5e9;
}
.vanilla-calendar-dot {
  width: 0.25rem;
  height: 0.25rem;
  background: #2563eb;
  border-radius: 9999px;
  margin-top: 0.15rem;
  display: block;
}
</style>

<script>
let citasPorDia = {}; // { 'YYYY-MM-DD': [cita, ...] }
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let selectedDate = null;

function openModalGestionarCitas() {
    document.getElementById('modalGestionarCitas').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    cargarCitasMes(currentYear, currentMonth);
}
function closeModalGestionarCitas() {
    document.getElementById('modalGestionarCitas').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
if (document.getElementById('modalGestionarCitas')) {
    document.getElementById('modalGestionarCitas').addEventListener('click', function(e) {
        if (e.target === this) closeModalGestionarCitas();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('prevMonthBtn').onclick = function() {
        if (currentMonth === 0) { currentMonth = 11; currentYear--; } else { currentMonth--; }
        cargarCitasMes(currentYear, currentMonth);
    };
    document.getElementById('nextMonthBtn').onclick = function() {
        if (currentMonth === 11) { currentMonth = 0; currentYear++; } else { currentMonth++; }
        cargarCitasMes(currentYear, currentMonth);
    };
    document.getElementById('calendarDateInput').onchange = function(e) {
        const d = new Date(e.target.value);
        if (!isNaN(d)) {
            currentMonth = d.getMonth();
            currentYear = d.getFullYear();
            cargarCitasMes(currentYear, currentMonth, e.target.value);
        }
    };
});

function cargarCitasMes(year, month, selectDate = null) {
    // Limpiar selección
    selectedDate = null;
    document.getElementById('detalleCitaPanel').innerHTML = '<p class="text-secondary text-center">Selecciona un día para ver los detalles.</p>';
    // Mostrar mes y año
    const monthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    document.getElementById('calendarMonthLabel').textContent = `${monthNames[month]} ${year}`;
    // AJAX para traer citas del mes
    fetch(`/src/ajax/citas_terapeuta_eventos.php?year=${year}&month=${month+1}`)
        .then(r => r.json())
        .then(eventos => {
            citasPorDia = {};
            eventos.forEach(ev => {
                const dia = ev.start.slice(0,10);
                if (!citasPorDia[dia]) citasPorDia[dia] = [];
                citasPorDia[dia].push(ev);
            });
            renderizarCalendario(year, month, selectDate);
        });
}

function renderizarCalendario(year, month, selectDate = null) {
    const grid = document.getElementById('calendarGrid');
    grid.innerHTML = '';
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay() === 0 ? 6 : firstDay.getDay() - 1; // Lunes = 0
    // Cabecera días
    const dias = ['L','M','X','J','V','S','D'];
    dias.forEach(d => {
        const th = document.createElement('div');
        th.className = 'text-xs font-bold text-center text-blue-900';
        th.textContent = d;
        grid.appendChild(th);
    });
    // Espacios vacíos
    for (let i = 0; i < startDay; i++) {
        const empty = document.createElement('div');
        grid.appendChild(empty);
    }
    // Días del mes
    for (let d = 1; d <= lastDay.getDate(); d++) {
        const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const btn = document.createElement('button');
        btn.className = 'vanilla-calendar-day text-main';
        btn.textContent = d;
        if (dateStr === (selectDate || '')) {
            btn.classList.add('selected');
            selectedDate = dateStr;
            mostrarDetalleCita(dateStr);
        }
        if (dateStr === (new Date().toISOString().slice(0,10))) {
            btn.classList.add('today');
        }
        if (citasPorDia[dateStr]) {
            // Mini círculo debajo del número
            const dot = document.createElement('span');
            dot.className = 'vanilla-calendar-dot';
            btn.appendChild(dot);
            btn.title = 'Ver citas';
        }
        btn.onclick = function() {
            document.querySelectorAll('.vanilla-calendar-day.selected').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            selectedDate = dateStr;
            mostrarDetalleCita(dateStr);
        };
        grid.appendChild(btn);
    }
}

function mostrarDetalleCita(dateStr) {
    const panel = document.getElementById('detalleCitaPanel');
    if (!citasPorDia[dateStr] || citasPorDia[dateStr].length === 0) {
        panel.innerHTML = '<p class="text-secondary text-center">No hay citas para este día.</p>';
        return;
    }
    let html = '<ul class="space-y-4">';
    citasPorDia[dateStr].forEach(ev => {
        html += `<li class="border border-card bg-card rounded-lg p-4 bg-white dark:bg-gray-900">
            <div class="font-bold text-main mb-1 flex items-center"><span class='mr-2'>🩺</span> ${ev.title}</div>
            <div class="text-secondary">Motivo: ${ev.extendedProps.motivo ?? 'Sin motivo'}</div>
            <div class="text-xs text-main mt-1">${ev.extendedProps.tratamiento ? 'Tratamiento: ' + ev.extendedProps.tratamiento : ''}</div>
            <div class="text-xs text-gray-500 mt-1">Hora: ${new Date(ev.start).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        </li>`;
    });
    html += '</ul>';
    panel.innerHTML = html;
}
</script> 