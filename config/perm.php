<?php
/**
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Zoullou (http://www.zoullou.net)
 * @package     ' . $moduleDirName . '
 */

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

return (object)[
    'modulePerm' => [
        1 => [
            'maskId'    => 1,
            'name'      => 'public_access',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_CAN_ACCESS',
            'title'     => 'CO_' . $moduleDirNameUpper . '_ACCESS_PERM',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_ACCESS_PERM_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_ACCESS_PERM_INFO',
        ],
        2 => [
            'maskId'    => 2,
            'name'      => 'public_rate',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_CAN_RATE',
            'title'     => 'CO_' . $moduleDirNameUpper . '_RATE_PERM',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_RATE_PERM_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_RATE_PERM_INFO',
        ],
        3 => [
            'maskId'    => 4,
            'name'      => 'public_submit',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_CAN_SUBMIT',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_SUBMIT',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_SUBMIT_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_SUBMIT_INFO',
        ],
        4 => [
            'maskId'    => 8,
            'name'      => 'public_download',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_CAN_DOWNLOAD',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DOWNLOAD',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DOWNLOAD_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DOWNLOAD_INFO',
        ],
        5 => [
            'maskId'    => 16,
            'name'      => 'public_download_original',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_CAN_DOWNLOAD_ORIG',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DOWNLOAD_ORIG',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DOWNLOAD_ORIG_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DOWNLOAD_ORIG_INFO',
        ],
        6 => [
            'maskId'    => 32,
            'name'      => 'public_upload',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_CAN_UPLOAD',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_UPLOAD',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_UPLOAD_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_UPLOAD_INFO',
        ],
        7 => [
            'maskId'    => 64,
            'name'      => 'public_autoapprove',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_AUTOAPPROVE',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_AUTOAPROVE',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_AUTOAPROVE_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_AUTOAPROVE_INFO',
        ],
        8 => [
            'maskId'    => 128,
            'name'      => 'public_displayed',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_DISPLAYED',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DISPLAYED',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DISPLAYED_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DISPLAYED_INFO',
        ],
        9 => [
            'maskId'    => 256,
            'name'      => 'public_delete',
            'maskTitle' => 'CO_' . $moduleDirNameUpper . '_DELETE',
            'title'     => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DELETE',
            'desc'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DELETE_DESC',
            'info'      => 'CO_' . $moduleDirNameUpper . '_PUBLIC_DELETE_INFO',
        ],
    ],
    'pluginPerm' => [],
];

// xmNews by Mage
//getGroupSelectFormForItem($gperm_name, $gperm_itemid, $caption, $name, $include_anon, $size, $multiple)

$permHelper->getGroupSelectFormForItem('xmnews_viewabstract', $this->getVar('category_id'), _MA_XMNEWS_PERMISSION_VIEW_ABSTRACT_THIS, 'xmnews_viewabstract_perms', true);
