import { Selector } from '../main';
Selector.id('dashboardLink').classList.add('active');
const overview = Selector.id('overview');

let isDragging = false;
let startX;
let scrollLeft;

overview.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.pageX - overview.offsetLeft;
    scrollLeft = overview.scrollLeft;

    overview.classList.add('dragging');
});

overview.addEventListener('mouseleave', () => {
    isDragging = false;
    overview.classList.remove('dragging');
});

overview.addEventListener('mouseup', () => {
    isDragging = false;
    overview.classList.remove('dragging');
});

overview.addEventListener('mousemove', (e) => {
    if (!isDragging) return;

    e.preventDefault();

    const x = e.pageX - overview.offsetLeft;
    const walk = (x - startX) * 1.5;

    overview.scrollLeft = scrollLeft - walk;
});