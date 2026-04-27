/* =============================================================
   FIKA · main.js
   - Menú móvil
   - Filtrado de productos del menú
   - Validaciones básicas de formularios
   ============================================================= */

document.addEventListener('DOMContentLoaded', () => {

    /* ---------- 1. Menú hamburguesa ---------- */
    const toggle = document.querySelector('.nav-toggle');
    const links  = document.querySelector('.nav-links');
    if (toggle && links) {
        toggle.addEventListener('click', () => {
            links.classList.toggle('abierto');
        });
    }

    /* ---------- 2. Filtro del menú de productos ---------- */
    const botonesFiltro = document.querySelectorAll('.filtro-btn');
    const productos     = document.querySelectorAll('.producto');

    botonesFiltro.forEach(btn => {
        btn.addEventListener('click', () => {
            botonesFiltro.forEach(b => b.classList.remove('activo'));
            btn.classList.add('activo');

            const categoria = btn.dataset.categoria;
            productos.forEach(p => {
                const cat = p.dataset.categoria;
                if (categoria === 'todos' || cat === categoria) {
                    p.style.display = '';
                    p.classList.add('fade-in');
                } else {
                    p.style.display = 'none';
                }
            });
        });
    });

    /* ---------- 3. Validación del formulario de reserva ---------- */
    const formReserva = document.querySelector('#form-reserva');
    if (formReserva) {
        formReserva.addEventListener('submit', (ev) => {
            const fechaInput = formReserva.querySelector('[name="fecha"]');
            if (fechaInput) {
                const hoy = new Date().toISOString().split('T')[0];
                if (fechaInput.value < hoy) {
                    ev.preventDefault();
                    alert('La fecha de la reserva no puede ser anterior a hoy.');
                }
            }
        });

        // Fecha mínima = hoy
        const fechaInput = formReserva.querySelector('[name="fecha"]');
        if (fechaInput) {
            fechaInput.min = new Date().toISOString().split('T')[0];
        }
    }

    /* ---------- 4. Validación del formulario de registro ---------- */
    const formRegistro = document.querySelector('#form-registro');
    if (formRegistro) {
        formRegistro.addEventListener('submit', (ev) => {
            const pass  = formRegistro.querySelector('[name="password"]').value;
            const pass2 = formRegistro.querySelector('[name="password2"]').value;
            if (pass.length < 6) {
                ev.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres.');
                return;
            }
            if (pass !== pass2) {
                ev.preventDefault();
                alert('Las contraseñas no coinciden.');
            }
        });
    }

    /* ---------- 5. Confirmación antes de eliminar ---------- */
    document.querySelectorAll('.confirmar').forEach(el => {
        el.addEventListener('click', (ev) => {
            if (!confirm('¿Seguro que quieres continuar con esta acción?')) {
                ev.preventDefault();
            }
        });
    });

});
