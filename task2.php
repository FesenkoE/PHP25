<?php
/*
    Используя рекурсию, вывести в виде дерева структуру массива.
*/

$fs = [
    'html' => [
        'api' => [
            'controllers',
            'models',
            'views',
            'modules'
        ],
        'application' => [
            'controllers',
            'models',
            'views',
            'modules'
        ],
        'runtime' => [
            'logs',
            'cache'
        ],
        'data' => [
            'dumps',
            'images',
            'files'
        ],
        'config',
        'migrations'
    ]
];



function prinTree($items, $item_parent = 0)
{
    $print = "";
    foreach ($items as $item => $key ){
        $print .= str_repeat("  ", $item_parent);
        if (is_array($key)){
            $print .= "$item\n";
            $print .= prinTree($key, $item_parent + 2);
        }else{
            $print .= "$key\n";
        }
    }
    return $print;
}
echo prinTree($fs);