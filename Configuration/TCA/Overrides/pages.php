<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\TypoScript\Tokenizer\LosslessTokenizer;
use TYPO3\CMS\Core\TypoScript\AST\AstBuilder;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use Psr\EventDispatcher\EventDispatcherInterface;

// Pfad zur TypoScript-Datei
$typoScriptPath = ExtensionManagementUtility::extPath('aichemist') . 'Configuration/TypoScript/setup.typoscript';
$typoScriptContent = file_get_contents($typoScriptPath);

// 1. Tokenizer initialisieren
$tokenizer = GeneralUtility::makeInstance(LosslessTokenizer::class);
$lineStream = $tokenizer->tokenize($typoScriptContent);

// 2. EventDispatcher holen
$eventDispatcher = GeneralUtility::makeInstance(EventDispatcherInterface::class);

// 3. AST Builder mit EventDispatcher erstellen
$astBuilder = GeneralUtility::makeInstance(AstBuilder::class, $eventDispatcher);

// 4. RootNode erstellen und AST parsen
$rootNode = GeneralUtility::makeInstance(RootNode::class);
$ast = $astBuilder->build(
    $lineStream,
    $rootNode,
    []
);

// 5. AST in ein flaches Array konvertieren
$typoScriptArray = $ast->flatten();

// 6. TypoScriptService für die endgültige Verarbeitung nutzen
$typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
$flattenedTypoScript = $typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptArray);

// Debugging: TypoScript-Daten prüfen
$flattenedTypoScript = convertFlattenedTypoScriptToArray($flattenedTypoScript);

$mySetting = $flattenedTypoScript['plugin']['tx_aichemist']['settings']['translateButtonTables'] ?? null;

$translateButton = [
    'renderType' => 'translateButton',
    'label' => 'Translate',
    'options' => [
        'title' => 'LLL:EXT:aichemist/Resources/Private/Language/locallang.xlf:translate_button.title',
        'icon' => 'actions-localize',
        'action' => 'translate',
        'langField' => 'sys_language_uid',
        'pid' => '###PAGE_TSCONFIG_ID###'
    ]
];

foreach ($mySetting ?? [] as $table => $fields) {
    if (isset($GLOBALS['TCA'][$table])) {
        $fields = GeneralUtility::trimExplode(',', $fields, true);
        foreach ($fields as $field) {
            $GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldControl']['translateButton'] = $translateButton;

            // ✅ Anpassung für TYPO3 v13:
            $GLOBALS['TCA'][$table]['columns'][$field]['config']['fieldWizard']['translate'] = [
                'renderType' => 'translateWizard'
            ];
        }
    }
}

function convertFlattenedTypoScriptToArray(array $flattenedArray): array {
    $result = [];

    foreach ($flattenedArray as $key => $value) {
        // Schlüssel zerlegen
        $keys = explode('.', $key);
        $current = &$result;

        // Durch die Schlüssel navigieren und das Array aufbauen
        foreach ($keys as $subKey) {
            if (!isset($current[$subKey])) {
                $current[$subKey] = [];
            }
            $current = &$current[$subKey];
        }

        // Wert setzen
        $current = $value;
    }

    return $result;
}
