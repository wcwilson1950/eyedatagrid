<?php

namespace Fgsl\Eyedatagrid;

class EyeSQlLiteAdap implements EyeSQLAdaptorInterface
{
    private $db_path;
    private $link;
    private $result;

    const DATETIME = 'Y-m-d H:i:s';
    const DATE = 'Y-m-d';

    public function __construct($config)
    {
        $this->db_path = $config['db_path'];

        if ((isset($config['connect_now']) && $config['connect_now'] == true) || !isset($config['connect_now'])) {
            $persistent = (isset($config['persistent']) && $config['persistent'] == true) ? true : false;
            $this->connect($persistent);
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    public function connect($persist = true)
    {
        $this->link = new \SQLite3($this->db_path);
        return $this->link ? true : false;
    }

    public function query($query)
    {
        $this->result = $this->link->query($query);
        if ($this->result === false) {
            trigger_error('Uncovered an error in your SQL query script: "' . $this->error() . '"');
        }
        return $this->result;
    }

    public function update(array $values, $table, $where = false, $limit = false)
    {
        if (count($values) < 0) {
            return false;
        }

        $fields = array();
        foreach ($values as $field => $val) {
            $fields[] = "`" . $field . "` = '" . $this->escapeString($val) . "'";
        }

        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        return $this->query("UPDATE `" . $table . "` SET " . implode(", ", $fields) . $where . $limit);
    }

    public function insert(array $values, $table)
    {
        if (count($values) < 0) {
            return false;
        }

        foreach ($values as $field => $val) {
            $values[$field] = $this->escapeString($val);
        }

        return $this->query("INSERT INTO `" . $table . "`(`" . implode("`, `", array_keys($values)) . "`) VALUES ('" . implode("', '", $values) . "')");
    }

    public function select($fields, $table, $where = false, $orderby = false, $limit = false)
    {
        if (is_array($fields)) {
            $fields = "`" . implode("`, `", $fields) . "`";
        }

        $orderby = ($orderby) ? " ORDER BY " . $orderby : '';
        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        $this->query("SELECT " . $fields . " FROM " . $table . $where . $orderby . $limit);

        if ($this->countRows() > 0) {
            $rows = array();
            while ($r = $this->fetchAssoc()) {
                $rows[] = $r;
            }
            return $rows;
        } else {
            return false;
        }
    }

    public function selectOne($fields, $table, $where = false, $orderby = false)
    {
        $result = $this->select($fields, $table, $where, $orderby, '1');
        return $result[0];
    }

    public function selectOneValue($field, $table, $where = false, $orderby = false)
    {
        $result = $this->selectOne($field, $table, $where, $orderby);
        return $result[$field];
    }

    public function delete($table, $where = false, $limit = 1)
    {
        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        return $this->query("DELETE FROM `" . $table . "`" . $where . $limit);
    }

    public function fetchAssoc($query = false)
    {
        $result = $this->resCalc($query);
        return $result->fetchArray(SQLITE3_ASSOC);
    }

    public function fetchRow($query = false)
    {
        $result = $this->resCalc($query);
        return $result->fetchArray(SQLITE3_NUM);
    }

    public function fetchOne($query = false)
    {
        $row = $this->fetchRow($query);
        return $row[0];
    }

    public function fieldName($query = false, $offset = 0)
    {
        $result = $this->resCalc($query);
        $columns = $result->numColumns();
        if ($offset < $columns) {
            return $result->columnName($offset);
        }
        return null;
    }

    public function fieldNameArray($query = false)
    {
        $names = [];
        $result = $this->resCalc($query);
        $columns = $result->numColumns();
        for ($i = 0; $i < $columns; $i++) {
            $names[] = $result->columnName($i);
        }
        return $names;
    }

    public function freeResult()
    {
        $this->result->finalize();
        return true;
    }

    public function escapeString($str)
    {
        return $this->link->escapeString($str);
    }

    public function countRows($result = false)
    {
        $result = $this->resCalc($result);
        $count = 0;
        while ($result->fetchArray()) {
            $count++;
        }
        return $count;
    }

    public function countFields($result = false)
    {
        $result = $this->resCalc($result);
        return $result->numColumns();
    }

    public function insertId()
    {
        return $this->link->lastInsertRowID();
    }

    public function affectedRows()
    {
        return $this->link->changes();
    }

    public function error()
    {
        return $this->link->lastErrorMsg();
    }

    public function dumpInfo()
    {
        echo $this->link->version();
    }

    public function close()
    {
        return $this->link->close();
    }

    private function resCalc($result)
    {
        if ($result == false) {
            $result = $this->result;
        } else if (!$result instanceof \SQLite3Result) {
            $result = $this->query($result);
        }
        return $result;
    }
}