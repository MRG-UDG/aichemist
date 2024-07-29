<?php

defined('TYPO3_MODE') or die();

// benutzerdefiniertes Feld für den Übersetzungsbutton hinzufügen
$translateButton = [
    'renderType' => 'translateButton',
    'options' => [
        'title' => 'LLL:EXT:aichemist/Resources/Private/Language/locallang.xlf:translate_button.title'
    ]
];

// TypoScript-Konfiguration lesen
$configurationManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class
);
$typoScriptSetup = $configurationManager->getConfiguration(
    \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
);

$pluginSetup = $typoScriptSetup['plugin.']['tx_aichemist.'] ?? [];
$typoScriptService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\TypoScript\TypoScriptService::class
);
// Felder aus der TypoScript-Konfiguration holen
$pluginSettings = $typoScriptService->convertTypoScriptArrayToPlainArray($pluginSetup);

// Übersetzungsbutton zu jedem konfigurierten Feld in jeder konfigurierten Tabelle hinzufügen
// (auch jenseits der Tabelle "pages")
foreach ($pluginSettings['settings']['translateButtonTables'] as $table => $fields) {
    if (isset($GLOBALS['TCA'][$table])) {
        $fields = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(
            ',',
            $fields,
            true
        );
        foreach ($fields as $field) {
            $GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldControl']['translateButton'] = $translateButton;
        }
    }
}
