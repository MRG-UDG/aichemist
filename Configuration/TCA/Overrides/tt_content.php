<?php

defined('TYPO3_MODE') or die();

// Fügen Sie ein benutzerdefiniertes Feld für den Übersetzungsbutton hinzu
$translateButton = [
    'renderType' => 'translateButton',
    'options' => [
        'title' => 'LLL:EXT:aichemist/Resources/Private/Language/locallang.xlf:translate_button.title'
    ]
];
$GLOBALS['TCA']['tt_content']['columns']['bodytext']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['tt_content']['columns']['header']['config']['fieldControl']['translateButton'] = $translateButton;
