// Importar jQuery (se usará solo si no está ya definido en window)
import jQuery from 'jquery';
// Asignar jQuery a window solo si no está ya definido
if (typeof window.jQuery === 'undefined') {
    window.$ = window.jQuery = jQuery;
}

import './bootstrap';

// Importar Bootstrap explícitamente
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Inicializar los dropdowns de Bootstrap
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar todos los dropdowns
    const dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});

// Importar AdminLTE y dependencias
import 'overlayscrollbars';
import 'admin-lte';

// Importar DataTables y extensiones para Bootstrap 5
import 'datatables.net';
import 'datatables.net-bs5';
import 'datatables.net-responsive';
import 'datatables.net-responsive-bs5';

// Verificar si DataTables está disponible y asignarlo a window si es necesario
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded en app.js');
    console.log('jQuery disponible en app.js:', typeof window.jQuery !== 'undefined');
    console.log('$ disponible en app.js:', typeof window.$ !== 'undefined');
    console.log('$.fn.dataTable disponible en app.js:', typeof window.$.fn?.dataTable !== 'undefined');
});
