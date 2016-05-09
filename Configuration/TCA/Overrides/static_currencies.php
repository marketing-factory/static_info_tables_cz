<?php
defined('TYPO3_MODE') or die();

$additionalFields = [
    'cu_name_en' => 'cu_name_cz',
    'cu_sub_name_en' => 'cu_sub_name_cz'
];

$LL = 'LLL:EXT:static_info_tables_cz/Resources/Private/Language/locallang_db.xlf:static_currencies_item.';

foreach ($additionalFields as $sourceField => $destField) {
    $additionalColumns = [];
    $additionalColumns[$destField] = $GLOBALS['TCA']['static_currencies']['columns'][$sourceField];
    $additionalColumns[$destField]['label'] = $LL . $destField;
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('static_currencies', $additionalColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'static_currencies',
        $destField,
        '',
        'after:' . $sourceField
    );
    // Add as search field
    $GLOBALS['TCA']['static_currencies']['ctrl']['searchFields'] .= ',' . $destField;
}
unset($additionalColumns);
unset($additionalFields);
