document.addEventListener('DOMContentLoaded', () => {
    const btns   = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');
    const eventId = document.querySelector('.manager-tabs').dataset.eventId;

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            btns.forEach(b   => b.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');

            if (btn.dataset.tab === 'itinerary') {
                fetchItinerary(eventId);
            }
        });
    });

    // ── Check-in ──────────────────────────────────────────────────────────
    const codeInput  = document.getElementById('checkin-code');
    const submitBtn  = document.getElementById('checkin-submit-btn');
    const resultBox  = document.getElementById('checkin-result');
    const logBox     = document.getElementById('checkin-log');

    if (codeInput && submitBtn) {
        // Force uppercase as user types
        codeInput.addEventListener('input', () => {
            const pos = codeInput.selectionStart;
            codeInput.value = codeInput.value.toUpperCase().replace(/[^A-F0-9]/g, '');
            codeInput.setSelectionRange(pos, pos);
            submitBtn.disabled = codeInput.value.length !== 16;
        });

        codeInput.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !submitBtn.disabled) submitBtn.click();
        });

        submitBtn.disabled = true;

        submitBtn.addEventListener('click', async () => {
            const code = codeInput.value.trim();
            if (code.length !== 16) return;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Checking…';
            hideResult();

            try {
                const res = await fetch(SITE_URL + 'api/checkin', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({ verify_code: code, event_id: parseInt(eventId, 10) }),
                });
                const data = await res.json();
                handleCheckinResponse(data, code);
            } catch (err) {
                showResult('error', '✕', 'Network error. Please try again.', '');
            } finally {
                submitBtn.textContent = 'Check In';
                codeInput.value = '';
                submitBtn.disabled = true;
                codeInput.focus();
                refreshStats(eventId);
            }
        });
    }

    // Load initial stats
    refreshStats(eventId);


    //-- itinerary section ------------------------------------------------
    const addBtn = document.querySelector(".addToItinerary-btn");
    addBtn.addEventListener("click",() =>{
        showItenararyForm(eventId);
       
    })



});

// ── Stats ────────────────────────────────────────────────────────────────
function refreshStats(eventId) {
    fetch(SITE_URL + 'api/event/' + eventId + '/checkin-stats')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const s = data.stats;
            const ci = document.getElementById('stat-checkedin');
            const tot = document.getElementById('stat-total');
            if (ci)  ci.textContent  = s.total_checked_in + ' checked in';
            if (tot) tot.textContent = s.total_registered + ' registered';
        })
        .catch(() => {});
}

// ── Result flash ─────────────────────────────────────────────────────────
function handleCheckinResponse(data, code) {
    if (data.success) {
        const p = data.participant;
        showResult('success', '✓',
            p.name,
            p.ticket_name + ' · ' + formatTime(p.checkin_time)
        );
        addLogRow('ok', '✓', p.name, p.ticket_name, new Date());
    } else if (data.message && data.message.includes('already used')) {
        const p = data.participant;
        const desc = p ? p.name : 'Unknown';
        const sub  = p ? (p.ticket_name + ' · already scanned at ' + formatTime(data.used_at)) : data.message;
        showResult('warn', '⚠', desc, sub);
        addLogRow('dup', '⚠', desc, 'Already used', new Date());
    } else {
        showResult('error', '✕', 'Invalid code', data.message || 'Ticket not recognised.');
        addLogRow('err', '✕', code, data.message || 'Invalid', new Date());
    }
}

function showResult(type, icon, name, sub) {
    const box = document.getElementById('checkin-result');
    box.className = 'checkin-result ' + type;
    box.innerHTML = `
        <span class="checkin-result-icon">${icon}</span>
        <div class="checkin-result-body">
            <div class="result-name">${escHtml(name)}</div>
            <div class="result-sub">${escHtml(sub)}</div>
        </div>`;

    clearTimeout(box._timer);
    box._timer = setTimeout(() => hideResult(), 6000);
}

function hideResult() {
    const box = document.getElementById('checkin-result');
    if (box) box.className = 'checkin-result hidden';
}

// ── Log rows ─────────────────────────────────────────────────────────────
function addLogRow(type, icon, name, meta, date) {
    const log = document.getElementById('checkin-log');
    const empty = log.querySelector('.empty-state');
    if (empty) empty.remove();

    const row = document.createElement('div');
    row.className = 'checkin-log-row';
    row.innerHTML = `
        <div class="checkin-log-icon ${type}">${icon}</div>
        <div class="checkin-log-info">
            <div class="checkin-log-name">${escHtml(name)}</div>
            <div class="checkin-log-meta">${escHtml(meta)}</div>
        </div>
        <div class="checkin-log-time">${formatTime(date)}</div>`;

    log.insertBefore(row, log.firstChild);
}

// ── Helpers ───────────────────────────────────────────────────────────────
function formatTime(val) {
    if (!val) return '';
    const d = (val instanceof Date) ? val : new Date(val.replace(' ', 'T'));
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

function escHtml(str) {
    return String(str ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}


// ── Itinerary modal ──────────────────────────────────────────────────────
function fetchItinerary(eventId) {
    fetch(SITE_URL + 'api/event/' + eventId + '/itinerary')
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const list = document.getElementById('itinerary-list');
            list.innerHTML = '';
            if (data.items.length === 0) {
                list.innerHTML = '<p class="empty-state">No itinerary items yet.</p>';
            } else {
                data.items.forEach(item => addItineraryItem(item, eventId));
            }
        })
        .catch(() => {});
}

function showItenararyForm(eventId) {
    const modal = document.getElementById('itinerary-modal');
    const form  = document.getElementById('itinerary-form');
    form.reset();

    // Pre-populate with event start datetime
    if (typeof EVENT_START_AT !== 'undefined') {
        document.getElementById('itin-start-time').value = EVENT_START_AT;
        document.getElementById('itin-end-time').value   = EVENT_START_AT;
    }

    modal.classList.remove('hidden');
    document.getElementById('itin-title').focus();

    const closeModal = () => modal.classList.add('hidden');

    const closeBtn  = document.getElementById('close-itinerary-modal');
    const cancelBtn = document.getElementById('cancel-itinerary-modal');
    const newClose  = closeBtn.cloneNode(true);
    const newCancel = cancelBtn.cloneNode(true);
    closeBtn.replaceWith(newClose);
    cancelBtn.replaceWith(newCancel);
    newClose.addEventListener('click', closeModal);
    newCancel.addEventListener('click', closeModal);

    modal.onclick = e => { if (e.target === modal) closeModal(); };
    const onKey = e => { if (e.key === 'Escape') { closeModal(); document.removeEventListener('keydown', onKey); } };
    document.addEventListener('keydown', onKey);

    const newForm = form.cloneNode(true);
    form.replaceWith(newForm);

    newForm.addEventListener('submit', async e => {
        e.preventDefault();
        const saveBtn = newForm.querySelector('#itin-save-btn');

        const startVal = newForm.querySelector('#itin-start-time').value;
        const endVal   = newForm.querySelector('#itin-end-time').value;
        if (startVal < EVENT_START_AT || startVal > EVENT_END_AT) {
            alert('Start time must be within the event timeframe.'); return;
        }
        if (endVal < startVal || endVal > EVENT_END_AT) {
            alert('End time must be after start time and within the event timeframe.'); return;
        }

        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving…';

        const data = Object.fromEntries(new FormData(newForm));

        try {
            const res = await fetch(SITE_URL + 'api/event/' + eventId + '/itinerary/add', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify(data),
            });
            const json = await res.json();
            if (json.success) {
                addItineraryItem(json.item, eventId);
                closeModal();
            } else {
                alert(json.message || 'Failed to save item.');
            }
        } catch (err) {
            alert('Network error. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save Item';
        }
    });
}

function addItineraryItem(item, eventId) {
    const list  = document.getElementById('itinerary-list');
    const empty = list.querySelector('.empty-state');
    if (empty) empty.remove();

    const fmtDate = val => {
        if (!val) return '';
        return new Date(val.replace(' ', 'T'))
            .toLocaleDateString([], { weekday: 'short', month: 'short', day: 'numeric' });
    };
    const fmtTime = val => {
        if (!val) return '';
        return new Date(val.replace(' ', 'T'))
            .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };

    const startISO = item.start_time.replace(' ', 'T');
    const endISO   = item.end_time ? item.end_time.replace(' ', 'T') : null;

    const sDate = startISO.slice(0, 10);
    const eDate = endISO ? endISO.slice(0, 10) : null;
    const sameDay = eDate && sDate === eDate;

    const dateLabel = sameDay || !eDate
        ? fmtDate(item.start_time)
        : fmtDate(item.start_time) + ' – ' + fmtDate(item.end_time);

    const timeLabel = !endISO
        ? fmtTime(item.start_time)
        : fmtTime(item.start_time) + ' – ' + fmtTime(item.end_time);

    const el = document.createElement('div');
    el.className        = 'itinerary-item';
    el.dataset.id       = item.id;
    el.dataset.startIso = startISO;
    el.innerHTML = `
        <div class="itin-time">
            <span class="itin-date">${escHtml(dateLabel)}</span>
            <span class="itin-clock">${escHtml(timeLabel)}</span>
        </div>
        <div class="itin-body">
            <div class="itin-title">${escHtml(item.title)}</div>
            ${item.description ? `<div class="itin-details">${escHtml(item.description)}</div>` : ''}
            ${item.location    ? `<div class="itin-location">📍 ${escHtml(item.location)}</div>`    : ''}
        </div>
        <button class="itin-remove-btn" title="Remove item" aria-label="Remove item">&times;</button>`;

    el.querySelector('.itin-remove-btn').addEventListener('click', async () => {
        if (!confirm('Remove this itinerary item?')) return;

        try {
            const res = await fetch(SITE_URL + 'api/event/' + eventId + '/itinerary/remove', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ item_id: item.id }),
            });
            const json = await res.json();
            if (json.success) {
                el.classList.add('itin-removing');
                el.addEventListener('animationend', () => {
                    el.remove();
                    if (!list.querySelector('.itinerary-item')) {
                        list.innerHTML = '<p class="empty-state">No itinerary items yet.</p>';
                    }
                }, { once: true });
            }
        } catch (err) { /* silently ignore */ }
    });

    // insert sorted by ISO start time
    const siblings = [...list.querySelectorAll('.itinerary-item')];
    const after = siblings.find(s => s.dataset.id !== String(item.id) && s.dataset.startIso > startISO);
    after ? list.insertBefore(el, after) : list.appendChild(el);
}
