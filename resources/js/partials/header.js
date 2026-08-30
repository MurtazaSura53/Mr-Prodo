import { Selector } from '../main';
const openSidenavBtn = Selector.id('openSidenavBtn')
const closeSidenavBtn = Selector.id('closeSidenavBtn')
const sidenav = Selector.id('sidenav');

document.addEventListener('click', (event) => {

    if (
        !sidenav.contains(event.target) &&
        !openSidenavBtn.contains(event.target)
    ) {
        sidenav.classList.remove('active');
        openSidenavBtn.style.display = "flex";
        closeSidenavBtn.style.display = "none";
    }

});
openSidenavBtn.addEventListener('click', () => {
    sidenav.classList.add('active');
    openSidenavBtn.style.display = "none";
    closeSidenavBtn.style.display = "flex";
});