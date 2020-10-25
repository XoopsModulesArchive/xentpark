<?php

class XentParkSites
{
    // class vars

    public $db;

    public $desc;

    public $idsite;

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

    public function setIdSite($idsite)
    {
        $this->idsite = $idsite;
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

    public function getIdSite()
    {
        return $this->idsite;
    }

    public function getName()
    {
        return $this->name;
    }

    // methods

    public function add()
    {
    }

    public function delete($id)
    {
    }

    public function getAllSites()
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[3]);

        $result = $this->db->query($sql);

        return $result;
    }

    public function getAllSitesInArray()
    {
        $arr = [];

        $result = $this->getAllSites();

        while (false !== ($site = $this->db->fetchArray($result))) {
            $arr[$site['ID_SITE']] = $site['name'];
        }

        return $arr;
    }

    public function getSite($id)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[3]) . " WHERE ID_SITE=$id";

        $result = $this->db->query($sql);

        $site = $this->db->fetchArray($result);

        return $site;
    }

    public function update()
    {
    }
}
