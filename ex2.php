<?php

use Fgsl\Eyedatagrid\EyeDataFactory;
use Fgsl\Eyedatagrid\EyeDataGrid;

require 'vendor/autoload.php';
$gridConfig = [
    'type' => EyeDataFactory::SQLITE,
    'query' => [
        'fields' => '*',
        'table' => 'people',
        'where' => 'Id > 2'
    ],
    'filters' => true,
    'columns' => [
        'FirstName' => 'First Name',
        'LastName' => 'Last Name',
        'Id' => false, // Hide ID Column
        'BirthDate' => [
            'header' => 'Birth Date',
            'type' => EyeDataGrid::TYPE_DATE,
            'format' => 'M d, Y',
            'filter' => true
        ],
        'Gender' => [
            'type' => EyeDataGrid::TYPE_ARRAY,
            'values' => ['m' => 'Male', 'f' => 'Female']
        ],
        'Done' => [
            'type' => EyeDataGrid::TYPE_PERCENT,
            'filter' => false,
            'colors' => ['Back' => '#c3daf9', 'Fore' => 'black']
        ]
    ]
];

$x = EyeDataFactory::build($gridConfig);

// // Set the query
// $x->setQuery("*", "people", "Id", "Id > 2");

// // Allows filters
// $x->allowFilters();

// // Change headers text
// $x->setColumnHeader('FirstName', 'First Name');
// $x->setColumnHeader('LastName', 'Last Name');

// // Hide ID Column
// $x->hideColumn('Id');

// // Change column type
// $x->setColumnType('BirthDate', EyeDataGrid::TYPE_DATE, 'M d, Y', true); // Change the date format
// $x->setColumnType('Gender', EyeDataGrid::TYPE_ARRAY, array('m' => 'Male', 'f' => 'Female')); // Convert db values to something better
// $x->setColumnType('Done', EyeDataGrid::TYPE_PERCENT, false, array('Back' => '#c3daf9', 'Fore' => 'black'));
?>
<html>

<head>
    <title>EyeDataGrid Example 2</title>
    <link href="table.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1>Filters, Headers Text Changed, Hidden, and Type Applyed</h1>
    <p>From the previous example a few things have changed:</p>
    <ul>
        <li>The column text for the first and last name was changed to add a space</li>
        <li>The ability to filter results by header was added (columns with column type changed does not allow
            filtering, eg: birthdate, gender)</li>
        <li>The ID column was hidden and we have a custom where condition specified</li>
        <li>The done column is set to a percent type</li>
        <li>The column type for birthdate was changed to format the date cleaner.</li>
        <li>The gender column type was changed to a more readable value using array mapping 'f' => 'Female'</li>
    </ul>
    <?php
    // Print the table
    $x->printTable();
    ?>
</body>

</html>