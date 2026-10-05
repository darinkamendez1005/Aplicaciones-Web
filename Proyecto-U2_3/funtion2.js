const btnTema2 = document.querySelector('#btn_modo'); /*const no necesita tipo de valor y el # es para mandar a llamar el id*/
const padre = document.getElementById('div_papa'); /*aqui solo mandamos a llamra el cuerpo*/
btnTema2.addEventListener('click', () =>{ /*Aqui mandamos a llamar el evento que es la interaccion con la que lo mandaremos a llmar para que funcione*/ 

    padre.classList.toggle('modo_transparente'); /*aqui el toggle sirve para alternar la clase */

    if (padre.classList.contains('modo_transparente')) { /*aqui el contains sirve para saber si la clase esta activa o no*/
        btnTema2.textContent = 'OPACO :)';
    } else {
        btnTema2.textContent = 'TRANSPARENTE (:';
    }
    console.log('Modo transparente activo:', padre.classList.contains('modo_transparente'));
});

//FUNCION PARA VALIDAR EL FORMULARIO
const form = document.getElementById('formulario');
const nombreInput = document.getElementById('nombre');
const correoInput = document.getElementById('correo');
const mensajeInput = document.getElementById('mensaje');
const celularInput = document.getElementById('celular');


form.addEventListener('submit', (event) => {
    event.preventDefault(); // Evita que el formulario se envíe automáticamente

    //limpiaremos los mensajes de error anteriores
    divrespuestas.innerHTML = '';

    //crearemos un arreglo para guardar los mensajes de error
    const errores = [];

    //validamos el campo nombre
    if (nombreInput.value.trim() === '') {
        errores.push('El campo nombre es obligatorio.');
    }
    
    //validamos el campo correo
    if (correoInput.value.trim() === '') {  
        errores.push('El campo correo es obligatorio.');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correoInput.value)) {
        errores.push('El campo correo no es válido.');
    }

    //validamos el campo mensaje
    if (mensajeInput.value.trim() === '') {
        errores.push('El campo mensaje es obligatorio.');
    }

    //validamos el campo celular
    if (celularInput.value.trim() === '') {
        errores.push('El campo celular es obligatorio.');
    } else if (!/^\d{10}$/.test(celularInput.value)) {
        errores.push('El campo celular debe contener 10 dígitos numéricos.');
    }

    //si hay errores, los mostramos en el div de respuestas
    if (errores.length > 0) {
        divrespuesta.style.color = 'red'; //cambiamos el color del texto a rojo
        divrespuesta.innerHTML = errores.join('<br>'); //mostramos los errores en el div de respuestas
    } else {
        divrespuesta.style.color = 'green';
        divrespuesta.innerHTML = 'Formulario válido';
        form.reset(); //limpiamos el formulario
    }
});

