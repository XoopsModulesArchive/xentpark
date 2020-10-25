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

echo "<div class='adminHeader'>" . _AM_XENT_PARK_ADMINWSTITLE . '</div><br>';

function WSShowWs()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $module_tables;

    echo "<table width='100%'><tr><td align='left'><div align='left' class='adminActionMenu'>" . _AM_XENT_PARK_WSPRESTEXT . "</div></td><td><div align='right' class='adminActionMenu'><a href='adminworkstations.php?op=WSAddWs'>" . _AM_XENT_PARK_ADDWS . '</a></div></td></tr></table>';

    $xentParkWorkstations = new XentParkWorkstations();

    $xentParkEquips = new XentParkEquips();

    $xentParkEquiptypes = new XentParkEquiptypes();

    $display = "style='display:none'";

    $result = $xentParkWorkstations->getAllWorkstations();

    echo "<table class='outer' width='100%'><tr><th width='80%'>" . _AM_XENT_PARK_WS . "</th><th width='20%'>" . _AM_XENT_OPTIONS . '</th></tr><tr><td colspan=2>';

    while (false !== ($ws = $xoopsDB->fetchArray($result))) {
        $xentParkWorkstations->setIdWorkstation($ws['ID_WORKSTATION']);

        $xentParkWorkstations->setName($ws['name']);

        $xentParkWorkstations->setDesc($ws['description']);

        $xentParkWorkstations->setIdOwner($ws['id_owner']);

        $xentParkWorkstations->setIdType($ws['id_type']);

        $xentParkWorkstations->setIdSite($ws['id_site']);

        $bloc_pliable = "onClick=\"xentdynamicmenu_blockpliable('" . $xentParkWorkstations->getIdWorkstation() . "')\"";

        if (0 == $ws['id_type']) {
            $image = 'mycomputer.gif';
        } elseif (1 == $ws['id_type']) {
            $image = 'icon_laptop.gif';
        }

        echo "	<table class='even' width='100%'><tr><td><table width='100%'>
	   					<tr>
	   						<td width='5%'><a href='#' $bloc_pliable>+<img src='../images/$image'></img></a></td>
	   						<td width='75%'><div class='wsName'>"
             . $xentParkWorkstations->getName()
             . "</div><div class='wsMainInfo'>Attribué à : "
             . $xentParkWorkstations->getOwner()
             . '<br>Bureau # : '
             . $xentParkWorkstations->getSite()
             . "</div></td>
	   						<td width='20%' align='center'><a href='adminworkstations.php?op=WSEditWs&id="
             . $xentParkWorkstations->getIdWorkstation()
             . "'><img src='../images/edit.gif' title='"
             . _AM_XENT_EDIT
             . "'></img></a><a a href='adminworkstations.php?op=WSAreYouSureToDeleteWs&id="
             . $xentParkWorkstations->getIdWorkstation()
             . "'><img src='../images/delete.gif' title='"
             . _AM_XENT_DELETE
             . "'></img></a><a href='adminworkstations.php?op=WSCloneWs&id="
             . $xentParkWorkstations->getIdWorkstation()
             . "' ><img src='../images/mycomputer_clone.gif' title='"
             . _AM_XENT_CLONE
             . "' ></img></a></td>
	   					</tr>
	   		
	   					
		   					<tr>
		   						<td></td>
		   						<td><br><div id='"
             . $xentParkWorkstations->getIdWorkstation()
             . "' $display class='wsComplInfo'>";

        $arr = $xentParkWorkstations->getEquips();

        foreach ($arr as $key => $value) {
            $equip = $xentParkEquips->getEquip($key);

            $xentParkEquips->setIdEquip($equip['ID_EQUIP']);

            $xentParkEquips->setName($equip['name']);

            $xentParkEquips->setDesc($equip['desc']);

            $xentParkEquips->setSerial($equip['serial']);

            $xentParkEquips->setIdTypeEquip($equip['id_type_equip']);

            $equiptype = $xentParkEquiptypes->getEquiptype($xentParkEquips->getIdTypeEquip());

            $xentParkEquiptypes->setIdEquiptype($equiptype['ID_EQUIPTYPE']);

            $xentParkEquiptypes->setName($equiptype['name']);

            echo $xentParkEquiptypes->getName() . ' : ' . $xentParkEquips->getName() . '<br>';
        }

        echo '<br>Description : ' . $xentParkWorkstations->getDesc() . '<br><br>
	   							Applications installées : <br>';

        $arr = $xentParkWorkstations->getApps();

        foreach ($arr as $value) {
            echo $value . '<br>';
        }

        echo '</div></td>	
		   					</tr>
	   					
	   				</table></td></tr></table><br>';
    }

    echo '</td></tr></table>';
}

function WSAddWs()
{
    $xentUsers = new XentUsers();

    $xentParkWorkstations = new XentParkWorkstations();

    $xentParkSites = new XentParkSites();

    $sform = new XoopsThemeForm(_AM_XENT_PARK_ADDWS, 'addws', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    $sform->addElement(new XoopsFormText(_AM_XENT_PARK_NAME, 'name', 50, 255, ''));

    $sform->addElement(new XoopsFormTextArea(_AM_XENT_PARK_DESC, 'desc'));

    $sform->addElement(makeSelect(_AM_XENT_PARK_OWNER, 'owner', 0, $xentUsers->getAllUsersInArray(), 1, 0, false));

    $sform->addElement(makeSelect(_AM_XENT_PARK_WSTYPE, 'type', 0, $xentParkWorkstations->getWorkstationTypes(), 1, 0, false));

    $sform->addElement(makeSelect(_AM_XENT_PARK_SITE, 'site', 0, $xentParkSites->getAllSitesInArray(), 1, 0, false));

    $save_button = new XoopsFormButton('', 'add', _AM_XENT_ADD, 'submit');

    $save_button->setExtra("onmouseover='document.addws.op.value=\"WSSaveAddWs\"'");

    $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

    $cancel_button->setExtra("onmouseover='document.addws.op.value=\"WSShowWs\"'");

    $button_tray = new XoopsFormElementTray('', '');

    $button_tray->addElement($save_button);

    $button_tray->addElement($cancel_button);

    $sform->addElement($button_tray);

    $sform->addElement(new XoopsFormHidden('op', ''));

    $sform->display();
}

function WSSaveAddWs($name, $desc, $owner, $type, $site)
{
    $xentParkWorkstations = new XentParkWorkstations();

    $xentParkWorkstations->setName(str_replace("'", '’', $name));

    $xentParkWorkstations->setDesc(str_replace("'", '’', $desc));

    $xentParkWorkstations->setIdOwner($owner);

    $xentParkWorkstations->setIdType($type);

    $xentParkWorkstations->setIdSite($site);

    $xentParkWorkstations->add();
}

function WSEditWs()
{
    $xentUsers = new XentUsers();

    $xentParkWorkstations = new XentParkWorkstations();

    $xentParkSites = new XentParkSites();

    $xentParkEquips = new XentParkEquips();

    $xentParkEquiptype = new XentParkEquiptypes();

    $xentParkApps = new XentParkApps();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = 0;
        }
    }

    if (!empty($_POST['equiptypechoice'])) {
        $id_equiptypechoice = $_POST['equiptypechoice'];
    } else {
        $id_equiptypechoice = 0;
    }

    if (0 != $id) {
        $ws = $xentParkWorkstations->getWorkstation($id);

        if (!empty($ws['ID_WORKSTATION'])) {
            $xentParkWorkstations->setIdWorkstation($ws['ID_WORKSTATION']);

            $xentParkWorkstations->setName($ws['name']);

            $xentParkWorkstations->setDesc($ws['description']);

            $xentParkWorkstations->setIdOwner($ws['id_owner']);

            $xentParkWorkstations->setIdType($ws['id_type']);

            if (0 == $id_equiptypechoice) {
                $xentParkEquips->setIdTypeEquip(0);
            } else {
                $xentParkEquips->setIdTypeEquip($id_equiptypechoice);
            }

            if (!empty($_POST['delequips'])) {
                if ('1' == $_POST['delequips']) {
                    $xentParkWorkstations->deleteEquips($_POST['list2']);
                }
            }

            if (!empty($_POST['addequips'])) {
                if ('1' == $_POST['addequips']) {
                    // si le prog passe ici, ca veut dire que l'usager a cliqué sur le combo

                    // box des types d'équipements, il faut donc sauvegarder les données de

                    // l'usager

                    // sauvegarder les données : supprimer tout ce qu'il y a pour cet type d'équipement

                    // et ensuite tout sauvegarder

                    if (false === $xentParkEquips->associateToWs($_POST['list1'])) {
                        $xentParkWorkstations->addEquips($_POST['list1']);
                    }
                }
            }

            if (!empty($_POST['addapps'])) {
                if ('1' == $_POST['addapps']) {
                    if (false === $xentParkApps->associateToWs($_POST['apps1'], $xentParkWorkstations->getIdWorkstation())) {
                        $xentParkWorkstations->addApps($_POST['apps1']);
                    }
                }
            }

            if (!empty($_POST['delapps'])) {
                if ('1' == $_POST['delapps']) {
                    $xentParkWorkstations->deleteApps($_POST['apps2']);
                }
            }

            $xentParkWorkstations->setIdSite($ws['id_site']);

            $sform = new XoopsThemeForm(_AM_XENT_PARK_EDITWS . ' - ' . $xentParkWorkstations->getName(), 'editws', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormText(_AM_XENT_PARK_NAME, 'name', 50, 255, $xentParkWorkstations->getName()));

            $sform->addElement(new XoopsFormTextArea(_AM_XENT_PARK_DESC, 'desc', $xentParkWorkstations->getDesc()));

            $sform->addElement(makeSelect(_AM_XENT_PARK_OWNER, 'owner', $xentParkWorkstations->getIdOwner(), $xentUsers->getAllUsersInArray(), 1, 0, false));

            $sform->addElement(makeSelect(_AM_XENT_PARK_WSTYPE, 'type', $xentParkWorkstations->getIdType(), $xentParkWorkstations->getWorkstationTypes(), 1, 0, false));

            $sform->addElement(makeSelect(_AM_XENT_PARK_SITE, 'site', $xentParkWorkstations->getIdSite(), $xentParkSites->getAllSitesInArray(), 1, 0, false));

            $save_button = new XoopsFormButton('', 'add', _AM_XENT_MODIFY, 'submit');

            $save_button->setExtra("onclick='document.editws.op.value=\"WSSaveEditWs\"'");

            $cancel_button = new XoopsFormButton('', 'cancel', _AM_XENT_CANCEL, 'submit');

            $cancel_button->setExtra("onclick='document.editws.op.value=\"WSShowWs\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($save_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('id', $xentParkWorkstations->getIdWorkstation()));

            $sform->addElement(new XoopsFormHidden('op', ''));

            $sform->display();

            // form pour les équipements de la machine

            $sform_ws = new XoopsThemeForm(_AM_XENT_PARK_EDITWSEQUIPS . ' - ' . $xentParkWorkstations->getName(), 'editwsequips', xoops_getenv('PHP_SELF'));

            $sform_ws->setExtra('enctype="multipart/form-data"');

            $select1 = makeSelect('', 'list1', 0, $xentParkEquips->getAllEquipsInStockInArray(), 5, 0, false);

            $select2 = makeSelect('', 'list2', 0, $xentParkWorkstations->getEquips(), 5, 0, false);

            $button_1 = new XoopsFormButton('', '', '<<', 'submit');

            $button_1->setExtra("onClick='document.editwsequips.delequips.value=\"1\"'");

            $button_2 = new XoopsFormButton('', '', '>>', 'submit');

            $button_2->setExtra("onClick='document.editwsequips.addequips.value=\"1\"'");

            $select_equiptype = makeSelect('', 'equiptypechoice', $id_equiptypechoice, $xentParkEquiptype->getAllEquiptypesInArray(true), 1, 0, false);

            $select_equiptype->setExtra("onclick='document.editwsequips.op.value=\"WSEditWs\"'  onChange=\"submit()\"");

            $tray_ws = new XoopsFormElementTray(_AM_XENT_PARK_EQUIPS);

            $tray_ws->addElement(new XoopsFormLabel("<table><tr><td valign='top'>"));

            $tray_ws->addElement(new XoopsFormLabel(_AM_XENT_PARK_EQUIPSINSTOCK));

            $tray_ws->addElement(new XoopsFormLabel('<br><br>'));

            $tray_ws->addElement($select_equiptype);

            $tray_ws->addElement(new XoopsFormLabel('<br>'));

            $tray_ws->addElement($select1);

            $tray_ws->addElement(new XoopsFormLabel("</td><td valign='middle'>"));

            $tray_ws->addElement($button_1);

            $tray_ws->addElement(new XoopsFormLabel('<br><br>'));

            $tray_ws->addElement($button_2);

            $tray_ws->addElement(new XoopsFormLabel("</td><td valign='top'>"));

            $tray_ws->addElement(new XoopsFormLabel(_AM_XENT_PARK_EQUIPSWS));

            $tray_ws->addElement(new XoopsFormLabel('<br><br>'));

            $tray_ws->addElement($select2);

            $tray_ws->addElement(new XoopsFormLabel('</td></tr></table>'));

            $sform_ws->addElement($tray_ws);

            $sform_ws->addElement(new XoopsFormHidden('id', $xentParkWorkstations->getIdWorkstation()));

            $sform_ws->addElement(new XoopsFormHidden('op', 'WSEditWs'));

            $sform_ws->addElement(new XoopsFormHidden('addequips', '0'));

            $sform_ws->addElement(new XoopsFormHidden('delequips', '0'));

            $sform_ws->display();

            // form pour les cd de drivers

            $sform_apps = new XoopsThemeForm(_AM_XENT_PARK_EDITWSAPPS . ' - ' . $xentParkWorkstations->getName(), 'editwsapps', xoops_getenv('PHP_SELF'));

            $sform_apps->setExtra('enctype="multipart/form-data"');

            $appsselect1 = makeSelect('', 'apps1', 0, $xentParkApps->getAllAppsArray(), 5, 0, false);

            $appsselect2 = makeSelect('', 'apps2', 0, $xentParkWorkstations->getApps(), 5, 0, false);

            $appsbutton_1 = new XoopsFormButton('', '', '<<', 'submit');

            $appsbutton_1->setExtra("onClick='document.editwsapps.delapps.value=\"1\"'");

            $appsbutton_2 = new XoopsFormButton('', '', '>>', 'submit');

            $appsbutton_2->setExtra("onClick='document.editwsapps.addapps.value=\"1\"'");

            $tray_apps = new XoopsFormElementTray(_AM_XENT_PARK_APPS);

            $tray_apps->addElement($appsselect1);

            $tray_apps->addElement($appsbutton_1);

            $tray_apps->addElement($appsbutton_2);

            $tray_apps->addElement($appsselect2);

            $sform_apps->addElement($tray_apps);

            $sform_apps->addElement(new XoopsFormHidden('id', $xentParkWorkstations->getIdWorkstation()));

            $sform_apps->addElement(new XoopsFormHidden('op', 'WSEditWs'));

            $sform_apps->addElement(new XoopsFormHidden('addapps', '0'));

            $sform_apps->addElement(new XoopsFormHidden('delapps', '0'));

            $sform_apps->display();
        }
    }
}

function WSSaveEditWs($id, $name, $desc, $owner, $type, $site)
{
    $xentParkWorkstations = new XentParkWorkstations();

    $xentParkWorkstations->setIdWorkstation($id);

    $xentParkWorkstations->setName($name);

    $xentParkWorkstations->setDesc($desc);

    $xentParkWorkstations->setIdOwner($owner);

    $xentParkWorkstations->setIdType($type);

    $xentParkWorkstations->setIdSite($site);

    $xentParkWorkstations->update();
}

function WSAreYouSureToDeleteWs($id)
{
    $xentParkWorkstations = new XentParkWorkstations();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }

    $ws = $xentParkWorkstations->getWorkstation($id);

    if (!empty($ws['ID_WORKSTATION'])) {
        $sform = new XoopsThemeForm(_AM_XENT_PARK_AREYOUSUREDELETE, 'delws', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormLabel(_AM_XENT_PARK_NAME, $ws['name']));

        $delete_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

        $delete_button->setExtra("onmouseover='document.delws.op.value=\"WSDeleteWs\"'");

        $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

        $cancel_button->setExtra("onmouseover='document.delws.op.value=\"WSShowWs\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($delete_button);

        $button_tray->addElement($cancel_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('id', $id));

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->display();
    }  

    // aucune station, msg d'erreur
}

function WSDeleteWs($id)
{
    $xentParkWorkstations = new XentParkWorkstations();

    $xentParkWorkstations->setIdWorkstation($id);

    $xentParkWorkstations->delete();
}

function WSCloneWs()
{
    $xentParkWorkstations = new XentParkWorkstations();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        $xentParkWorkstations->setIdWorkstation($id);

        $xentParkWorkstations->cloneWS();
    }
}

// ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
    case 'WSAddWs':
        WSAddWs();
        break;
    case 'WSSaveAddWs':
        WSSaveAddWs($name, $desc, $owner, $type, $site);
        break;
    case 'WSEditWs':
        WSEditWs();
        break;
    case 'WSSaveEditWs':
        WSSaveEditWs($id, $name, $desc, $owner, $type, $site);
        break;
    case 'WSAreYouSureToDeleteWs':
        WSAreYouSureToDeleteWs($id);
        break;
    case 'WSDeleteWs':
        WSDeleteWs($id);
        break;
    case 'WSCloneWs':
        WSCloneWs();
        break;
    default:
        WSShowWs();
        break;
}

// *************************** Fin de NTS **********************************

buildWSActionMenu();

#CloseTable();

xoops_cp_footer();
