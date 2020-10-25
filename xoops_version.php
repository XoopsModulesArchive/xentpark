<?php
// ------------------------------------------------------------------------- //
//                  Module xentPark pour Xoops 2.0.7                     //
//                              Version:  1.0                                //
// ------------------------------------------------------------------------- //
// Author: Milhouse                                        				     //
// Purpose:                           				     //
// email: hotkart@hotmail.com                                                //
// URLs:                      												 //
//---------------------------------------------------------------------------//
global $xoopsModuleConfig;
$modversion['name'] = _MI_XENT_PARK_NAME;
$modversion['version'] = '1.0';
$modversion['description'] = _MI_XENT_PARK_DESC;
$modversion['credits'] = 'Tx to M4D3l, marcan and the ones i forgot';
$modversion['author'] = 'Ecrit pour Xoops2<br>par Alexandre Parent (Milhouse)';
$modversion['license'] = '';
$modversion['official'] = 1;
$modversion['image'] = 'images/xent_park_logo.png';
$modversion['help'] = '';
$modversion['dirname'] = 'xentpark';

// MYSQL FILE
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file
//If you hack this modules, dont change the order of the table.
//All
$modversion['tables'][0] = 'xent_park_workstations';
$modversion['tables'][1] = 'xent_park_equips';
$modversion['tables'][2] = 'xent_park_rents';
$modversion['tables'][3] = 'xent_park_sites';
$modversion['tables'][4] = 'xent_park_equiptypes';
$modversion['tables'][5] = 'xent_park_link_workstation_equips';
$modversion['tables'][6] = 'xent_park_link_workstation_drivers';
$modversion['tables'][7] = 'xent_park_link_workstation_apps';
$modversion['tables'][8] = 'xent_park_apps';

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/adminworkstations.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 1;
