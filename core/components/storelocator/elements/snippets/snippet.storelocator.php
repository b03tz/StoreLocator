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
// Load the userValueList class
$storeLocator = $modx->getService('storelocator','StoreLocator', $modx->getOption('storelocator.core_path', null, $modx->getOption('core_path').'components/storelocator/').'model/storelocator/', $scriptProperties);
if (!($storeLocator instanceof StoreLocator)) return '';

// Configuration parameters
$apiKey = $modx->getOption('apiKey', $scriptProperties, $modx->getOption('storelocator.apiKey'));
$zoom = $modx->getOption('zoom', $scriptProperties, 8);
$storeZoom = $modx->getOption('storeZoom', $scriptProperties, 13);
$searchZoom = $modx->getOption('searchZoom', $scriptProperties, 13);
$width = $modx->getOption('width', $scriptProperties, 300);
$height = $modx->getOption('height', $scriptProperties, 400);
$mapType = $modx->getOption('mapType', $scriptProperties, 'ROADMAP');
$defaultRadius = $modx->getOption('defaultRadius', $scriptProperties, 5);
$centerLongitude = $modx->getOption('centerLongitude', $scriptProperties, 6.61480);
$centerLatitude = $modx->getOption('centerLatitude', $scriptProperties, 52.40441);
$markerImage = $modx->getOption('markerImage', $scriptProperties, '0');
$sortDir = $modx->getOption('sortDir', $scriptProperties, 'ASC');
$limit = $modx->getOption('limit', $scriptProperties, 0);

// Templating parameters
$formTpl = $modx->getOption('formTpl', $scriptProperties, 'sl.form');
$storeRowTpl = $modx->getOption('storeRowTpl', $scriptProperties, 'sl.storerow');
$storeInfoWindowTpl = $modx->getOption('storeInfoWindowTpl', $scriptProperties, 'sl.infowindow');
$noResultsTpl = $modx->getOption('noResultsTpl', $scriptProperties, 'sl.noresultstpl');

// Developers templating parameters
$scriptWrapperTpl = $modx->getOption('scriptWrapperTpl', $scriptProperties, 'sl.scriptwrapper');
$scriptStoreMarker = $modx->getOption('scriptStoreMarker', $scriptProperties, 'sl.scriptstoremarker');

// Load lexicon
$modx->lexicon->load('storelocator:frontend');

// Register the google maps API
if ($apiKey != '') {
	$modx->regClientStartupScript('http://maps.googleapis.com/maps/api/js?sensor=false&key='.$apiKey);
} else {
	$modx->regClientStartupScript('http://maps.googleapis.com/maps/api/js?sensor=false');
}

// The init code

$centerLongitude = isset($_REQUEST['longitude']) ? floatval($_REQUEST['longitude']) : $centerLongitude;
$centerLatitude = isset($_REQUEST['latitude']) ? floatval($_REQUEST['latitude']) : $centerLatitude;
$zoom = isset($_REQUEST['longitude']) ? $searchZoom : $zoom;
$modx->regClientStartupHTMLBlock($storeLocator->getChunk($scriptWrapperTpl, array(
	'centerLatitude' => $centerLatitude,
	'centerLongitude' => $centerLongitude,
	'zoom' => $zoom,
	'mapType' => $mapType
)));

// Parse store chunks
$query = $modx->newQuery('slStore'); 
$query->sortby('sort', $sortDir);
$query->limit($limit);

$stores = $modx->getCollection('slStore', $query);
$storeListOutput = '';
$i = 0;
foreach($stores as $store) {

	if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
		$longitude = floatval($_REQUEST['longitude']);
		$latitude = floatval($_REQUEST['latitude']);
		$distance = (int) $_REQUEST['radius'];
		
		list($lat1, $lat2, $lon1, $lon2) = $storeLocator->getBoundingBox($latitude, $longitude, $distance);
			
		// Check if this store is within the selected radius
		if ($store->get('longitude') > $lon1 and $store->get('longitude') < $lon2 && $store->get('latitude') > $lat1 && $store->get('latitude') < $lat2) {
			// All is okay
		} else {
			// This store is not within the radius
			continue;
		}
	}

	// Get resource that belongs to the store
	$resource = $modx->getObject('modResource', $store->get('resource_id'));
	
	// If the resource doesn't exist just skip it
	if ($resource != null) {
		$resourceArray = $resource->toArray();
		$storeListOutput .= $storeLocator->getChunk($storeRowTpl, array_merge(
			$resourceArray,
			array(
				'store' => $store->toArray(),
				'onclick' => 'storeLocatorMap.setCenter(new google.maps.LatLng('.$store->get('latitude').', '.$store->get('longitude').')); storeLocatorMap.setZoom('.$storeZoom.');'
			)
		));
		
		$infoWindowOutput = '';
		$infoWindowOutput = $storeLocator->getChunk($storeInfoWindowTpl, array_merge(
			$resourceArray,
			array(
				'store' => $store->toArray()
			)
		));
		
		$storeListOutput .= $storeLocator->getChunk($scriptStoreMarker, array_merge(
			$resourceArray,
			array(
				'store' => $store->toArray(),
				'content' => $infoWindowOutput,
				'markerImage' => $markerImage
			)
		));
		
		$i++;
	}
}

// Nothing is found
if ($i == 0) {
	$storeListOutput = $storeLocator->getChunk($noResultsTpl);
}

$locatorFormOutput = $storeLocator->getChunk($formTpl, array(
	'query' => str_replace(array('[', ']'), '', $_REQUEST['query']),
	'radius' => isset($_REQUEST['radius']) ? $_REQUEST['radius'] : $defaultRadius
));

// Parse output to place holders
$modx->toPlaceHolders(array(
	'map' => '<div id="storelocator_canvas" style="width: '.$width.'px; height: '.$height.'px;"></div>',
	'storeList' => $storeListOutput,
	'form' => $locatorFormOutput
), 'StoreLocator');