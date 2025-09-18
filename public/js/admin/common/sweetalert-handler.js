/**
 * [GERİYE UYUMLU] Form gönderimi için SweetAlert onay diyalogu gösterir.
 * Bu fonksiyon, mevcut 'blogs' modülünüz gibi form tabanlı silme işlemleri için kullanılır.
 * @param {string} formSelector - Dinlenecek formun CSS seçicisi (örn: '.deleteForm').
 */
export function initSweetAlert(formSelector) {
    if (typeof Swal === 'undefined') return;

    document.querySelectorAll(formSelector).forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu kayıt kalıcı olarak silinecek!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
}

/**
 * [YENİ VE ESNEK] Genel tıklama olayları için bir onay diyalogu gösterir.
 * Onay verilirse, belirtilen 'callback' fonksiyonunu çalıştırır.
 * Bu, form dışındaki butonlar (örn: toplu silme) için idealdir.
 * @param {string} buttonSelector - Tıklanacak butonun CSS seçicisi (örn: '#bulkDeleteBtn').
 * @param {function} onConfirmCallback - Kullanıcı "Evet" derse çalıştırılacak fonksiyon.
 */
export function initConfirmation(buttonSelector, onConfirmCallback) {
    if (typeof Swal === 'undefined') return;

    document.querySelectorAll(buttonSelector).forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu işlem geri alınamaz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, devam et!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirmCallback(this); // 'this' tıklanan butonu temsil eder
                }
            });
        });
    });
}

