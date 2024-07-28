<?php

defined('TYPO3_MODE') or die();

// Fügen Sie ein benutzerdefiniertes Feld für den Übersetzungsbutton hinzu
$translateButton = [
    'renderType' => 'translateButton',
    'options' => [
        'title' => 'LLL:EXT:aichemist/Resources/Private/Language/locallang.xlf:translate_button.title'
    ]
];
$GLOBALS['TCA']['pages']['columns']['title']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['nav_title']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['subtitle']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['seo_title']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['description']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['og_title']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['og_description']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['twitter_title']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['twitter_description']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['abstract']['config']['fieldControl']['translateButton'] = $translateButton;
$GLOBALS['TCA']['pages']['columns']['keywords']['config']['fieldControl']['translateButton'] = $translateButton;
