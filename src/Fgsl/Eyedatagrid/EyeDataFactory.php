<?php

namespace Fgsl\Eyedatagrid;

class EyeDataFactory
{
    const SQLITE = 'sqlite';
    const MYSQL = 'mysql';

    public static function create($type)
    {

        switch ($type) {
            case self::SQLITE:

                $db = self::createSqlite();
                break;
            case self::MYSQL:
                $db = self::createMysql();
                break;
            default:
                throw new \Exception('Invalid database type');
        }
        return new EyeDataGrid($db);
    }
    private static function createMysql()
    {
        $config = require 'config/mysql.local.php';
        return new EyeMysqlAdap($config);
    }
    private static function createSqlite()
    {
        $config = require 'config/sqlite.local.php';
        return new EyeSqlLiteAdap($config);
    }
}