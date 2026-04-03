document.addEventListener('DOMContentLoaded', () => {
    const btns   = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');

    // grab event ID from the wrapper div
    const eventId = document.querySelector('.manager-tabs').dataset.eventId;

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            btns.forEach(b   => b.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');

            if (btn.dataset.tab === 'participants') {
                loadParticipants(eventId);
            }
            if (btn.dataset.tab === 'itinerary') {
                
            }
        });
    });

    // load participants on page load since it's the default active tab
    loadParticipants(eventId);
});

function loadParticipants(eventId) {
    const container = document.querySelector('.participant-list');
    const countBadge = document.querySelector('.participant-count');

    
}

//to be called when the view all participants button is clicked
function renderParticipants(participants, container) {
    if (participants.length === 0) {
        container.innerHTML = '<p class="empty-state">No participants registered yet.</p>';
        return;
    }

    const rows = participants.map(p => `
        <div class="participant-row">
            <span class="participant-name">${p.username}</span>
            <span class="participant-email">${p.email}</span>
        </div>
    `).join('');

    container.innerHTML = rows;
}


function loadParticipants(eventId) {
    const container = document.querySelector('.itinerary-content');
    renderItineraryItems(eventId);
    
}

function renderItineraryItems(){
    
}