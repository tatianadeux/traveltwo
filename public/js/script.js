let menu = document.querySelector('.navMenu')
let burger = document.querySelector('.burger')

burger.addEventListener('click', () =>{
    menu.classList.toggle('active');
    burger.classList.toggle('open')
})