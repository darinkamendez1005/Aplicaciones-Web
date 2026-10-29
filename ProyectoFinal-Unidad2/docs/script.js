// Esperamos a que todo el DOM de la página cargue por completo antes de ejecutar JS
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Seleccionamos el formulario completo mediante su ID único
    const formulario = document.querySelector('#form-opinion');
    
    // 2. Seleccionamos la caja de alerta de éxito que está oculta por defecto en el HTML
    const alertaExito = document.querySelector('#alerta-exito');

    // Verificamos por seguridad que el formulario exista en la página actual
    if (formulario) {
        
        // 3. Escuchamos el evento 'submit' (cuando el usuario da clic en el botón de enviar)
        formulario.addEventListener('submit', function(e) {
            
            // e.preventDefault() evita que la página se recargue de golpe al enviar los datos
            e.preventDefault();

            // Quitamos la clase 'd-none' de Bootstrap para hacer visible la alerta de éxito
            alertaExito.classList.remove('d-none');

            // Limpiamos los campos del formulario automáticamente para dejarlos en blanco
            formulario.reset();

            // Ocultamos la alerta de éxito de forma automática después de 4 segundos (4000 ms)
            setTimeout(function() {
                alertaExito.classList.add('d-none');
            }, 4000);
        });
    }
});