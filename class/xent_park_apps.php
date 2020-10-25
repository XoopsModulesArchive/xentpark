<?php

class XentParkApps
{
    public $db;

    public $id_apps;

    public $name;

    public function __construct()
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
    }

    public function setIdApps($id)
    {
        $this->id_apps = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getIdApps()
    {
        return $this->id_apps;
    }

    public function getName()
    {
        return $this->name;
    }

    public function add()
    {
        global $module_tables;

        $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[8]) . ' (name) VALUES(' . $this->getName() . ')';

        $this->db->query($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminapps.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminapps.php', 4, $this->db->error());
        }
    }

    public function delete()
    {
        global $module_tables;

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[8]) . ' WHERE ID_APPS=' . $this->getIdApps();

        $this->db->query($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminapps.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminapps.php', 4, $this->db->error());
        }
    }

    public function associateToWs($idapps, $id_ws)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[7]) . " WHERE ID_APPS=$idapps AND ID_WORKSTATION=$id_ws";

        $result = $this->db->query($sql);

        if (0 == $this->db->getRowsNum($result)) {
            return false;
        }
  

        return true;
    }

    public function getAllAppsArray()
    {
        global $module_tables;

        $arr = [];

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[8]) . ' ORDER BY name';

        $result = $this->db->query($sql);

        while (false !== ($apps = $this->db->fetchArray($result))) {
            $arr[$apps['ID_APPS']] = $apps['name'];
        }

        return $arr;
    }

    public function getApps($id)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[8]) . " WHERE ID_APPS=$id";

        $result = $this->db->query($sql);

        $apps = $this->db->fetchArray($result);

        return $apps;
    }

    public function update()
    {
    }
}
