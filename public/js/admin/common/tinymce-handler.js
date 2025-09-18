/**
 * Verilen CSS seçicileri için TinyMCE editörünü başlatır.
 * @param {string} selector - TinyMCE'nin uygulanacağı HTML elementlerinin seçicisi (örn: '#content, .editable-area').
 */
export function initTinyMCE(selector) {
    if (typeof tinymce === 'undefined') {
        console.error('TinyMCE kütüphanesi bulunamadı.');
        return;
    }

    tinymce.init({
        selector: selector,

        // --- HATA AYIKLAMA İÇİN GEÇİCİ DEĞİŞİKLİK ---
        // Eklentilerin çoğunu devre dışı bırakarak sorunun bir eklentiden
        // kaynaklanıp kaynaklanmadığını test ediyoruz.
        plugins: 'lists link image code', // Sadece temel eklentiler aktif
        // Orijinal eklenti listeniz:
        // plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table directionality emoticons template paste textpattern',

        toolbar: 'undo redo | formatselect | bold italic | numlist bullist | link image | code',
        // Orijinal araç çubuğunuz:
        // toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link image media | code preview',

        autosave_ask_before_unload: false,
        menubar: false,
        branding: false,
        forced_root_block: 'div',

        protect: [
            /<!--[\s\S]*?-->/g,
            /<\/?[A-Za-z]+\s+[^>]*?data-mce-bogus="all"[\s\S]*?>/g
        ],

        setup: function(editor) {
            editor.on('BeforeUnload', function(e) {
                return; // Bu ayar kalmalı, zararı yok.
            });
        }
    });
}


