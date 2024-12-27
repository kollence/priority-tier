import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// resources/js/bootstrap.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    cluster: import.meta.env.VITE_REVERB_APP_CLUSTER,
    // key: import.meta.env.VITE_REVERB_APP_KEY,
    // wsHost: import.meta.env.VITE_REVERB_HOST,
    // wsPort: import.meta.env.VITE_REVERB_PORT,
    // wssPort: import.meta.env.VITE_REVERB_PORT,
    // forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    // enabledTransports: ['ws', 'wss'],
});

// Vue/React component example
const userId = document.querySelector('meta[name="user-id"]').content;

Echo.channel(`import-notifications.${userId}`)
    .listen('DataImportCompleted', (e) => {
        if (e.status === 'success') {
            showSuccessNotification(e.message);
        } else {
            showErrorNotification(e.message);
        }
    });

function showSuccessNotification(message) {
    // Implement your popup/toast notification
    alert(message);
}

function showErrorNotification(message) {
    // Implement your popup/toast notification
    alert(message);
}