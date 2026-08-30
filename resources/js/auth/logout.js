import { Selector, Request, Toast, Alert } from "../main.js";
const logoutBtn = Selector.id('logoutBtn');

logoutBtn.addEventListener('click', async (e) => {
    e.preventDefault();
    Alert.show("Are you sure you want to logout!", () => {
        const request = new Request({
            url: '/logout',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                _token: document.querySelector('meta[name="csrf-token"]').content
            }),
        });

        request.send(response => {

            if (response.status === 200) {
                window.location.href = '/login';
                return;
            }

            Toast.show('Unable to logout', 'error');
        });
    });
});