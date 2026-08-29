<?php
$r = new ReflectionClass('PDO');
foreach ($r->getConstants() as $k => $v) {
    if (str_starts_with($k, 'MYSQL_ATTR_')) {
        echo "$k => $v\n";
    }
}