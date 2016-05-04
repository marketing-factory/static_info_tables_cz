<?php
defined('TYPO3_MODE') or die();

$additionalFields = [
    'cn_short_en' => 'cn_short_cz',
    'cn_official_name_en' => 'cn_official_name_cz',
    'cn_capital' => 'cn_capital_cz',
];

$LL = 'LLL:EXT:static_info_tables_cz/Resources/Private/Language/locallang_db.xlf:static_countries_item.';

foreach ($additionalFields as $sourceField => $destField) {
    $additionalColumns = [];
    $additionalColumns[$destField] = $GLOBALS['TCA']['static_countries']['columns'][$sourceField];
    $additionalColumns[$destField]['label'] = $LL . $destField;
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('static_countries', $additionalColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'static_countries',
        $destField,
        '',
        'after:' . $sourceField
    );
    // Add as search field
    $GLOBALS['TCA']['static_countries']['ctrl']['searchFields'] .= ',' . $destField;
}
unset($additionalColumns);
unset($additionalFields);
