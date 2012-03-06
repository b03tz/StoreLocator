<?php
/**
 * StoreLocator
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
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
 
if (!$modx->user->isAuthenticated('mgr')) return $modx->error->failure($modx->lexicon('permission_denied'));

$id = (int) $_REQUEST['id'];
$storeData = json_decode($_REQUEST['storeConfig'], true);

if ($id == 0) {
	// Create a new store
	$store = $modx->newObject('slStore');
	
	// Get the highest ordering store
	$query = $modx->newQuery('slStore');
	$query->limit(1);
	$query->sortby('sort', 'DESC');
	$highest = $modx->getObject('slStore', $query);
	
	if ($highest == null) {
		$storeData['sort'] = 1;
	} else {
		$storeData['sort'] = $highest->get('sort') + 1;
	}
	
	$store->fromArray($storeData);
} else {
	// Update an existing store
	$store = $modx->getObject('slStore', $id);
	$store->fromArray($storeData);
}

// Save the store
$store->save();

// Return it
$storeArray = $store->toArray();
return $modx->error->success('', $storeArray);