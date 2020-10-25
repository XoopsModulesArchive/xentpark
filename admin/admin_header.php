<?php

//echo "<link rel='stylesheet' type='text/css' media='all' href='include/admin.css'>";

require __DIR__ . '/admin_buttons.php';
include '../../../mainfile.php';
require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once dirname(__DIR__) . '/include/functions.php';
require_once dirname(__DIR__) . '/class/xent_park_equips.php';
require_once dirname(__DIR__) . '/class/xent_park_workstations.php';
require_once dirname(__DIR__) . '/class/xent_park_equiptypes.php';
require_once dirname(__DIR__) . '/class/xent_park_sites.php';
require_once dirname(__DIR__) . '/class/xent_park_apps.php';
require_once dirname(__DIR__) . '/class/xent_search.php';
require_once XOOPS_ROOT_PATH . '/modules/xentgen/class/xent_users.php';
#require_once XOOPS_ROOT_PATH."/modules/xentgen/include/xentfunctions.php";

global $xoopsModule, $xoopsTpl;

$versioninfo = $moduleHandler->get($xoopsModule->getVar('mid'));
$module_tables = $versioninfo->getInfo('tables');

if (is_object($xoopsUser)) {
    $xoopsModule = XoopsModule::getByDirname('xentpark');

    if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
        redirect_header(XOOPS_URL . '/', 1, _NOPERM);

        exit();
    }
} else {
    redirect_header(XOOPS_URL . '/', 1, _NOPERM);

    exit();
}

$module_id = $xoopsModule->getVar('mid');
$oAdminButton = new AdminButtons();
$oAdminButton->AddTitle(_AM_XENT_PARK_ADMINMENUTITLE);

$oAdminButton->AddButton(_AM_XENT_PARK_ADMINWS, 'adminworkstations.php', 'adminworkstations');
$oAdminButton->AddButton(_AM_XENT_PARK_ADMINEQUIPS, 'adminequips.php', 'adminequips');

$oAdminButton->AddTopLink(_AM_XENT_PARK_PREFERENCES, XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $module_id);
$oAdminButton->AddTopLink(_AM_XENT_PARK_SEARCH, XOOPS_URL . '/modules/xentpark/admin/adminsearch.php');
$oAdminButton->addTopLink(_AM_XENT_PARK_UPDATEMODULE, XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=xentpark');

$myts = MyTextSanitizer::getInstance();
