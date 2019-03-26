<?php

define('DBDRIVER','mysql');
define('DBHOST', 'localhost');
define('DBNAME', 'hashrepo');
define('DBUSER', 'root');
define('DBPASS', '');
define('SYSTEM_GLOBAL_SALT', 'aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d');
define('DEV_MODE', false);
define('SYSTEM_CLIENTS', [
  '78d06ef5049f1567ae66a495a199f5e5' => ['deviceType' => 'Mobile', 'SystemName' => 'Android'],
  'a' => ['deviceType' => 'Desktop', 'SystemName' => 'Windows']
]);

define('TABLE_USERS_NAME','hashrepo_users');
define('TABLE_DEVICES_NAME','hashrepo_devices');
define('TABLE_ACTIVATORS_CODE_NAME','hashrepo_activators');
define('TABLE_ACTIVATORS_CODES_ACCOUNT_NAME','hashrepo_accounts_activators');