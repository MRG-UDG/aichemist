define(['jquery', 'TYPO3/CMS/Backend/AjaxDataHandler', 'TYPO3/CMS/Backend/FormEngine'], function($, AjaxDataHandler, FormEngine) {
    'use strict';

    var Translator = {
        initialize: function() {
            $(document).on('click', '.t3js-translate-button', function(e) {
                e.preventDefault();
                var fieldId = $(this).data('field-id');
                Translator.translate(fieldId, 'en-US'); // Ändern Sie 'DE' zur gewünschten Zielsprache
            });
        },
        formatFieldId: function(fieldId) {
            // Teile die ID in ihre Komponenten
            var parts = fieldId.split('_');
            if (parts.length < 4) return fieldId; // Wenn das Format nicht passt, gib die originale ID zurück

            var table = parts.slice(1, -2).join('_'); // Tabelle kann '_' enthalten
            var uid = parts[parts.length - 2];
            var field = parts[parts.length - 1];

            return 'data_' + table + '__' + uid + '__' + field + '_';
        },
        getEditorContent: function(fieldId) {
            var formattedFieldId = this.formatFieldId(fieldId);
            var content = '';
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[formattedFieldId]) {
                content = CKEDITOR.instances[formattedFieldId].getData();
            } else if (typeof tinyMCE !== 'undefined' && tinyMCE.get(formattedFieldId)) {
                content = tinyMCE.get(formattedFieldId).getContent();
            } else {
                content = $('#' + formattedFieldId).val();
            }
            return content;
        },
        setEditorContent: function(fieldId, content) {
            var formattedFieldId = this.formatFieldId(fieldId);
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[formattedFieldId]) {
                CKEDITOR.instances[formattedFieldId].setData(content);
            } else if (typeof tinyMCE !== 'undefined' && tinyMCE.get(formattedFieldId)) {
                tinyMCE.get(formattedFieldId).setContent(content);
            } else {
                $('#' + formattedFieldId).val(content);
            }
            FormEngine.Validation.markFieldAsChanged($('#' + formattedFieldId));
        },
        translate: function(fieldId, targetLang) {
            var text = this.getEditorContent(fieldId);

            if (!text) {
                return;
            }

            $.ajax({
                url: TYPO3.settings.ajaxUrls['translate_text'],
                method: 'POST',
                data: {
                    text: text,
                    targetLang: targetLang
                },
                success: function(response) {
                    if (response.error) {
                        console.error('Translation error:', response.error);
                    } else {
                        Translator.setEditorContent(fieldId, response.translatedText);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                }
            });
        }
    };

    Translator.initialize();
    return Translator;
});
