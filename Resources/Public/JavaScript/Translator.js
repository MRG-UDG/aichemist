define('TYPO3/CMS/Aichemist/Translator', ['jquery', 'TYPO3/CMS/Backend/AjaxDataHandler', 'TYPO3/CMS/Backend/FormEngine'], function($, AjaxDataHandler, FormEngine) {
    'use strict';

    var Translator = {
        initialize: function() {
            $(document).on('click', '.t3js-translate-button', function(e) {
                e.preventDefault();
                var fieldName = $(this).data('field-name');
                var targetLang = $(this).data('targetlang');
                var fieldId = Translator.getFieldId(fieldName);

                Translator.translate(fieldId, fieldName, targetLang);
            });
        },
        getFieldId: function(fieldName) {
            // für Standard Felder
            var inputEl = $('[data-formengine-input-name="' + fieldName + '"]');
            if (inputEl.length === 0) {
                // für RTE Felder
                inputEl = $('[name="' + fieldName + '"]');
            }
            return inputEl.attr('id');
        },
        getEditorContent: function(fieldId, fieldName) {
            var content = '';
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[fieldId]) {
                content = CKEDITOR.instances[fieldId].getData();
            } else if (typeof tinyMCE !== 'undefined' && tinyMCE.get(fieldId)) {
                content = tinyMCE.get(fieldId).getContent();
            } else {
                content = $('#' + fieldId).val();
            }
            return content;
        },
        setEditorContent: function(fieldId, fieldName, content) {
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances[fieldId]) {
                CKEDITOR.instances[fieldId].setData(content);
            } else if (typeof tinyMCE !== 'undefined' && tinyMCE.get(fieldId)) {
                tinyMCE.get(fieldId).setContent(content);
            } else {
                // im sichtbaren und versteckten Feld anpassen
                $('#' + fieldId).val(content);
                $('[name="' + fieldName + '"]').val(content);
            }
            FormEngine.Validation.markFieldAsChanged($('#' + fieldId));
        },
        translate: function(fieldId, fieldName, targetLang) {
            var text = this.getEditorContent(fieldId, fieldName);

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
                        Translator.setEditorContent(fieldId, fieldName, response.translatedText);
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
