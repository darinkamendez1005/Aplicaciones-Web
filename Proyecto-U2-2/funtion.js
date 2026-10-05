const btnTema = document.querySelector('#btn_cambio'); /*const no necesita tipo de valor y el # es para mandar a llamar el id*/
const body = document.body; /*aqui solo mandamos a llamra el cuerpo*/
btnTema.addEventListener('click', () =>{ /*Aqui mandamos a llamar el evento que es la interaccion con la que lo mandaremos a llmar para que funcione*/ 

    body.classList.toggle('modo_oscuro'); /*aqui */

    if (body.classList.contains('modo_oscuro')){
        btnTema.textContent = 'Modo Claro';
    } else {
        btnTema.textContent = 'Modo Oscuro';
    }
    console.log('Modo oscuro activo:', body.classList.contains('modo_oscuro'));

});