<?php array (
  'global' => 
  array (
    'storyline' => 
    array (
      'universe' => 'legacies',
      'episode' => 'default',
    ),
    'web' => 
    array (
      'base_url' => 'http://localhost/projects/wootook-1.5/',
    ),
    'date' => 
    array (
      'timezone' => 'Europe/Paris',
    ),
    'layout' => 
    array (
      'page' => 'page.php',
      'admin' => 'admin.php',
      'empire' => 'empire.php',
    ),
    'locales' => 
    array (
      'fr' => 'fr_FR',
      'fr_FR' => 'fr_FR',
      'en' => 'en_US',
      'en_US' => 'en_US',
    ),
    'database' => 
    array (
      'default' => 
      array (
        'engine' => 'mysql',
        'options' => 
        array (
        ),
        'params' => 
        array (
          'hostname' => 'localhost',
          'username' => 'root',
          'password' => '%J4mzdo69$',
          'database' => 'db_xnova',
          'port' => '3306',
        ),
        'table_prefix' => 'wtk_',
      ),
      'core_setup' => 
      array (
        'use' => 'default',
      ),
      'core_read' => 
      array (
        'use' => 'default',
      ),
      'core_write' => 
      array (
        'use' => 'default',
      ),
    ),
  ),
  'default' => 
  array (
    'engine' => 
    array (
      'core' => 
      array (
        'use_large_numbers' => false,
      ),
      'universe' => 
      array (
        'galaxies' => NULL,
        'systems' => NULL,
        'positions' => NULL,
      ),
      'combat' => 
      array (
        'allow_spy_drone_attacks' => false,
      ),
    ),
  ),
);