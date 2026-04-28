(function () {
    const modal   = document.getElementById('delivery-qr-modal');
    if (!modal) return;

    const canvas  = document.getElementById('delivery-qr-canvas');
    const titleEl = modal.querySelector('.delivery-qr-title');
    const closeEl = modal.querySelector('.delivery-qr-close');

    document.querySelectorAll('.delivery-qr-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const card = btn.closest('.delivery-btn-card');
            const url  = card.dataset.directUrl;
            const name = card.dataset.platformName;

            titleEl.textContent = 'สั่งผ่าน ' + name;
            canvas.innerHTML    = '';

            QRCode.toCanvas(url, { width: 220, margin: 1, color: { dark: '#1e293b', light: '#ffffff' } }, function (err, c) {
                if (!err) canvas.appendChild(c);
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    closeEl?.addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
})();
