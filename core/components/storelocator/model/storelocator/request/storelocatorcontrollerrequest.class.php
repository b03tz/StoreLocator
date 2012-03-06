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
require_once MODX_CORE_PATH . 'model/modx/modrequest.class.php';
/**
 * Encapsulates the interaction of MODx manager with an HTTP request.
 *
 * @package storelocator
 * @subpackage request
 * @extends modRequest
 */
class StoreLocatorControllerRequest extends modRequest {
    /**
     * A reference to the StoreLocator instance
     * @var StoreLocator $storelocator
     */
    public $storelocator = null;
    /**
     * The action key to use
     * @var string $actionVar
     */
    public $actionVar = 'action';
    /**
     * The default controller to load if none is specified
     * @var string $defaultAction
     */
    public $defaultAction = 'home';
    /**
     * The currently loaded action
     * @var string $action
     */
    public $action = '';

    /**
     * @param StoreLocator $storelocator A reference to the StoreLocator instance
     */
    function __construct(StoreLocator &$storelocator) {
        parent :: __construct($storelocator->modx);
        $this->storelocator =& $storelocator;
    }

    /**
     * Extends modRequest::handleRequest and loads the proper error handler and
     * actionVar value.
     *
     * @return string
     */
    public function handleRequest() {
        $this->loadErrorHandler();

        /* save page to manager object. allow custom actionVar choice for extending classes. */
        $this->action = isset($_REQUEST[$this->actionVar]) ? $_REQUEST[$this->actionVar] : $this->defaultAction;

        return $this->_respond();
    }

    /**
     * Prepares the MODx response to a mgr request that is being handled.
     *
     * @access public
     * @return boolean True if the response is properly prepared.
     */
    private function _respond() {
        $modx =& $this->modx;
        $storelocator =& $this->storelocator;

        $viewHeader = include $this->storelocator->config['corePath'].'controllers/mgr/header.php';

        $f = $this->storelocator->config['corePath'].'controllers/mgr/'.$this->action.'.php';
        if (file_exists($f)) {
            $viewOutput = include $f;
        } else {
            $viewOutput = 'Action not found: '.$f;
        }

        return $viewHeader.$viewOutput;
    }
}