<?php

class XentParkEquips
{
    // class vars

    public $db;

    public $desc;

    public $id_equip;

    public $id_type_equip;

    public $is_rentable;

    public $name;

    public $serial;

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

    public function setIdEquip($idequip)
    {
        $this->id_equip = $idequip;
    }

    public function setIdTypeEquip($idtypeequip)
    {
        $this->id_type_equip = $idtypeequip;
    }

    public function setIsRentable($bool)
    {
        $this->is_rentable = $bool;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    // getters

    public function getDesc()
    {
        return $this->desc;
    }

    public function getIdEquip()
    {
        return $this->id_equip;
    }

    public function getIdTypeEquip()
    {
        return $this->id_type_equip;
    }

    public function getIsRentable()
    {
        return $this->is_rentable;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    // methods

    public function add()
    {
        global $module_tables;

        $sql = 'INSERT INTO ' . $this->db->prefix($module_tables[1]) . " (name, description, serial, is_rentable, id_type_equip) VALUES ('" . $this->getName() . "', '" . $this->getDesc() . "', '" . $this->getSerial() . "', " . $this->getIsRentable() . ', ' . $this->getIdTypeEquip() . ')';

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminequips.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminequips.php', 4, $this->db->error());
        }
    }

    public function associateToWs($idequip)
    {
        global $module_tables;

        $sql = 'SELECT ID_EQUIP FROM ' . $this->db->prefix($module_tables[5]) . " WHERE ID_EQUIP=$idequip";

        $result = $this->db->query($sql);

        if (0 == $this->db->getRowsNum($result)) {
            return false;
        }
  

        return true;
    }

    public function delete()
    {
        global $module_tables;

        $sql = 'DELETE FROM ' . $this->db->prefix($module_tables[1]) . ' WHERE ID_EQUIP=' . $this->getIdEquip();

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminequips.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminequips.php', 4, $this->db->error());
        }
    }

    public function getAllEquipsInArray()
    {
        global $module_tables;

        $arr = [];

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]);

        $result = $this->db->query($sql);

        while (false !== ($equip = $this->db->fetchArray($result))) {
            $arr[$equip['ID_EQUIP']] = $equip['name'];
        }

        return $arr;
    }

    public function getAllEquipsInStockInArray()
    {
        global $module_tables;

        $arr = [];

        if (0 == $this->getIdTypeEquip()) {
            // si c'est égal a 0, c'est parce que l'option "Tout" a été choisi

            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]);
        } else {
            $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]) . ' WHERE id_type_equip=' . $this->getIdTypeEquip();
        }

        $result = $this->db->query($sql);

        while (false !== ($equip = $this->db->fetchArray($result))) {
            if (true === $this->inStock($equip['ID_EQUIP'])) {
                $arr[$equip['ID_EQUIP']] = $equip['name'];
            }
        }

        return $arr;
    }

    public function getEquip($id)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[1]) . " WHERE ID_EQUIP=$id";

        $result = $this->db->query($sql);

        $equip = $this->db->fetchArray($result);

        return $equip;
    }

    public function getEquipWs($id)
    {
        global $module_tables;

        $xentParkWorkstations = new XentParkWorkstations();

        $arr = [];

        $count = 0;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[5]) . " WHERE ID_EQUIP=$id";

        $result = $this->db->query($sql);

        while (false !== ($link = $this->db->fetchArray($result))) {
            $arr[$count] = $xentParkWorkstations->getNameById($link['ID_WORKSTATION']);

            $count++;
        }

        return $arr;
    }

    public function inStock($idequip)
    {
        if (false === $this->associateToWs($idequip) && false === $this->isRent($idequip)) {
            return true;
        }
  

        return false;
    }

    public function isRent($idequip)
    {
        global $module_tables;

        $sql = 'SELECT * FROM ' . $this->db->prefix($module_tables[2]) . " WHERE id_equip=$idequip";

        $result = $this->db->query($sql);

        $rent = $this->db->fetchArray($result);

        if (empty($rent['ID_RENT'])) {
            return false;
        }
  

        return true;
    }

    public function update()
    {
        global $module_tables;

        $sql = 'UPDATE '
               . $this->db->prefix($module_tables[1])
               . " SET name='"
               . $this->getName()
               . "', description='"
               . $this->getDesc()
               . "', serial='"
               . $this->getSerial()
               . "', is_rentable="
               . $this->getIsRentable()
               . ', id_type_equip='
               . $this->getIdTypeEquip()
               . ' WHERE ID_EQUIP='
               . $this->getIdEquip();

        $this->db->queryF($sql);

        if (0 == $this->db->errno()) {
            redirect_header('adminequips.php', 1, _AM_XENT_DBUPDATED);
        } else {
            redirect_header('adminequips.php', 4, $this->db->error());
        }
    }
}
