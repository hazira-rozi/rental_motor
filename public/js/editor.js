/**
 * ==========================================================
 * FINAL CKEDITOR 5 - CLEAN VERSION (No Collaboration Warnings)
 * ==========================================================
 */

window.editorInstances = {};

$(document).ready(function () {
    $(document).on("click", "#fake-input", function () {
        $(this).addClass("d-none");
        $("#main-form").hide().removeClass("d-none").slideDown(300);
        if (window.editorInstances['question']) {
            setTimeout(() => window.editorInstances['question'].editing.view.focus(), 400);
        }
    });

    initGlobalEditors();
    toggleSectionFields();
});

function initGlobalEditors() {
    const editors = document.querySelectorAll('.ck-editor-5');
    
    editors.forEach((el) => {
        const name = el.getAttribute('data-name') || el.getAttribute('name');
        if (typeof CKEDITOR === 'undefined') return;

        CKEDITOR.ClassicEditor.create(el, {
            toolbar: ["heading", "|", "bold", "italic", "underline", "|", "bulletedList", "numberedList", "undo", "redo"],
            
            // Menghapus SEMUA fitur kolaborasi untuk menghilangkan warning di konsol
            removePlugins: [
                // Collaboration & Cloud
                'AIAssistant', 'WritingAssistant', 'OpenAIAssistant', 'Comments', 
                'TrackChanges', 'RevisionHistory', 'RealTimeCollaboration', 
                'RealTimeCollaborativeComments', 'RealTimeCollaborativeRevisionHistory',
                'RealTimeCollaborativeTrackChanges', 'PresenceList', 'CommentsRepository',
                'WideSidebar', 'CloudServices', 'CKBox', 'CKFinder', 'EasyImage','TrackChangesEditing',
                'TrackChangesData',
                
                // Technical/Infrastructure
                'WebSocketGateway', 'Sessions', 'Token',
                
                // Premium Features
                'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 
                'Template', 'DocumentOutline', 'FormatPainter', 'TableOfContents',
                'PasteFromOfficeEnhanced', 'CaseChange', 'ExportPdf', 'ExportWord'
            ],
            licenseKey: '', 
        })
        .then((newEditor) => {
            window.editorInstances[name] = newEditor;
            newEditor.editing.view.change((writer) => {
                writer.setStyle("min-height", "250px", newEditor.editing.view.document.getRoot());
            });
        })
        .catch((error) => console.error(`Error Editor [${name}]:`, error));
    });
}

/**
 * SMART TOGGLE & EXCLUDE FIELDS
 */
window.toggleSectionFields = function() {
    const section = $('#field-section').val();
    const readingBox = $('#reading-fields');
    const listeningBox = $('#listening-fields');

    // Matikan semua input yang tidak relevan (Exclude)
    readingBox.addClass('d-none').find('input, textarea').attr('disabled', true);
    listeningBox.addClass('d-none').find('input').attr('disabled', true);

    if (section === 'reading') {
        readingBox.removeClass('d-none').find('input, textarea').attr('disabled', false);
    } 
    else if (section === 'listening') {
        listeningBox.removeClass('d-none').find('input').attr('disabled', false);
    }
    
    // Logic Type Soal
    if (section === 'structure') {
        $('#field-type option[value="error"]').prop('disabled', false).show();
    } else {
        $('#field-type').val('mc');
        $('#field-type option[value="error"]').prop('disabled', true).hide();
    }
};

/**
 * EDIT DATA MAPPER
 */
window.editQuestion = function(data) {
    $("#fake-input").addClass("d-none");
    $("#main-form").removeClass("d-none").show();
    $("#form-title").text(`Edit Soal ${data.section.toUpperCase()} - No. ${data.number}`);

    $("#field-id").val(data.id);
    $("#field-section").val(data.section);
    $("#field-number").val(data.number);
    $("#field-part").val(data.part || '');
    $("#field-type").val(data.type || 'mc');
    $("#field-answer").val(data.answer_key);
    $("#field-passage-group").val(data.passage_group || '');
    $("#field-cue-start").val(data.cue_start || '');
    $("#field-cue-end").val(data.cue_end || '');

    if (window.editorInstances['passage']) window.editorInstances['passage'].setData(data.passage_html || '');
    if (window.editorInstances['question']) window.editorInstances['question'].setData(data.content_html || '');

    if (data.options) {
        let opts = typeof data.options === 'string' ? JSON.parse(data.options) : data.options;
        Object.keys(opts).forEach(key => { $(`#opt-${key}`).val(opts[key]); });
    }

    toggleSectionFields();
    window.scrollTo({ top: 0, behavior: "smooth" });
};

/**
 * RESET FORM
 */
window.resetForm = function() {
    $("#main-form").slideUp(200, function () {
        $(this).addClass("d-none");
        $("#fake-input").removeClass("d-none");
        $("#main-form")[0].reset();
        $("#field-id").val("");
        Object.values(window.editorInstances).forEach(editor => editor.setData(''));
        toggleSectionFields();
    });
};