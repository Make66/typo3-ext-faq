<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$pluginKey = ExtensionUtility::registerPlugin(
    'Faq',
    'FaqList',
    'FAQ List',
    'EXT:faq/Resources/Public/Icons/Extension.svg',
    'taketool',
    'LLL:EXT:faq/Resources/Private/Language/locallang.xlf:plugin.faqlist.description',
);

/*$GLOBALS['TCA']['tt_content']['types']['faq_faqlist']['showitem'] = '
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
        --palette--;;general,
        header;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_formlabel,
        --linebreak--,
        pi_flexform,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
        --palette--;;language,
    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
        --palette--;;hidden,
        --palette--;;access,
';*/


ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $pluginKey,
    'after:subheader'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:faq/Configuration/FlexForms/FaqList.xml',
    'faq_faqlist'
);
