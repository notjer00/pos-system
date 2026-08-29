<?php
// Check what PDO MySQL driver is actually being used
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=test', 'test', 'test');
    echo "Driver: " . $db->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "Client version: " . $db->getAttribute(PDO::ATTR_CLIENT_VERSION) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}