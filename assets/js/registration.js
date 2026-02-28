document.getElementById('register-btn').addEventListener('click', function() {
    const btn = this;
    const eventId = document.getElementById('event_id').value;
    const action = btn.textContent.includes('Cancel') ? 'cancel' : 'register';

    fetch(SITE_URL + action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: "event_id=" + eventId
    })
    .then(res => res.json())
    .then(data => {
        const messageEl = document.getElementById('message');
        messageEl.textContent = data.message;

        btn.textContent = action === 'register'
            ? 'Cancel Registration'
            : 'Register';
    })
    .catch(err => console.error(err));
});
