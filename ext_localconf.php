<?php

defined('TYPO3') or die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Taketool\Faq\Controller\FaqController;

ExtensionUtility::configurePlugin(
    'Faq',
    'FaqList',
    [
        FaqController::class => 'list',
    ],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
