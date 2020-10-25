<?php

require __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';

foreach ($_REQUEST as $a => $b) {
    $$a = $b;
}

$eh = new ErrorHandler();
xoops_cp_header();
echo $oAdminButton->renderButtons('adminequips');

#OpenTable();

echo "<script type='text/javascript' src='http://www.odesia.com/v3/modules/xentpark/include/extended_block.js'></script>";
echo "<script type='text/javascript' src='http://www.odesia.com/v3/modules/xentpark/include/moveBetweenLists.js'></script>";

echo "<div class='adminHeader'>" . _AM_XENT_PARK_ADMINEQUIPSTITLE . '</div><br>';

function EQUIPSShowEquips()
{
    global $xoopsDB;

    echo "<table width='100%'><tr><td align='left'><div align='left' class='adminActionMenu'>" . _AM_XENT_PARK_EQUIPSPRESTEXT . "</div></td><td><div align='right' class='adminActionMenu'><a href='adminequips.php?op=EQUIPSAddEquiptype'>" . _AM_XENT_PARK_ADDEQUIPTYPE . '</a></div></td></tr></table>';

    $display = "style='display:none'";

    $xentParkEquiptypes = new XentParkEquiptypes();

    $xentParkEquips = new XentParkEquips();

    $result = $xentParkEquiptypes->getAllEquiptypes();

    while (false !== ($equiptype = $xoopsDB->fetchArray($result))) {
        $xentParkEquiptypes->setIdEquiptype($equiptype['ID_EQUIPTYPE']);

        $xentParkEquiptypes->setName($equiptype['name']);

        $bloc_pliable = "onClick=\"xentdynamicmenu_blockpliable('" . $xentParkEquiptypes->getIdEquiptype() . "')\"";

        echo "	<table id='equiptypeName' width='100%' style='border-collapse: collapse'>
    					<tr>
    						<td id='equiptypeName' width='80%'><div class='equiptypeName'><a href='#' $bloc_pliable>+ " . $xentParkEquiptypes->getName() . "</a></div></td>
    						<td id='equiptypeName'><a href='adminequips.php?op=EQUIPSEditEquiptype&id=" . $xentParkEquiptypes->getIdEquiptype() . "'><img src='../images/edit.gif'></img></a><a href='adminequips.php?op=EQUIPSAreYouSureToDeleteEquiptype&id=" . $xentParkEquiptypes->getIdEquiptype() . "'><img src='../images/delete.gif'></img></a></td>
    					</tr>
    				</table>
    				";

        $result1 = $xentParkEquiptypes->getEquips();

        echo "	<div id='" . $xentParkEquiptypes->getIdEquiptype() . "' style='display:none'>
    					<br><div align='right' class='adminActionMenu'><a href='adminequips.php?op=EQUIPSAddEquip&id=" . $xentParkEquiptypes->getIdEquiptype() . "'>" . _AM_XENT_PARK_ADDEQUIP . $xentParkEquiptypes->getName() . "</a></div>	
    					<table class'outer' width='100%' border=1 style='border-collapse: collapse'>
    						<tr>
    							<th width='3%'></th>
    							<th width='78%'>Nom</th>
    							<th width='19%'>Options</th>
    						</tr>";

        while (false !== ($equip = $xoopsDB->fetchArray($result1))) {
            $xentParkEquips->setIdEquip($equip['ID_EQUIP']);

            $xentParkEquips->setName($equip['name']);

            $xentParkEquips->setDesc($equip['description']);

            $xentParkEquips->setSerial($equip['serial']);

            $xentParkEquips->setIsRentable($equip['is_rentable']);

            $bloc_pliable = "onClick=\"xentdynamicmenu_blockpliable('" . $xentParkEquips->getIdEquip() . "')\"";

            echo "		<tr>
    							<td align='center' id='inStock'>";

            if ($xentParkEquips->inStock($xentParkEquips->getIdEquip())) {
                echo "<img width='20' height='20' src='../images/green.gif'></img>";
            } else {
                echo "<img width='20' height='20' src='../images/red.gif'></img>";
            }

            echo "</td>	<td valign='middle'><a href='#' $bloc_pliable>+"
                 . $xentParkEquips->getName()
                 . ' (S/N : '
                 . $xentParkEquips->getSerial()
                 . ")</a>
    												<div id='"
                 . $xentParkEquips->getIdEquip()
                 . "' $display class='wsComplInfo'>"
                 . _AM_XENT_PARK_DESC
                 . ' : '
                 . $xentParkEquips->getDesc()
                 . '<br>'
                 . _AM_XENT_PARK_ISRENTABLE
                 . ' : '
                 . convertIntBinIntoText($xentParkEquips->getIsRentable())
                 . '<br><br>';

            $arr = $xentParkEquips->getEquipWs($xentParkEquips->getIdEquip());

            $firstDisp = false;

            if (!empty($arr)) {
                echo _AM_XENT_PARK_WS . ' : ';

                foreach ($arr as $a) {
                    if (false === $firstDisp) {
                        echo $a;

                        $firstDisp = true;
                    } else {
                        echo ', ' . $a;
                    }
                }

                echo '<br>';
            }

            echo _AM_XENT_PARK_ISRENT . ' : ' . convertIntBinIntoText($xentParkEquips->isRent($xentParkEquips->getIdEquip())) . "</div>
    											</td>
    							<td><a href='adminequips.php?op=EQUIPSEditEquip&id=" . $xentParkEquips->getIdEquip() . "'><img src='../images/edit.gif'></a></img><a href='adminequips.php?op=EQUIPSAreYouSureToDeleteEquip&id=" . $xentParkEquips->getIdEquip() . "'><img src='../images/delete.gif'></img></a></td>
    						</tr>	
    					";
        }

        echo '</table><br><br></div>';

        echo '<br>';
    }
}

function EQUIPSAddEquiptype()
{
    $sform = new XoopsThemeForm(_AM_XENT_PARK_ADDEQUIPTYPE, 'addequiptype', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    $sform->addElement(new XoopsFormText(_AM_XENT_PARK_NAME, 'name', 50, 255, ''));

    $save_button = new XoopsFormButton('', 'add', _AM_XENT_ADD, 'submit');

    $save_button->setExtra("onmouseover='document.addequiptype.op.value=\"EQUIPSSaveAddEquiptype\"'");

    $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

    $cancel_button->setExtra("onmouseover='document.addequiptype.op.value=\"EQUIPSShowEquip\"'");

    $button_tray = new XoopsFormElementTray('', '');

    $button_tray->addElement($save_button);

    $button_tray->addElement($cancel_button);

    $sform->addElement($button_tray);

    $sform->addElement(new XoopsFormHidden('op', ''));

    $sform->display();
}

function EQUIPSSaveAddEquiptype($name)
{
    $xentParkEquiptype = new XentParkEquiptypes();

    $xentParkEquiptype->setName(str_replace("'", '’', $name));

    $xentParkEquiptype->add();
}

function EQUIPSAddEquip()
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        $sform = new XoopsThemeForm(_AM_XENT_PARK_ADDEQUIP, 'addequip', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_AM_XENT_PARK_NAME, 'name', 50, 255, ''));

        $sform->addElement(new XoopsFormTextArea(_AM_XENT_PARK_DESC, 'desc'));

        $sform->addElement(new XoopsFormText(_AM_XENT_PARK_SERIAL, 'serial', 50, 255, ''));

        $sform->addElement(makeSelect(_AM_XENT_PARK_ISRENTABLE, 'is_rentable', 0, makeNoYesArray(), 2, 0, false));

        $save_button = new XoopsFormButton('', 'add', _AM_XENT_ADD, 'submit');

        $save_button->setExtra("onmouseover='document.addequip.op.value=\"EQUIPSSaveAddEquip\"'");

        $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

        $cancel_button->setExtra("onmouseover='document.addequip.op.value=\"EQUIPSShowEquip\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($save_button);

        $button_tray->addElement($cancel_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->addElement(new XoopsFormHidden('id', $id));

        $sform->display();
    }
}

function EQUIPSSaveAddEquip($name, $desc, $serial, $is_rentable, $id_type_equip)
{
    $xentParkEquips = new XentParkEquips();

    $xentParkEquips->setName(str_replace("'", '’', $name));

    $xentParkEquips->setDesc(str_replace("'", '’', $desc));

    $xentParkEquips->setSerial(str_replace("'", '’', $serial));

    $xentParkEquips->setIsRentable($is_rentable);

    $xentParkEquips->setIdTypeEquip($id_type_equip);

    $xentParkEquips->add();
}

function EQUIPSEditEquiptype()
{
    $xentParkEquiptype = new XentParkEquiptypes();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = 0;
        }
    }

    if (0 != $id) {
        $equiptype = $xentParkEquiptype->getEquiptype($id);

        if (!empty($equiptype['ID_EQUIPTYPE'])) {
            $xentParkEquiptype->setIdEquiptype($equiptype['ID_EQUIPTYPE']);

            $xentParkEquiptype->setName($equiptype['name']);

            $sform = new XoopsThemeForm(_AM_XENT_PARK_EDITEQUIPTYPE, 'editequiptype', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormText(_AM_XENT_PARK_NAME, 'name', 50, 255, $xentParkEquiptype->getName()));

            $save_button = new XoopsFormButton('', 'add', _AM_XENT_MODIFY, 'submit');

            $save_button->setExtra("onmouseover='document.editequiptype.op.value=\"EQUIPSSaveEditEquiptype\"'");

            $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

            $cancel_button->setExtra("onmouseover='document.editequiptype.op.value=\"EQUIPSShowEquip\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($save_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('op', ''));

            $sform->addElement(new XoopsFormHidden('id', $xentParkEquiptype->getIdEquiptype()));

            $sform->display();
        }
    }
}

function EQUIPSSaveEditEquiptype($id, $name)
{
    $xentParkEquiptype = new XentParkEquiptypes();

    $xentParkEquiptype->setIdEquiptype($id);

    $xentParkEquiptype->setName(str_replace("'", '’', $name));

    $xentParkEquiptype->update();
}

function EQUIPSAreYouSureToDeleteEquiptype($id)
{
    $xentParkEquiptype = new XentParkEquiptypes();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }

    $equiptype = $xentParkEquiptype->getEquiptype($id);

    if (!empty($equiptype['ID_EQUIPTYPE'])) {
        $xentParkEquiptype->setName($equiptype['name']);

        $sform = new XoopsThemeForm(_AM_XENT_PARK_AREYOUSUREDELETE, 'delequiptype', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormLabel(_AM_XENT_PARK_NAME, $xentParkEquiptype->getName()));

        $delete_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

        $delete_button->setExtra("onmouseover='document.delequiptype.op.value=\"EQUIPSDeleteEquiptype\"'");

        $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

        $cancel_button->setExtra("onmouseover='document.delequiptype.op.value=\"EQUIPSShowEquip\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($delete_button);

        $button_tray->addElement($cancel_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('id', $id));

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->display();
    }
}

function EQUIPSDeleteEquiptype($id)
{
    $xentParkEquiptype = new XentParkEquiptypes();

    $xentParkEquiptype->setIdEquiptype($id);

    $xentParkEquiptype->delete();
}

function EQUIPSEditEquip()
{
    $xentParkEquips = new XentParkEquips();

    $xentParkEquiptype = new XentParkEquiptypes();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = 0;
        }
    }

    if (0 != $id) {
        $equip = $xentParkEquips->getEquip($id);

        if (!empty($equip['ID_EQUIP'])) {
            $xentParkEquips->setIdEquip($equip['ID_EQUIP']);

            $xentParkEquips->setName($equip['name']);

            $xentParkEquips->setDesc($equip['description']);

            $xentParkEquips->setSerial($equip['serial']);

            $xentParkEquips->setIsRentable($equip['is_rentable']);

            $xentParkEquips->setIdTypeEquip($equip['id_type_equip']);

            $sform = new XoopsThemeForm(_AM_XENT_PARK_EDITEQUIP, 'editequip', xoops_getenv('PHP_SELF'));

            $sform->setExtra('enctype="multipart/form-data"');

            $sform->addElement(new XoopsFormText(_AM_XENT_PARK_NAME, 'name', 50, 255, $xentParkEquips->getName()));

            $sform->addElement(new XoopsFormTextArea(_AM_XENT_PARK_DESC, 'desc', $xentParkEquips->getDesc()));

            $sform->addElement(new XoopsFormText(_AM_XENT_PARK_SERIAL, 'serial', 50, 255, $xentParkEquips->getSerial()));

            $sform->addElement(makeSelect(_AM_XENT_PARK_ISRENTABLE, 'is_rentable', $xentParkEquips->getIsRentable(), makeNoYesArray(), 2, 0, false));

            $sform->addElement(makeSelect(_AM_XENT_PARK_EQUIPTYPE, 'equiptype', $xentParkEquips->getIdTypeEquip(), $xentParkEquiptype->getAllEquiptypesInArray(), 1, 0, false));

            $save_button = new XoopsFormButton('', 'add', _AM_XENT_MODIFY, 'submit');

            $save_button->setExtra("onmouseover='document.editequip.op.value=\"EQUIPSSaveEditEquip\"'");

            $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

            $cancel_button->setExtra("onmouseover='document.editequip.op.value=\"EQUIPSShowEquip\"'");

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement($save_button);

            $button_tray->addElement($cancel_button);

            $sform->addElement($button_tray);

            $sform->addElement(new XoopsFormHidden('op', ''));

            $sform->addElement(new XoopsFormHidden('id', $xentParkEquips->getIdEquip()));

            $sform->display();
        }
    }
}

function EQUIPSSaveEditEquip($id, $name, $desc, $serial, $is_rentable, $id_type_equip)
{
    $xentParkEquips = new XentParkEquips();

    $xentParkEquips->setIdEquip($id);

    $xentParkEquips->setName(str_replace("'", '’', $name));

    $xentParkEquips->setDesc(str_replace("'", '’', $desc));

    $xentParkEquips->setSerial(str_replace("'", '’', $serial));

    $xentParkEquips->setIsRentable($is_rentable);

    $xentParkEquips->setIdTypeEquip($id_type_equip);

    $xentParkEquips->update();
}

function EQUIPSAreYouSureToDeleteEquip($id)
{
    $xentParkEquips = new XentParkEquips();

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = 0;
    }

    $equip = $xentParkEquips->getEquip($id);

    if (!empty($equip['ID_EQUIP'])) {
        $xentParkEquips->setName($equip['name']);

        $sform = new XoopsThemeForm(_AM_XENT_PARK_AREYOUSUREDELETE, 'delequip', xoops_getenv('PHP_SELF'));

        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormLabel(_AM_XENT_PARK_NAME, $xentParkEquips->getName()));

        $delete_button = new XoopsFormButton('', 'add', _AM_XENT_DELETE, 'submit');

        $delete_button->setExtra("onmouseover='document.delequip.op.value=\"EQUIPSDeleteEquip\"'");

        $cancel_button = new XoopsFormButton('', 'add', _AM_XENT_CANCEL, 'submit');

        $cancel_button->setExtra("onmouseover='document.delequip.op.value=\"EQUIPSShowEquips\"'");

        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement($delete_button);

        $button_tray->addElement($cancel_button);

        $sform->addElement($button_tray);

        $sform->addElement(new XoopsFormHidden('id', $id));

        $sform->addElement(new XoopsFormHidden('op', ''));

        $sform->display();
    }
}

function EQUIPSDeleteEquip($id)
{
    $xentParkEquips = new XentParkEquips();

    $xentParkEquips->setIdEquip($id);

    $xentParkEquips->delete();
}

// ** NTS : À mettre à la fin de chaque fichier nécessitant plusieurs ops **

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
    case 'EQUIPSAddEquiptype':
        EQUIPSAddEquiptype();
        break;
    case 'EQUIPSSaveAddEquiptype':
        EQUIPSSaveAddEquiptype($name);
        break;
    case 'EQUIPSAddEquip':
        EQUIPSAddEquip();
        break;
    case 'EQUIPSSaveAddEquip':
        EQUIPSSaveAddEquip($name, $desc, $serial, $is_rentable, $id);
        break;
    case 'EQUIPSEditEquiptype':
        EQUIPSEditEquiptype();
        break;
    case 'EQUIPSSaveEditEquiptype':
        EQUIPSSaveEditEquiptype($id, $name);
        break;
    case 'EQUIPSAreYouSureToDeleteEquiptype':
        EQUIPSAreYouSureToDeleteEquiptype($id);
        break;
    case 'EQUIPSDeleteEquiptype':
        EQUIPSDeleteEquiptype($id);
        break;
    case 'EQUIPSEditEquip':
        EQUIPSEditEquip();
        break;
    case 'EQUIPSSaveEditEquip':
        EQUIPSSaveEditEquip($id, $name, $desc, $serial, $is_rentable, $equiptype);
        break;
    case 'EQUIPSAreYouSureToDeleteEquip':
        EQUIPSAreYouSureToDeleteEquip($id);
        break;
    case 'EQUIPSDeleteEquip':
        EQUIPSDeleteEquip($id);
        break;
    default:
        EQUIPSShowEquips();
        break;
}

// *************************** Fin de NTS **********************************

buildEquipsActionMenu();

#CloseTable();

xoops_cp_footer();
