<?php

namespace Fgsl\Eyedatagrid;

use Fgsl\Eyedatagrid\EyeMysqlAdap;
use Fgsl\Eyedatagrid\EyeSqlLiteAdap;
use Fgsl\Eyedatagrid\EyeDataGrid;

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
    public static function useAjaxTable($url, $type = self::SQLITE)
    {
        $x = self::create($type);
        $x->useAjaxTable($url);
    }
    public static function build(array $gridConfig)
    {
        $grid = self::create($gridConfig['type']);
        list($fields, $table, $where) = array_values($gridConfig['query']);
        $grid->setQuery($fields, $table, $where);

        foreach ($gridConfig['columns'] as $field => $settings) {
            $grid->setColumn($field, $settings);
        }

        return $grid;
    }
}