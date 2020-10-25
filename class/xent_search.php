<?php

class XentSearch
{
    // class vars

    public $array_results = [];

    public $array_sections = [];

    public $count_array_sections = 0;

    public $db;

    public $search_string;

    // constructor

    public function __construct()
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
    }

    public function setArrayResult($arr, $index)
    {
        $this->array_results[$index] = $arr;
    }

    public function setArraySection($arr, $index)
    {
        $this->array_sections[$index] = $arr;
    }

    public function setCountArraySections($count)
    {
        $this->count_array_sections = $count;
    }

    public function setSearchString($str)
    {
        $this->search_string = $str;
    }

    // getters

    public function getArrayResult()
    {
        return $this->array_results;
    }

    public function getArraySection()
    {
        return $this->array_sections;
    }

    public function getCountArraySections()
    {
        return $this->count_array_sections;
    }

    public function getSearchString()
    {
        return $this->search_string;
    }

    // methods

    // watch out : $link_param NE peut pas être un array

    // le $link_param doit évidemment être un string correspondante au paramètre dans le link

    public function addSection($name, $caption, $db_table, $db_table_field, $link, $link_param, $link_param_db)
    {
        $arr = [];

        $arr['name'] = $name;

        $arr['caption'] = $caption;

        $arr['table'] = $db_table;

        $arr['field'] = $db_table_field;

        $arr['link'] = $link;

        $arr['link_param'] = $link_param;

        $arr['link_param_db'] = $link_param_db;

        $arr['result'] = [];

        $this->setArraySection($arr, $name);
    }

    public function display()
    {
        echo "<div class='adminHeader'>" . _AM_XENT_PARK_SEARCHRESULT . '</div><br><br>';

        echo '<b>' . _AM_XENT_PARK_SEARCHWORDS . $this->getSearchString() . '</b><br><br>';

        foreach ($this->getArraySection() as $arr) {
            echo '<u>' . $arr['caption'] . '</u><br>';

            $a = $arr['result'];

            if (!empty($a)) {
                echo '<br><li>';

                foreach ($a as $theresult) {
                    echo $theresult . '<br>';
                }

                echo '</li>';
            } else {
                echo '<br>' . _AM_XENT_PARK_SEARCHNORESULT . '<br>';
            }

            echo '<br><br>';
        }

        echo '<br>';
    }

    public function execute($str)
    {
        $array_search_words = [];

        $count = 0;

        $this->setSearchString($str);

        // on sépare la string à chercher dans un array (séparation à chaque espace)

        // si tu veux ajouter des opérateurs de recherche (ex : "machine clonée"+12) ... c'est dans

        // cette boucle que ça se passe.

        while ('' != $this->getSearchString()) {
            $pos = mb_strpos($this->getSearchString(), ' ');

            $array_search_words[$count] = mb_substr($this->getSearchString(), 0, $pos);

            if (empty($pos)) {
                $array_search_words[$count] = $this->getSearchString();

                break;
            }

            $this->setSearchString(mb_substr($this->getSearchString(), mb_strpos($this->getSearchString(), ' ') + 1));

            $count++;
        }

        foreach ($this->getArraySection() as $arr_sections) {
            // batir la query sql pour chaque section

            $sql = 'SELECT * FROM ' . $this->db->prefix($arr_sections['table']) . ' WHERE ';

            $tmp_count = 0;

            foreach ($array_search_words as $value) {
                if ($tmp_count != $count) {
                    $sql .= ' INSTR(' . $arr_sections['field'] . ", '" . $value . "') AND ";
                } else {
                    $sql .= ' INSTR(' . $arr_sections['field'] . ", '" . $value . "')";
                }

                $tmp_count++;
            }

            // exécute la query sql

            $result = $this->db->query($sql);

            // on highlight les mots trouvés en rouge

            $i = 0;

            $arr = [];

            while (false !== ($search_records = $this->db->fetchArray($result))) {
                $arr[$i] = "<a href='" . $arr_sections['link'] . '&' . $arr_sections['link_param'] . '=' . $search_records[$arr_sections['link_param_db']] . "'>" . $this->highlightSearchWords($search_records[$arr_sections['field']], $array_search_words) . '</a>';

                $i++;
            }

            // on set le array des résultats pour la section

            $arr_sections['result'] = $arr;

            $this->setArraySection($arr_sections, $arr_sections['name']);
        }
    }

    public function highlightSearchWords($haystack, $needle)
    {
        for ($x = 0, $xMax = count($needle); $x < $xMax; $x++) {
            $result = '';

            while (instr($haystack, $needle[$x], 1)) {
                $i = mb_strpos(mb_strtolower($haystack), mb_strtolower($needle[$x]));

                $result .= mb_substr($haystack, 0, $i) . "<font color='#FF0000'>" . mb_substr($haystack, $i, mb_strlen($needle[$x])) . '</font>';

                $haystack = mb_substr($haystack, $i + mb_strlen($needle[$x]));
            }

            $result .= $haystack;

            $haystack = $result;
        }

        return $result;
    }
}
