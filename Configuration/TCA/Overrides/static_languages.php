<?php
defined('TYPO3_MODE') or die();

$additionalFields = [
    'lg_name_en' => 'lg_name_cz'
];

$LL = 'LLL:EXT:static_info_tables_cz/Resources/Private/Language/locallang_db.xlf:static_languages_item.';

foreach ($additionalFields as $sourceField => $destField) {
    $additionalColumns = [];
    $additionalColumns[$destField] = $GLOBALS['TCA']['static_languages']['columns'][$sourceField];
    $additionalColumns[$destField]['label'] = $LL . $destField;
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('static_languages', $additionalColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'static_languages',
        $destField,
        '',
        'after:' . $sourceField
    );
    // Add as search field
    $GLOBALS['TCA']['static_languages']['ctrl']['searchFields'] .= ',' . $destField;
}
unset($additionalColumns);
unset($additionalFields);
