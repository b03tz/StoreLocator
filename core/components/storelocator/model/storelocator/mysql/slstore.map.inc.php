<?php
$xpdo_meta_map['slStore']= array (
  'package' => 'storelocator',
  'table' => 'storelocator_stores',
  'fields' => 
  array (
    'description' => '',
    'latitude' => 0,
    'longitude' => 0,
    'resource_id' => 0,
    'sort' => 0,
    'config' => '',
  ),
  'fieldMeta' => 
  array (
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '150',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'latitude' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '9,6',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'longitude' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '9,6',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'sort' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'config' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => false,
      'default' => '',
    ),
  ),
);
