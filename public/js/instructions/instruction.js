$(document).ready(function () {
    
    /**
     * UTILS
     */
    function decodeHtml(html) {
        var txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }

    // Perbaikan: Merujuk ke window.editorInstances sesuai editor.js
    function setEditorData(content, retryCount = 0) {
        const editor = (window.editorInstances && window.editorInstances['instruction']) 
                       ? window.editorInstances['instruction'] 
                       : null;
        
        if (editor) {
            editor.setData(content);
        } else if (retryCount < 10) {
            setTimeout(() => setEditorData(content, retryCount + 1), 200);
        } else {
            console.error("Gagal menemukan editor 'instruction' di window.editorInstances.");
            $('#field-content').val(content);
        }
    }

    /**
     * FUNGSI PREVIEW (SWEETALERT2)
     */
    $(document).on('click', '.btn-preview-trigger', function() {
        const title = $(this).data('title');
        const rawContent = $(this).data('content');
        const decodedContent = decodeHtml(rawContent);

        Swal.fire({
            title: `<h5 class="mb-0">${title}</h5>`,
            html: `
                <div class="text-left p-3 border rounded bg-white shadow-sm" 
                     style="max-height: 450px; overflow-y: auto; font-family: 'Georgia', serif; line-height: 1.6;">
                    ${decodedContent}
                </div>
            `,
            width: '700px',
            showCloseButton: true,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#858796'
        });
    });

    /**
     * FUNGSI EDIT
     */
    $(document).on('click', '.btn-edit-trigger', function() {
        const id = $(this).data('id');
        const section = $(this).data('section');
        const part = $(this).data('part');
        const rawContent = $(this).data('content');
        const decodedContent = decodeHtml(rawContent);

        // UI Toggles (Gunakan slideDown agar konsisten dengan editor.js)
        $('#fake-input').addClass('d-none');
        $('#main-form').hide().removeClass('d-none').slideDown(300);
        $('#form-title').text('Edit Instruksi');

        $('#field-id').val(id);
        $('#field-section').val(section).trigger('change');
        $('#field-part').val(part === '-' || !part ? '' : part).trigger('change');

        // Isi CKEditor
        setEditorData(decodedContent);

        $('html, body').animate({
            scrollTop: $("#form-title").offset().top - 20
        }, 500);
    });

    /**
     * RESET FORM
     */
    window.resetForm = function() {
        $("#main-form").slideUp(200, function () {
            $(this).addClass("d-none");
            $("#fake-input").removeClass("d-none");
            $("#main-form")[0].reset();
            $("#field-id").val("");
            // Perbaikan reset editor
            if (window.editorInstances && window.editorInstances['instruction']) {
                window.editorInstances['instruction'].setData('');
            }
            $('#form-title').text('Tambah Instruksi Baru');
        });
    };

    $(document).on('click', '#fake-input', function() {
        $(this).addClass('d-none');
        $('#main-form').hide().removeClass('d-none').slideDown(300);
    });
});