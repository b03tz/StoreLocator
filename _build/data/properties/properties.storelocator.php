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
/**
* @package StoreLocator
* @subpackage build
*/
$properties = array(
    array(
        'name' => 'apiKey',
        'desc' => 'storelocator.prop_apikey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'zoom',
        'desc' => 'storelocator.prop_zoom_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '8',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'storeZoom',
        'desc' => 'storelocator.prop_storezoom_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '13',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'searchZoom',
        'desc' => 'storelocator.prop_searchzoom_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '13',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'width',
        'desc' => 'storelocator.prop_width_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '300',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'height',
        'desc' => 'storelocator.prop_height_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '400',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'mapType',
        'desc' => 'storelocator.prop_maptype_desc',
        'type' => 'list',
        'options' => array(
			array(
				'text' => 'storelocator.hybrid', 
				'value' => 'HYBRID'
			),
			array(
				'text' => 'storelocator.roadmap', 
				'value' => 'ROADMAP'
			),
			array(
				'text' => 'storelocator.satellite', 
				'value' => 'SATELLITE'
			),
			array(
				'text' => 'storelocator.terrain', 
				'value' => 'TERRAIN'
			),
		),
        'value' => 'ROADMAP',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'defaultRadius',
        'desc' => 'storelocator.prop_defaultradius_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '5',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'centerLongitude',
        'desc' => 'storelocator.prop_centerlongitude_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '6.61480',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'centerLatitude',
        'desc' => 'storelocator.prop_centerlatitude_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '52.40441',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'markerImage',
        'desc' => 'storelocator.prop_markerimage_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'storelocator.prop_sortdir_desc',
        'type' => 'list',
        'options' => array(
			array(
				'text' => 'storelocator.asc', 
				'value' => 'ASC'
			),
			array(
				'text' => 'storelocator.desc', 
				'value' => 'DESC'
			)
		),
        'value' => 'ASC',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'storelocator.prop_limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'formTpl',
        'desc' => 'storelocator.prop_formtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.form',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'storeRowTpl',
        'desc' => 'storelocator.prop_storerowtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.storerow',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'storeInfoWindowTpl',
        'desc' => 'storelocator.prop_storeinfowindowtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.infowindow',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'noResultsTpl',
        'desc' => 'storelocator.prop_noresultstpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.noresultstpl',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'scriptWrapperTpl',
        'desc' => 'storelocator.prop_scriptwrappertpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.scriptwrapper',
        'lexicon' => 'storelocator:properties',
    ),
    array(
        'name' => 'scriptStoreMarker',
        'desc' => 'storelocator.prop_scriptstoremarker_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'sl.scriptstoremarker',
        'lexicon' => 'storelocator:properties',
    ),
);

return $properties;