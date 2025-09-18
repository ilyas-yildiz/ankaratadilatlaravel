// public/js/admin/common/status-handler.js

/**
 * Durum değiştirme anahtarını dinler ve AJAX ile günceller.
 * @param {string} selector - CSS seçicisi (örn: '.blog-status-switch').
 * @param {string} modelName - Güncellenecek modelin adı (örn: 'blogs').
 * @param {string} csrfToken - Laravel'in CSRF token'ı.
 */
export function initStatusSwitch(selector, modelName, csrfToken) {
    // Tüm anahtarları seç
    const statusSwitches = document.querySelectorAll(selector);

    statusSwitches.forEach(statusSwitch => {
        statusSwitch.addEventListener('change', function () {
            const itemId = this.dataset.id;
            const newStatus = this.checked;

            const url = `/admin/${modelName}/${itemId}/status`;

            fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: newStatus })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        iziToast.success({
                            title: 'Başarılı!',
                            message: 'Durum başarıyla güncellendi.',
                            position: 'topRight'
                        });
                    } else {
                        throw new Error(data.message || 'Durum Güncellenemedi');
                    }
                })
                .catch(error => {
                    console.error('Durum güncelleme hatası:', error);
                    iziToast.error({
                        title: 'Hata:',
                        message: error.message || 'Durum Güncellenemedi',
                        position: 'topRight'
                    });
                    this.checked = !newStatus;
                });
        });
    });
}
