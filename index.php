<?php

use RequestHandler\Utils\Collection\{
    ArrayList\ArrayList,
    Hash\Hash,
    Hash\IHash
};

require_once __DIR__ . '/vendor/autoload.php';

$list = new ArrayList();

$list[] = 'test';
$list[] = 'coa';

unset($list[0]);
$list[] = 'asd';

var_dump($list[0]);
