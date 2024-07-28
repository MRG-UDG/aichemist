<?php
namespace MRG\Aichemist\Form\FieldControl;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\PageRenderer;

class TranslateButtonControl extends AbstractNode
{
    public function render()
    {
        $parameterArray = $this->data['parameterArray'];
        $fieldId = $parameterArray['itemFormElID'];

        // Überprüfen Sie, ob fieldControlOptions gesetzt ist, und setzen Sie einen Fallback
        $options = $parameterArray['fieldControlOptions'] ?? ['title' => 'Übersetzen'];

        // Fügen Sie den Titel hinzu
        $title = $options['title'] ?? 'Übersetzen';

        // Fügen Sie Ihr JavaScript hinzu
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Aichemist/Translator');

        return [
            'iconIdentifier' => 'actions-translate',
            'title' => $title,
            'linkAttributes' => [
                'class' => 't3js-translate-button',
                'data-field-id' => $fieldId
            ],
            'requireJsModules' => [
                'TYPO3/CMS/Aichemist/Translator'
            ]
        ];
    }
}
