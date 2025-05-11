<?php
namespace MRG\Aichemist\Form\FieldControl;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TranslateButtonControl extends AbstractNode
{
    public function render(): array
    {
        if (isset($this->data['command']) && $this->data['command'] === 'new') {
            return []; // Neue Seiten können noch nicht übersetzt werden
        }

        $tableName = $this->data['tableName'];
        switch ($tableName) {
            case 'pages':
                $sysLanguageUid = $this->data['databaseRow']['sys_language_uid'] ?? 0;
                $pageUid = ($sysLanguageUid > 0)
                    ? ($this->data['databaseRow']['l10n_source'] ?? 0)
                    : ($this->data['databaseRow']['uid'] ?? 0);
                break;
            case 'sys_file_metadata':
                $sysLanguageUid = $this->data['databaseRow']['sys_language_uid'] ?? 0;
                $pageUid = 1; // Default-Wert, TODO: Site-UID aus Sprache ermitteln
                break;
            default:
                $pageUid = $this->data['databaseRow']['pid'] ?? 0;
        }

        if ($pageUid < 0 || !isset($this->data['databaseRow']['sys_language_uid'])) {
            return []; // Inhalte ohne Übersetzungsfähigkeit können nicht übersetzt werden
        }

        $contentLanguageId = $this->data['databaseRow']['sys_language_uid'] ?? 0;
        $parameterArray = $this->data['parameterArray'];
        $fieldName = $parameterArray['itemFormElName'];

        $options = $parameterArray['fieldControlOptions'] ?? ['title' => 'Übersetzen'];
        $title = $options['title'] ?? 'Übersetzen';

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId($pageUid);
        $languages = $site->getLanguages();
        $targetLang = 'EN-US';
        foreach ($languages as $language) {
            if ((int)$language->getLanguageId() === (int)$contentLanguageId) {
                $targetIso = $language->getLocale()->getLanguageCode();
                $targetLang = ($targetIso === 'en' || $targetIso === 'pt')
                    ? $language->getLocale()
                    : $targetIso;
                break;
            }
        }

        $targetLang = str_replace('_', '-', strtoupper($targetLang));
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
