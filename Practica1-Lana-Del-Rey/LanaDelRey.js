const btnTema2 = document.querySelector('#btn_modo');
const divInicial = document.querySelector('#bienvenida');

btnTema2.addEventListener('click', () => {

    document.body.classList.toggle('eraOscura');

    if (document.body.classList.contains('eraOscura')) {
        btnTema2.textContent = 'Era Born to Die';
    } else {
        btnTema2.textContent = 'Era Ultraviolence';
    }

    console.log('Era Ultraviolence activo:', document.body.classList.contains('eraOscura'));
});
