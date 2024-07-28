<?php

defined('TYPO3_MODE') or die();

// Behalten Sie die ursprüngliche RTE-Konfiguration bei
$GLOBALS['TCA']['tt_content']['columns']['bodytext']['config']['type'] = 'text';
$GLOBALS['TCA']['tt_content']['columns']['bodytext']['config']['enableRichtext'] = true;

// Fügen Sie ein benutzerdefiniertes Feld für den Übersetzungsbutton hinzu
$GLOBALS['TCA']['tt_content']['columns']['bodytext']['config']['fieldControl']['translateButton'] = [
    'renderType' => 'translateButton',
    'options' => [
        'title' => 'Text übersetzen'
    ]
];
