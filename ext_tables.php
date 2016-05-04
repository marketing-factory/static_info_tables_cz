<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
$tablesAdditionalFields = [
    'static_countries' => [
        'cn_short_en' => 'cn_short_cz',
        'cn_official_name_en' => 'cn_official_name_cz',
        'cn_capital' => 'cn_capital_cz',
    ],
    'static_country_zones' => [
        'zn_name_en' => 'zn_name_cz',
    ],
    'static_currencies' => [
        'cu_name_en' => 'cu_name_cz',
        'cu_sub_name_en' => 'cu_sub_name_cz',
    ],
    'static_languages' => [
        'lg_name_en' => 'lg_name_cz',
    ],
    'static_territories' => [
        'tr_name_en' => 'tr_name_cz',
    ],
];

$extensionResourcesLanguagePath = 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xlf:';

foreach ($tablesAdditionalFields as $table => $additionalFields) {
    if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 6001000) {
        \TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA($table);
    }
    foreach ($additionalFields as $sourceField => $destField) {
        $additionalColumns = [];
        $additionalColumns[$destField] = $GLOBALS['TCA'][$table]['columns'][$sourceField];
        $additionalColumns[$destField]['label'] = $extensionResourcesLanguagePath . ':' . $table . '_item.' . $destField;
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $additionalColumns);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            $destField,
            '',
            'after:' . $sourceField
        );
    }
}
unset($additionalColumns);
unset($tablesAdditionalFields);
