function confirmDelete(eventId)
{
    const confirmation = confirm(
        "Are you sure you want to delete this event?\n\nThis action cannot be undone."
    );

    if (!confirmation) return;

    document.getElementById("deleteEventId").value = eventId;

    document.getElementById("deleteEventForm").submit();
}

function goToManageEvent(eventId){
    if (!eventId) return;
    const id = encodeURIComponent(String(eventId));  
    window.location.href = `${SITE_URL}manageEvent/${id}`;
}
