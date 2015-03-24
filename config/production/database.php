<?php
$url = getenv("CLEARDB_DATABASE_URL");
if (!$url) die("error!");

$url_parts = parse_url(getenv("CLEARDB_DATABASE_URL"));
return [
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host'      => $url_parts["host"],
            'database'  => substr($url_parts["path"], 1),
            'username'  => $url_parts["user"],
            'password'  => $url_parts["pass"],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],
    ],
];

