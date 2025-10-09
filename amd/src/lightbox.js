/* eslint-disable no-undef */
define(['core/log'], function(Log) {
    function init() {
        try {
            const modal = document.getElementById('pptbook-modal');
            const modalImg = document.getElementById('pptbook-modal-img');
            if (!modal || !modalImg) { return; }

            // Nur Lightbox – keine Interception der Pagination!
            document.querySelectorAll('a.pptbook-zoom').forEach(a => {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    modalImg.src = a.getAttribute('data-full') || a.getAttribute('href');
                    modal.style.display = 'flex';
                });
            });
            modal.addEventListener('click', () => {
                modal.style.display = 'none';
                modalImg.src = '';
            });
        } catch (e) {
            Log.error('pptbook lightbox init failed: ' + e.message);
        }
    }
    return { init };
});
