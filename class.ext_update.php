<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2005-2015 René Fritz (r.fritz@colorcube.de)
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once(ExtensionManagementUtility::extPath('static_info_tables') . 'class.tx_staticinfotables_encoding.php');

/**
 * Class for updating the db
 *
 * @author    René Fritz <r.fritz@colorcube.de>
 * @co-author    Simon Schmidt <sfs@marketing-factory.de>
 * @co-author    Tomasz Krawczyk <tomasz@typo3.pl>
 */
class ext_update
{

    /**
     * Main function, returning the HTML content of the module
     *
     * @return    string        HTML
     */
    public function main()
    {

        $content = '';

        $content .= '<br />Update the Static Info Tables with new language labels.';
        $content .= '<br />';
        $import = GeneralUtility::_GP('import');

        if ($import == 'Import') {

            $destEncoding = GeneralUtility::_GP('dest_encoding');
            $extPath = ExtensionManagementUtility::extPath('static_info_tables_cz');

            // Update polish labels
            $fileContent = explode("\n", GeneralUtility::getURL($extPath . 'ext_tables_static_update.sql'));

            foreach ($fileContent as $line) {
                $line = trim($line);
                if ($line && preg_match('#^UPDATE#i', $line)) {
                    $query = $this->getUpdateEncoded($line, $destEncoding);
                    if (TYPO3_DLOG) {
                        GeneralUtility::devLog('SQL Query', 'static_info_tables_cz', 0, ['query:' => $query]);
                    }
                    $res = $GLOBALS['TYPO3_DB']->admin_query($query);
                }
            }
            $content .= '<br />';
            $content .= '<p>Encoding: ' . htmlspecialchars($destEncoding) . '</p>';
            $content .= '<p>Done.</p>';
        } elseif (ExtensionManagementUtility::isLoaded('static_info_tables')) {

            $content .= '</form>';
            $content .= '<form action="' . htmlspecialchars(GeneralUtility::linkThisScript()) . '" method="post">';
            $content .= '<br />Destination character encoding:';
            $content .= '<br />' . tx_staticinfotables_encoding::getEncodingSelect('dest_encoding', '', 'utf-8');
            $content .= '<br />(The character encoding must match the encoding of the existing tables data. By default this is UTF-8.)';
            $content .= '<br /><br />';
            $content .= '<input type="submit" name="import" value="Import" />';
            $content .= '</form>';
        } else {
            $content .= '<br /><strong>The extension static_info_tables needs to be installed first!</strong>';
        }

        return $content;
    }


    /**
     * Convert the values of a SQL update statement to a different encoding than UTF-8.
     *
     * @param string $query Update statement like: UPDATE static_countries SET cn_short_cz='XXX' WHERE cn_iso_2='PT';
     * @param string $destEncoding Destination encoding
     * @return string Converted update statement
     */
    protected function getUpdateEncoded($query, $destEncoding)
    {
        static $csconv;

        if (!($destEncoding === 'utf-8')) {
            if (!is_object($csconv)) {
                $csconv = GeneralUtility::makeInstance('t3lib_cs');
            }

            $queryElements = explode('WHERE', $query);
            $where = preg_replace('#;$#', '', trim($queryElements[1]));

            $queryElements = explode('SET', $queryElements[0]);
            $queryFields = $queryElements[1];

            $queryElements = GeneralUtility::trimExplode('UPDATE', $queryElements[0], 1);
            $table = $queryElements[0];

            $fields_values = [];
            $queryFieldsArray = preg_split('/[,]/', $queryFields, 1);
            foreach ($queryFieldsArray as $fieldsSet) {
                $col = GeneralUtility::trimExplode('=', $fieldsSet, 1);
                $value = stripslashes(substr($col[1], 1, strlen($col[1]) - 2));
                $value = $csconv->conv($value, 'utf-8', $destEncoding);
                $fields_values[$col[0]] = $value;
            }

            $query = $GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, $where, $fields_values);
        }
        return $query;
    }

    /**
     * @return bool
     */
    public function access()
    {
        return true;
    }
}
