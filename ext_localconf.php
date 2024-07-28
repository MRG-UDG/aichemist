<?php
defined('TYPO3_MODE') || die();

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Imaging\IconRegistry::class
);
$iconRegistry->registerIcon(
    'ext-aichemist-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:aichemist/Resources/Public/Icons/Extension.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1628770526] = [
    'nodeName' => 'translateButton',
    'priority' => 40,
    'class' => \MRG\Aichemist\Form\FieldControl\TranslateButtonControl::class
];

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Aichemist',
    'TranslationPlugin',
    [
        \MRG\Aichemist\Controller\TranslationController::class => 'translateAction'
    ],
    [
        \MRG\Aichemist\Controller\TranslationController::class => 'translateAction'
    ]
);

// Registrieren Sie die AJAX-Route
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['ajaxRoutes']['translate_text'] = [
    'target' => \MRG\Aichemist\Controller\TranslationController::class . '::translateAction'
];
