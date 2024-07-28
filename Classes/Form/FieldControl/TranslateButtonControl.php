<?php
namespace MRG\Aichemist\Form\FieldControl;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TranslateButtonControl extends AbstractNode
{
    public function render()
    {
        if (isset($this->data['command']) && $this->data['command'] === 'new') {
            // neue Seiten können noch nicht übersetzt werden, weil sonst Daten für die API fehlen.
            return [];
        }
        $tableName = $this->data['tableName'];
        switch ($tableName) {
            case 'pages':
                $sysLanguageUid = $this->data['databaseRow']['sys_language_uid'];
                if ($sysLanguageUid > 0) {
                    $pageUid = $this->data['databaseRow']['l10n_source'];
                } else {
                    $pageUid = $this->data['databaseRow']['uid'];
                }
                break;
            default:
                $pageUid = $this->data['databaseRow']['pid'];
        }
        if ($pageUid < 0) {
            // neuer Inhalt kann noch nicht übersetzt werden, weil sonst Daten für die API fehlen.
            return [];
        }
        $contentLanguageId = $this->data['databaseRow']['sys_language_uid'];
        $parameterArray = $this->data['parameterArray'];
        $fieldName = $parameterArray['itemFormElName'];

        // Überprüfen Sie, ob fieldControlOptions gesetzt ist, und setzen Sie einen Fallback
        $options = $parameterArray['fieldControlOptions'] ?? ['title' => 'Übersetzen'];

        // Fügen Sie den Titel hinzu
        $title = $options['title'] ?? 'Übersetzen';

        // Fügen Sie Ihr JavaScript hinzu
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadRequireJsModule('TYPO3/CMS/Aichemist/Translator');

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageUid);
        $languages = $site->getLanguages();
        $targetLang = 'en_US'; // default
        foreach ($languages as $language) {
            if ($language->getLanguageId() === $contentLanguageId) {
                // iso-639-1
                $targetIso = $language->getTwoLetterIsoCode();
                if ($targetIso == 'en' || $targetIso == 'pt') {
                    // Ausnahmen bei gewissen Sprachen, wo die "locale" Einstellung wichtig ist.
                    $targetLang = $language->getLocale();
                } else {
                    $targetLang = $targetIso;
                }
                break;
            }
        }
        $targetLang = str_replace('_', '-', strtoupper($targetLang));

        // Optional: Locale in den Titel einfügen
        $title .= ' (' . $targetLang . ')';

        return [
            'iconIdentifier' => 'actions-translate',
            'title' => $title,
            'linkAttributes' => [
                'class' => 't3js-translate-button',
                'data-field-name' => $fieldName,
                'data-targetLang' => $targetLang,
            ]
        ];
    }
}
