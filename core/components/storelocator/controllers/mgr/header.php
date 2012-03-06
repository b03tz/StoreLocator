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
 * Loads the header for mgr pages.
 *
 * @package storelocator
 * @subpackage controllers
 */
 
$version = $this->modx->getVersionData();
if (version_compare($version['full_version'],'2.1.1-pl') >= 0) {
	if ($this->modx->user->hasSessionContext($this->modx->context->get('key'))) {
		$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">var siteId = \''.$_SESSION["modx.{$this->modx->context->get('key')}.user.token"].'\';</script>');
	} else {
		$_SESSION["modx.{$this->modx->context->get('key')}.user.token"] = 0;
	}
} else {
	$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">var siteId = \''.$this->modx->site_id.'\';</script>');
}
 
$modx->regClientCSS($this->storelocator->config['baseUrl'].'mgr/css/style.css');

$modx->regClientStartupScript('http://maps.googleapis.com/maps/api/js?sensor=false');
$modx->regClientStartupScript($this->storelocator->config['baseUrl'].'mgr/js/storelocator.js');

$modx->regClientStartupHTMLBlock('<script type="text/javascript">slCore.config.connectorUrl = \''.$this->storelocator->config['baseUrl'].'connector.php\';</script>');

$modx->regClientStartupScript($this->storelocator->config['baseUrl'].'mgr/js/stores.js');
$modx->regClientStartupScript($this->storelocator->config['baseUrl'].'mgr/js/manage_stores.js');

$this->modx->lexicon->load('storelocator:default');
$modx->lexicon->fetch('storelocator');

return '';