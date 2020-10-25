<?php

require __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

foreach ($_REQUEST as $a => $b) {
    $$a = $b;
}

$eh = new ErrorHandler();
xoops_cp_header();
echo $oAdminButton->renderButtons('adminworkstations');

#OpenTable();

echo "<script type='text/javascript' src='http://www.odesia.com/v3/modules/xentpark/include/extended_block.js'></script>";
echo "<script type='text/javascript' src='http://www.odesia.com/v3/modules/xentpark/include/moveBetweenLists.js'></script>";

#echo "<div class='adminHeader'>"._AM_XENT_PARK_SEARCH."</div><br>";

function SEARCHShowForm()
{
    $sform = new XoopsThemeForm('&nbsp;', 'search', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    $tray = new XoopsFormElementTray(_AM_XENT_PARK_SEARCH, '');

    $go_button = new XoopsFormButton('', 'go', _AM_XENT_GO, 'submit');

    $go_button->setExtra("onmouseover='document.search.op.value=\"SEARCHExecute\"'");

    $tray->addElement(new XoopsFormText('', 'search', 50, 255, $_POST['search']));

    $tray->addElement($go_button);

    $sform->addElement($tray);

    $sform->addElement(new XoopsFormHidden('op', ''));

    $sform->display();
}

function SEARCHExecute()
{
    $xentSearch = new XentSearch();

    $xentSearch->addSection('ws', 'Chercher dans les noms de machines', 'xent_park_workstations', 'name', 'adminworkstations.php?op=WSEditWs', 'id', 'ID_WORKSTATION');

    $xentSearch->addSection('equips', 'Chercher dans les noms des equipements', 'xent_park_equips', 'name', 'adminequips.php?op=EQUIPSEditEquip', 'id', 'ID_EQUIP');

    $xentSearch->addSection('apps', 'Chercher dans les noms des applications', 'xent_park_apps', 'name', 'adminapps.php?op=APPSEditApps', 'id', 'ID_APPS');

    $xentSearch->execute($_POST['search']);

    $sform = new XoopsThemeForm('&nbsp;', 'search2', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    $tray = new XoopsFormElementTray(_AM_XENT_PARK_SEARCH, '');

    $go_button = new XoopsFormButton('', 'go', _AM_XENT_GO, 'submit');

    $go_button->setExtra("onmouseover='document.search2.op.value=\"SEARCHExecute\"'");

    $tray->addElement(new XoopsFormText('', 'search', 50, 255, $_POST['search']));

    $tray->addElement($go_button);

    $sform->addElement($tray);

    $sform->addElement(new XoopsFormHidden('op', ''));

    $sform->display();

    $xentSearch->display();
}

// ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
    case 'SEARCHExecute':
        SEARCHExecute();

        // no break
    default:
        SEARCHShowForm();
        break;
}

// *************************** Fin de NTS **********************************

buildWSActionMenu();

#CloseTable();

xoops_cp_footer();
