<?php

require_once XOOPS_ROOT_PATH . '/modules/xentgen/class/xent_users.php';
require_once XOOPS_ROOT_PATH . '/modules/xentpark/class/xent_park_sites.php';
require_once XOOPS_ROOT_PATH . '/modules/xentpark/class/xent_park_equips.php';

class XentParkWorkstations
{
    // class vars

    public $db;

    public $desc;

    public $idowner;

    public $idsite;

    public $idtype;

    public $idworkstation;

    public $name;

    // constructor

    public function __construct()
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
    }

    // setters

    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    public function setIdOwner($idowner)
    {
        $this->idowner = $idowner;
    }

    public function setIdSite($idsite)
    {
        $this->idsite = $idsite;
    }

    public function setIdType($idtype)
    {
        $this->idtype = $idtype;
    }

    public function setIdWorkstation($id)
    {
        $this->idworkstation = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // getters

    public function getDesc()
    {
        return $this->desc;
    }

    public function getIdOwner()
    {
        return $this->idowner;
    }

    public function getIdSite()
    {
        return $this->idsite;
    }

    public function getIdType()
    {
        return $this->idtype;
    }

    public function getIdWorkstation()
    {
        return $this->idworkstation;
    }

    public function getName()
    {
        return $this->name;
    }

    // methods

    public function add()
    {
        global $module_tables;

        $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[0]) . " (name, description, id_owner, id_type, id_site) VALUES('" . $this->getName() . "', '" . $this->getDesc() . "', " . $this->getIdOwner() . ', ' . $this->getIdType() . ', ' . $this->getIdSite() . ')';

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminworkstations.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminworkstations.php', 4, $this->db->error());
        }
    }

    public function addApps($idapps)
    {
        global $module_tables;

        $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[7]) . ' (ID_WORKSTATION, ID_APPS) VALUES(' . $this->getIdWorkstation() . ", $idapps)";

        $this->db->queryF($sql);
    }

    public function addEquips($idequip)
    {
        global $module_tables;

        $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[5]) . ' (ID_WORKSTATION, ID_EQUIP) VALUES(' . $this->getIdWorkstation() . ", $idequip)";

        $this->db->queryF($sql);
    }

    public function cloneWS()
    {
        global $module_tables;

        // clone dans la table workstations

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . ' WHERE ID_WORKSTATION=' . $this->getIdWorkstation();

        $result = $this->db->query($sql);

        while (false !== ($ws = $this->db->fetchArray($result))) {
            $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[0]) . " (name, description, id_owner, id_type, id_site) VALUES ('" . _AM_XENT_PARK_CLONENAME . "', '" . $ws['description'] . "', " . $ws['id_owner'] . ', ' . $ws['id_type'] . ', ' . $ws['id_site'] . ')';

            $this->db->queryF($sql);
        }

        // on doit aller chercher l'id de la machine clonée

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . ' ORDER BY ID_WORKSTATION DESC LIMIT 1';

        $result = $this->db->query($sql);

        $cloned_ws = $this->db->fetchArray($result);

        // clone dans la table link

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[5]) . ' WHERE ID_WORKSTATION=' . $this->getIdWorkstation();

        $result = $this->db->query($sql);

        while (false !== ($link = $this->db->fetchArray($result))) {
            $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[5]) . ' (ID_WORKSTATION, ID_EQUIP) VALUES (' . $cloned_ws['ID_WORKSTATION'] . ', ' . $link['ID_EQUIP'] . ')';

            $this->db->queryF($sql);
        }

        if (0 == $this->db->errno()) {
            redirect_header('adminworkstations.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminworkstations.php', 4, $this->db->error());
        }
    }

    public function delete()
    {
        global $module_tables;

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[0]) . ' WHERE ID_WORKSTATION=' . $this->getIdWorkstation();

        $this->db->queryF($sql);

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[5]) . ' WHERE ID_WORKSTATION=' . $this->getIdWorkstation();

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminworkstations.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminworkstations.php', 4, $this->db->error());
        }
    }

    public function deleteApps($idapps)
    {
        global $module_tables;

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[7]) . " WHERE ID_APPS=$idapps AND ID_WORKSTATION=" . $this->getIdWorkstation();

        $this->db->queryF($sql);
    }

    public function deleteEquips($idequip)
    {
        global $module_tables;

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[5]) . " WHERE ID_EQUIP=$idequip AND ID_WORKSTATION=" . $this->getIdWorkstation();

        $this->db->queryF($sql);
    }

    public function getAllWorkstations()
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . ' ORDER BY id_type, name';

        $result = $this->db->query($sql);

        return $result;
    }

    public function getApps()
    {
        global $module_tables;

        $xentParkApps = new XentParkApps();

        $arr = [];

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[8]) . ' as t1, ' . $this->db->prefix($module_tables[7]) . ' as t2 WHERE ID_WORKSTATION=' . $this->getIdWorkstation() . ' AND t1.ID_APPS=t2.ID_APPS ORDER BY name';

        $result = $this->db->query($sql);

        while (false !== ($link_wa = $this->db->fetchArray($result))) {
            $apps = $xentParkApps->getApps($link_wa['ID_APPS']);

            $arr[$apps['ID_APPS']] = $apps['name'];
        }

        return $arr;
    }

    public function getEquips()
    {
        global $module_tables;

        $xentParkEquips = new XentParkEquips();

        $arr = [];

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[5]) . ' WHERE ID_WORKSTATION=' . $this->getIdWorkstation();

        $result = $this->db->query($sql);

        while (false !== ($link_we = $this->db->fetchArray($result))) {
            $equip = $xentParkEquips->getEquip($link_we['ID_EQUIP']);

            $arr[$equip['ID_EQUIP']] = $equip['name'];
        }

        return $arr;
    }

    public function getNameById($id)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . " WHERE ID_WORKSTATION=$id";

        $result = $this->db->query($sql);

        $ws = $this->db->fetchArray($result);

        return $ws['name'];
    }

    public function getOwner()
    {
        $xentUsers = new XentUsers();

        $user = $xentUsers->getUser($this->getIdOwner());

        return $user['name'];
    }

    public function getSite()
    {
        $xentParkSites = new XentParkSites();

        $site = $xentParkSites->getSite($this->getIdSite());

        return $site['name'];
    }

    public function getWorkstation($id)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[0]) . " WHERE ID_WORKSTATION=$id";

        $result = $this->db->query($sql);

        $ws = $this->db->fetchArray($result);

        return $ws;
    }

    public function getWorkstationTypes()
    {
        $arr = [];

        $arr[0] = _AM_XENT_PARK_DESKTOP;

        $arr[1] = _AM_XENT_PARK_LAPTOP;

        return $arr;
    }

    public function update()
    {
        global $module_tables;

        $sql = 'UPDATE '
               . $this->db->prefix($module_tables[0])
               . " SET name='"
               . $this->getName()
               . "', description='"
               . $this->getDesc()
               . "', id_owner="
               . $this->getIdOwner()
               . ', id_type='
               . $this->getIdType()
               . ', id_site='
               . $this->getIdSite()
               . ' WHERE ID_WORKSTATION='
               . $this->getIdWorkstation();

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminworkstations.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminworkstations.php', 4, $this->db->error());
        }
    }
}
