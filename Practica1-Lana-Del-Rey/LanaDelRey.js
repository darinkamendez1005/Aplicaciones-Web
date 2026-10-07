const btnTema2 = document.getElementById('#btn_modo');
const divInicial = document.getElementById('DHM_divInicial');
const fotoLana = document.getElementById('#fotoLana');/*Este es para mandar a llamar la imagen que cambia con el tema*/
btnTema2.addEventListener('click', () => {

divInicial.classList.toggle('eraOscura');

if (divInicial.classList.contains('eraOscura')) {
    btnTema2.textContent = 'Era Born to Die';
    fotoLana.src = 'https://www.gq.com.mx/actualidad/musica/articulos/lana-del-rey-nuevo-album-lust-for-life/8358'; /*Aquí se cambia la imagen de Lana Del Rey cuando se activa el modo oscuro*/
    } else {
        btnTema2.textContent = 'Era Ultraviolence';
        fotoLana.src = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXDnVdwQjvwMpoXNqCznMfY5C_6sc_iPO8rXWbWHx9waDcKK9xPv8Coz3N&s=10'; /*Aquí se cambia a la imagen original de Lana Del Rey*/
    }
    console.log('Era Ultraviolence activo:', divInicial.classList.contains('eraOscura'));
});