<?php
defined('TYPO3') || die();

$pageRenderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
$pageRenderer->loadJavaScriptModule('@mrg/aichemist/Translator.js');
