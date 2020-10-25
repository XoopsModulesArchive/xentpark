<?php

class XentParkEquiptypes
{
    // class vars

    public $db;

    public $id_equiptype;

    public $name;

    // constructor

    public function __construct()
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
    }

    // setters

    public function setIdEquiptype($idequiptype)
    {
        $this->id_equiptype = $idequiptype;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // getters

    public function getIdEquiptype()
    {
        return $this->id_equiptype;
    }

    public function getName()
    {
        return $this->name;
    }

    // methods

    public function add()
    {
        global $module_tables;

        $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[4]) . " (name) VALUES('" . $this->getName() . "')";

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminequips.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminequips.php', 4, $this->db->error());
        }
    }

    public function delete()
    {
        global $module_tables;

        // ici on delete le type dans la table des types

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[4]) . ' WHERE ID_EQUIPTYPE=' . $this->getIdEquiptype();

        $this->db->queryF($sql);

        // on doit ensuite deleter dans la table link, les enregistrements contenant

        // tout les équipements du type a deleter

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]) . ' WHERE id_type_equip=' . $this->getIdEquiptype();

        $result = $this->db->query($sql);

        while (false !== ($equip = $this->db->fetchArray($result))) {
            $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[5]) . ' WHERE ID_EQUIP=' . $equip['ID_EQUIP'];

            $this->db->queryF($sql);
        }

        // on doit deleter les équips dans la table des équipements

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[1]) . ' WHERE id_type_equip=' . $this->getIdEquiptype();

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminequips.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminequips.php', 4, $this->db->error());
        }
    }

    public function getAllEquiptypes()
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[4]) . ' ORDER by name';

        $result = $this->db->query($sql);

        return $result;
    }

    public function getAllEquiptypesInArray($includeAllOption = false)
    {
        global $module_tables;

        $myts = MyTextSanitizer::getInstance();

        $arr = [];

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[4]) . ' ORDER BY name';

        $result = $this->db->query($sql);

        if (true === $includeAllOption) {
            $arr[0] = $myts->displayTarea(_AM_XENT_PARK_ALL);
        }

        while (false !== ($equiptype = $this->db->fetchArray($result))) {
            $arr[$equiptype['ID_EQUIPTYPE']] = $equiptype['name'];
        }

        return $arr;
    }

    public function getEquips()
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]) . ' WHERE id_type_equip=' . $this->getIdEquiptype();

        $result = $this->db->query($sql);

        return $result;
    }

    public function getEquiptype($id)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[4]) . " WHERE ID_EQUIPTYPE=$id";

        $result = $this->db->query($sql);

        $equiptype = $this->db->fetchArray($result);

        return $equiptype;
    }

    public function update()
    {
        global $module_tables;

        $sql = 'UPDATE ' . $this->db->prefix($module_tables[4]) . " SET name='" . $this->getName() . "' WHERE ID_EQUIPTYPE=" . $this->getIdEquiptype();

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminequips.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminequips.php', 4, $this->db->error());
        }
    }
}
