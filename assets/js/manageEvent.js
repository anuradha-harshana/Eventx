document.addEventListener('DOMContentLoaded', () => {
    const btns   = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            // deactivate all
            btns.forEach(b   => b.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));

            // activate clicked
            btn.classList.add('active');
            document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
        });
    });
});
