/**
 * Bir tabloyu sürükle-bırak ile sıralanabilir hale getirir.
 * @param {string} tableId - Sıralanacak tablonun ID'si (örn: 'blogsTable').
 * @param {string} modelName - Güncellenecek modelin adı (örn: 'blogs').
 * @param {string} csrfToken - Laravel'in CSRF token'ı.
 */
export function initSortable(tableId, modelName, csrfToken) {
    const table = document.getElementById(tableId);
    if (!table) return;

    if (typeof Sortable === 'undefined') {
        console.error('SortableJS kütüphanesi bulunamadı.');
        return;
    }

    Sortable.create(table, {
        handle: '.handle-cell',
        animation: 150,
        onEnd: function(evt) {
            let order = [];
            document.querySelectorAll(`#${tableId} tr`).forEach((row, index) => {
                order.push({ id: row.getAttribute('data-id'), position: index + 1 });
            });

            // AJAX isteği için URL'yi model adıyla birlikte doğru şekilde oluştur
            fetch(`/admin/${modelName}/update-order`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order: order
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        iziToast.success({
                            title: 'Başarılı',
                            message: data.message,
                            position: 'topRight',
                            timeout: 2000
                        });
                    } else {
                        iziToast.error({
                            title: 'Hata',
                            message: data.message || 'Sıralama güncellenemedi.',
                            position: 'topRight',
                            timeout: 3000
                        });
                    }
                })
                .catch(error => {
                    console.error('Sıralama gönderme hatası:', error);
                    iziToast.error({
                        title: 'Hata',
                        message: 'Sıralama güncellenemedi.',
                        position: 'topRight',
                        timeout: 3000
                    });
                });
        }
    });
}
