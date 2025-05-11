import $ from 'jquery';
import AjaxDataHandler from '@typo3/backend/ajax-data-handler.js';
import FormEngine from '@typo3/backend/form-engine.js';

class Ttranslator {
    constructor() {
        this.initialize();
    }

    initialize() {
        $(document).on('click', '.t3js-translate-button', (e) => {
            e.preventDefault();
            const fieldName = $(e.currentTarget).data('field-name');
            const targetLang = $(e.currentTarget).data('targetlang');
            const fieldId = this.getFieldId(fieldName);
            this.translate(fieldId, fieldName, targetLang);
        });
    }

    getFieldId(fieldName) {
        let inputEl = $(`[data-formengine-input-name="${fieldName}"]`);
        if (inputEl.length === 0) {
            inputEl = $(`[name="${fieldName}"]`);
        }
        return inputEl.attr('id');
    }

    getEditorContent(fieldId, fieldName) {
        return $(`#${fieldId}`).val();
    }

    setEditorContent(fieldId, fieldName, content) {
        $(`#${fieldId}`).val(content);
        $(`[name="${fieldName}"]`).val(content);
        FormEngine.Validation.markFieldAsChanged($(`#${fieldId}`));
    }

    translate(fieldId, fieldName, targetLang) {
        const text = this.getEditorContent(fieldId, fieldName);

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
            success: (response) => {
                if (response.error) {
                    console.error('Translation error:', response.error);
                } else {
                    this.setEditorContent(fieldId, fieldName, response.translatedText);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.error('AJAX error:', textStatus, errorThrown);
            }
        });
    }
}

export default new Ttranslator();
