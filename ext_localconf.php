<?php
defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

call_user_func(function ($extKey) {

    // Register the icon
    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'ext-aichemist-icon',
        SvgIconProvider::class,
        ['source' => 'EXT:aichemist/Resources/Public/Icons/Extension.svg']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1628770526] = [
        'nodeName' => 'translateButton',
        'priority' => 40,
        'class' => \MRG\Aichemist\Form\FieldControl\TranslateButtonControl::class
    ];

    ExtensionUtility::configurePlugin(
        'Aichemist',
        'TranslationPlugin',
        [
            \MRG\Aichemist\Controller\TranslationController::class => 'translateAction'
        ],
        [
            \MRG\Aichemist\Controller\TranslationController::class => 'translateAction'
        ],
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
    );

    // Registrieren Sie die AJAX-Route
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['ajaxRoutes']['translate_text'] = [
        'target' => \MRG\Aichemist\Controller\TranslationController::class . '::translateAction'
    ];

}, 'aichemist');
