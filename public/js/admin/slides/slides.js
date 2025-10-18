// public/js/admin/slides/slides.js
// Bu dosya, Slaytlar sayfasının ana JavaScript mantığını yönetir.

import { initConfirmation } from '../common/sweetalert-handler.js';
import { initStatusSwitch } from '../common/status-handler.js';
import { initSortable } from '../common/sortable-handler.js';
import { initAjaxFormHandlers } from '../common/forms-handler.js';
import { initCheckboxManagement } from '../common/bulk-action-handler.js';
// TinyMCE artık gerekli değil, çünkü Slide Modülü'nde büyük metin alanı (description) yok.

document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modelName = 'slides'; // <-- Model adını 'slides' olarak DEĞİŞTİRDİK.

    // 1. AJAX Formları için ayarları tanımla
    const formOptions = {
        createFormId: '#createSlideForm', // View'deki ID'nizle eşleşmeli
        editFormId: '#editSlideForm',     // View'deki ID'nizle eşleşmeli
        csrfToken: csrfToken
    };
    // Slide Modülü'nde TinyMCE kullanılmadığı için createEditorId ve editEditorId kaldırıldı.
    initAjaxFormHandlers(formOptions);

    // 2. Diğer Ortak İşlevler
    // TinyMCE kaldırıldı.
    initSortable('slidesTable', modelName, csrfToken); // <-- ID'yi ve modelName'i güncelledik.
    initStatusSwitch('.status-switch', modelName, csrfToken);
    // Toplu seçim ve buton gösterimi/gizlemesi
    initCheckboxManagement('selectAllCheckbox', '.row-checkbox', 'bulkDeleteBtn');

    // 3. TOPLU SİLME ONAYI
    initConfirmation('#bulkDeleteBtn', (button) => {
        const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

        // Rota tanımınız: Route::post('{model}/bulk-delete', [CommonController::class, 'bulkDestroy'])->name('common.bulkDestroy');
        // Bu rotaya 'slides' modelini gönderiyoruz.
        const url = `/admin/${modelName}/bulk-delete`;

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ ids: selectedIds })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Silindi!', data.message, 'success').then(() => window.location.reload());
                } else {
                    Swal.fire('Hata!', data.message || 'Bir hata oluştu.', 'error');
                }
            });
    });

    // 4. DÜZENLEME MODALINI DOLDURMA (SLIDES'A ÖZEL)
    // Bu kısım, slayt alanlarınıza göre ayarlanmalıdır.
    document.querySelectorAll('.edit-slide-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = document.getElementById('editSlideModal'); // View'deki ID ile eşleşmeli

            // Verileri butonun data-* özelliklerinden al
            const updateUrl = this.dataset.updateUrl;
            const title = this.dataset.title;
            const subtitle = this.dataset.subtitle;
            const link = this.dataset.link;
            const buttonText = this.dataset.buttonText;
            const imageUrl = this.dataset.imageUrl;

            // Modal içindeki elementleri bul ve doldur
            modal.querySelector('form').action = updateUrl;
            modal.querySelector('#edit_title').value = title || '';
            modal.querySelector('#edit_subtitle').value = subtitle || '';
            modal.querySelector('#edit_link').value = link || '';
            modal.querySelector('#edit_button_text').value = buttonText || '';
            modal.querySelector('#current_image').src = imageUrl || 'https://placehold.co/150x150/EFEFEF/AAAAAA&text=Görsel+Yok';

            // Eğer Modal'da Status/Order varsa buradan da doldurulabilir. (Şu an ihtiyacımız yok)
        });
    });
});