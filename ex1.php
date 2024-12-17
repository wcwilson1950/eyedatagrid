<?php

/**
 * Instruction to run this sample:
 * 
 * 1) Create a MySQL database using sample_data.mysql script
 * 2) In config folder, create local.php from local.inc.php and
 * fill connection parameters according created database
 */

use Fgsl\Eyedatagrid\EyeDataFactory;
use Fgsl\Eyedatagrid\EyeSqlLiteAdap;
use Fgsl\Eyedatagrid\EyeDataGrid;

require 'vendor/autoload.php';

$x = EyeDataFactory::create(EyeDataFactory::SQLITE);

// Set the query
$x->setQuery("*", "people");
?>
<!doctype html>
<html>

<head>
    <title>EyeDataGrid Example 1</title>
    <link href="table.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1>Basic Datagrid</h1>
    <p>This is a basic example of the datagrid</p>
    <?php
    // Print the table
    $x->printTable();
    ?>
</body>

</html>