/**
 * Bir düzenleme modalını, onu tetikleyen butonun data-* özelliklerine göre doldurur.
 * Bu handler, hangi verinin hangi elemente gideceğini butondaki 'data-target-*'
 * özelliklerinden okur, bu sayede tamamen generic hale gelir.
 *
 * @param {string} triggerSelector - Düzenleme modallarını tetikleyecek butonların CSS seçicisi.
 */
export function initEditModal(triggerSelector) {
    document.querySelectorAll(triggerSelector).forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const modalSelector = this.dataset.bsTarget;
            const modalElement = document.querySelector(modalSelector);
            if (!modalElement) return;

            const modalForm = modalElement.querySelector('form');

            // Formun 'action' URL'sini 'data-update-url' özelliğinden al
            if (this.dataset.updateUrl) {
                modalForm.action = this.dataset.updateUrl;
            }

            // Butonun tüm data-* özelliklerini gez
            for (const key in this.dataset) {
                // Sadece 'data-value-' ile başlayan özellikleri işle (input/select/textarea için)
                if (key.startsWith('value')) {
                    // key: 'valueTitle', 'valueDescription'
                    // targetSelector: '#edit_title', '#edit_description'
                    const targetSelector = this.dataset[key];
                    const targetElement = modalForm.querySelector(targetSelector);
                    // dataKey: 'title', 'description'
                    const dataKey = key.substring(5).charAt(0).toLowerCase() + key.substring(6);

                    if (targetElement) {
                        const tagName = targetElement.tagName.toLowerCase();
                        const value = this.dataset[dataKey];

                        if (tagName === 'textarea' && tinymce.get(targetElement.id)) {
                            tinymce.get(targetElement.id).setContent(value || '');
                        } else {
                            targetElement.value = value || '';
                        }
                    }
                }
                // Sadece 'data-src-' ile başlayan özellikleri işle (resimler için)
                else if (key.startsWith('src')) {
                    // key: 'srcImageurl'
                    const targetSelector = this.dataset[key];
                    const targetElement = modalForm.querySelector(targetSelector);
                    // dataKey: 'imageUrl'
                    const dataKey = key.substring(3).charAt(0).toLowerCase() + key.substring(4);

                    if (targetElement) {
                        const value = this.dataset[dataKey];
                        targetElement.src = value || 'https://placehold.co/150x150/EFEFEF/AAAAAA&text=Görsel+Yok';
                    }
                }
            }
        });
    });
}

