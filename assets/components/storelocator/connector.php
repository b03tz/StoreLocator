<?php
/**
 * StoreLocator
 *
 * Copyright 2010-11 by Patrick Nijkamp <patrick@scherpontwikkeling.nl>
 *
 * This file is part of StoreLocator.
 *
 * StoreLocator is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * StoreLocator is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * StoreLocator; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package StoreLocator
 */
/**
 * StoreLocator Connector
 *
 * @package StoreLocator
 */
if (is_file(dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php')) {
	require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
} else {
	require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/config.core.php';
} 

require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$storeLocatorCorePath = $modx->getOption('storelocator.core_path',null,$modx->getOption('core_path').'components/storelocator/');
require_once $storeLocatorCorePath.'model/storelocator/storelocator.class.php';
$modx->storelocator = new StoreLocator($modx);

$modx->lexicon->load('storelocator:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->storelocator->config,$storeLocatorCorePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));