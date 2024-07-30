<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

// Pfad zur TypoScript Setup-Datei Ihrer Extension
$typoScriptPath = ExtensionManagementUtility::extPath('aichemist') . 'Configuration/TypoScript/setup.typoscript';

// TypoScript-Datei einlesen
$typoScriptContent = file_get_contents($typoScriptPath);

// TypoScriptParser instanziieren
$typoScriptParser = GeneralUtility::makeInstance(TypoScriptParser::class);
$typoScriptParser->parse($typoScriptContent);

// TypoScriptService instanziieren
$typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

// TypoScript-Array in ein flaches Array konvertieren
$flattenedTypoScript = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptParser->setup);

// Auf spezifische Einstellungen zugreifen
$mySetting = $flattenedTypoScript['plugin']['tx_aichemist']['settings']['translateButtonTables'] ?? null;

$translateButton = [
    'renderType' => 'translateButton',
    'options' => [
        'title' => 'LLL:EXT:aichemist/Resources/Private/Language/locallang.xlf:translate_button.title'
    ]
];

// Übersetzungsbutton zu jedem konfigurierten Feld in jeder konfigurierten Tabelle hinzufügen
foreach ($mySetting ?? [] as $table => $fields) {
    if (isset($GLOBALS['TCA'][$table])) {
        $fields = GeneralUtility::trimExplode(',', $fields, true);
        foreach ($fields as $field) {
            $GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldControl']['translateButton'] = $translateButton;
        }
    }
}
