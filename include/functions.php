<?php

require_once XOOPS_ROOT_PATH . '/modules/xentgen/include/xentfunctions.php';

function buildWSActionMenu()
{
    echo "<div class='adminActionMenu'><a href=adminworkstations.php class='adminActionMenu'>" . _AM_XENT_PARK_ADMINWSTITLE . '</a></div>';
}

function buildEquipsActionMenu()
{
    echo "<div class='adminActionMenu'><a href=adminequips.php class='adminActionMenu'>" . _AM_XENT_PARK_ADMINEQUIPSTITLE . '</a></div>';
}
