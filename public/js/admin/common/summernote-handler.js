// LFM'den seçilen dosyayı editöre eklemek için global bir fonksiyon
window.lfm = function(options, cb) {
    let route_prefix = options.prefix || '/admin/laravel-filemanager';
    window.open(route_prefix + '?type=' + (options.type || 'file'), 'FileManager', 'width=900,height=600');
    window.SetUrl = cb;
};

// Summernote'u başlatan ana fonksiyon
export function initSummernote(selector) {
    const editor = $(selector);
    if (editor.length === 0) {
        console.error("Summernote başlatılamadı: " + selector + " seçicisiyle element bulunamadı.");
        return;
    }

    editor.summernote({
        lang: 'tr-TR',
        height: 350,
        focus: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'lfm', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        buttons: {
            lfm: function(context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="note-icon-picture"></i> Dosya Yöneticisi',
                    tooltip: 'Dosya Yöneticisi',
                    click: function() {
                        lfm({ type: 'image', prefix: '/admin/laravel-filemanager' }, function(lfmItems, path) {
                             lfmItems.forEach(function (lfmItem) {
                                context.invoke('insertImage', lfmItem.url);
                            });
                        });
                    }
                });
                return button.render();
            }
        }
    });
}