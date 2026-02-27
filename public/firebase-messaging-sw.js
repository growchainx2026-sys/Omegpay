importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyCJThJpuw00gwlFtytSezUmWqUF8WFSZCc",
    authDomain: "cashnex-ce3e1.firebaseapp.com",
    projectId: "cashnex-ce3e1",
    storageBucket: "cashnex-ce3e1.firebasestorage.app",
    messagingSenderId: "519169772039",
    appId: "1:519169772039:web:ddbd60f5901fdbc44cf8a2",
    measurementId: "G-EFLM0EWDFN"
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/storage/avatars/NCHm5DyVNIgB8S74jXxB73zI5pUZcirrf90H5NWy.png'
    };
    return self.registration.showNotification(notificationTitle, notificationOptions);
});
