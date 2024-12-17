<?php

namespace Fgsl\Eyedatagrid;

interface EyeSQLAdaptorInterface
{
    const DATETIME = 'Y-m-d H:i:s';
    const DATE = 'Y-m-d';

    public function __construct($config);
    public function __destruct();
    public function connect($persist = true);
    public function query($query);
    public function update(array $values, $table, $where = false, $limit = false);
    public function insert(array $values, $table);
    public function select($fields, $table, $where = false, $orderby = false, $limit = false);
    public function selectOne($fields, $table, $where = false, $orderby = false);
    public function selectOneValue($field, $table, $where = false, $orderby = false);
    public function delete($table, $where = false, $limit = 1);
    public function fetchAssoc($query = false);
    public function fetchRow($query = false);
    public function fetchOne($query = false);
    public function fieldName($query = false, $offset = 0);
    public function fieldNameArray($query = false);
    public function freeResult();
    public function escapeString($str);
    public function countRows($result = false);
    public function countFields($result = false);
    public function insertId();
    public function affectedRows();
    public function error();
    public function dumpInfo();
    public function close();
}