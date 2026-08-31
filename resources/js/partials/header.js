import { Selector } from '../main';
const openSidenavBtn = Selector.id('openSidenavBtn')
const sidenav = Selector.id('sidenav');

document.addEventListener('click', (event) => {

    if (
        !sidenav.contains(event.target) &&
        !openSidenavBtn.contains(event.target)
    ) {
        sidenav.classList.remove('active');
    }

});
openSidenavBtn.addEventListener('click', () => {
    sidenav.classList.add('active');
});