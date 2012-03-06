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
 * This file is the main class file for StoreLocator.
 *
 * @copyright Copyright (C) 2011, SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * @author SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @package storelocator
 */
class StoreLocator {
    /**
     * A reference to the modX object.
     * @var modX $modx
     */
    public $modx = null;
    /**
     * The request object for the current state
     * @var StoreLocatorControllerRequest $request
     */
    public $request;
    /**
     * The controller for the current request
     * @var StoreLocatorController $controller
     */
    public $controller = null;

    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('storelocator.core_path',null,$modx->getOption('core_path').'components/storelocator/');
        $assetsPath = $this->modx->getOption('storelocator.assets_path',null,$modx->getOption('assets_path').'components/storelocator/');
        $assetsUrl = $this->modx->getOption('storelocator.assets_url',null,$modx->getOption('assets_url').'components/storelocator/');

        $this->config = array_merge(array(
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'processorsPath' => $corePath.'processors/',
            'controllersPath' => $corePath.'controllers/',
            'chunksPath' => $corePath.'elements/chunks/',
            'snippetsPath' => $corePath.'elements/snippets/',

            'baseUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'connectorUrl' => $assetsUrl.'connector.php'
        ),$config);

        $this->modx->addPackage('storelocator', $this->config['modelPath']);
        
        if ($this->modx->lexicon) {
            $this->modx->lexicon->load('storelocator:default');
        }
    }

    /**
     * Initializes StoreLocator based on a specific context.
     *
     * @access public
     * @param string $ctx The context to initialize in.
     * @return string The processed content.
     */
    public function initialize($ctx = 'mgr') {
        $output = '';
        switch ($ctx) {
            case 'mgr':
                if (!$this->modx->loadClass('storelocator.request.StoreLocatorControllerRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new StoreLocatorControllerRequest($this);
                $output = $this->request->handleRequest();
                break;
        }
        return $output;
    }
    
    /**
     * Load the appropriate controller
     * @param string $controller
     * @return null|StoreLocatorController
     */
    public function loadController($controller) {
        if ($this->modx->loadClass('StoreLocatorController',$this->config['modelPath'].'storelocator/request/',true,true)) {
            $classPath = $this->config['controllersPath'].'web/'.$controller.'.php';
            $className = 'StoreLocator'.$controller.'Controller';
            
            if (file_exists($classPath)) {
                if (!class_exists($className)) {
                    $className = require_once $classPath;
                }
                if (class_exists($className)) {
                    $this->controller = new $className($this,$this->config);
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR,'[StoreLocator] Could not load controller: '.$className.' at '.$classPath);
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[StoreLocator] Could not load controller file: '.$classPath);
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[StoreLocator] Could not load storelocatorController class.');
        }
        return $this->controller;
    }
	
    /**
    * Gets a Chunk and caches it; also falls back to file-based templates
    * for easier debugging.
    *
    * @author Shaun McCormick
    * @access public
    * @param string $name The name of the Chunk
    * @param array $properties The properties for the Chunk
    * @return string The processed content of the Chunk
    */
    public function getChunk($name,$properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
	
    /**
    * Returns a modChunk object from a template file.
    *
    * @author Shaun McCormick
    * @access private
    * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
    * @param string $postFix The postfix to append to the name
    * @return modChunk/boolean Returns the modChunk object if found, otherwise
    * false.
    */
    private function _getTplChunk($name,$postFix = '.tpl') {
        $chunk = false;
        $f = $this->config['chunksPath'].$name.$postFix;

        if (file_exists($f)) {
            $o = file_get_contents($f);
            /* @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }
	
	// Thanks to: http://blog.rrwd.nl/2010/08/05/alternatief-voor-getboundingbox-voor-berekenen-afstanden-met-geo-coordinaten/
	public function getBoundingBox($lat_degrees, $lon_degrees, $distance_in_km) {
		$radius = 6371.0;
 
		$rlat = $radius* sin(deg2rad(90 - $lat_degrees));

		$dphi_lon = asin($distance_in_km / $rlat);
		$dphi_lat = asin($distance_in_km / $radius);

		$lat1 = $lat_degrees - rad2deg($dphi_lat);
		$lat2 = $lat_degrees + rad2deg($dphi_lat);
		$lon1 = $lon_degrees - rad2deg($dphi_lon);
		$lon2 = $lon_degrees + rad2deg($dphi_lon);

		return array($lat1, $lat2, $lon1, $lon2);
	}

}