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
 
var slPageStores = Ext.extend(Ext.Panel, {
	initComponent: function() {
		// Rightclick mouse menu for the grid
		this.rowMenu = new Ext.menu.Menu({
			baseParams: {
				rowId: 0
			},
			items: [
				{
					text: 'Update',
					listeners: {
						click: {
							scope: this,
							fn: function() {
								slCore.ajax.request({
									url: slCore.config.connectorUrl,
									params: {
										id: this.rowMenu.baseParams.rowId,
										action: 'mgr/store/get'
									},
									scope: this,
									success: function(response) {
										var store = Ext.decode(response.responseText);

										this.storeForm.getForm().setValues(store.object);
										this.storeWindow.show();
									}
								});
							}	
						}	
					}
				},
				{
					text: 'Remove',
					listeners: {
						click: {
							scope: this,
							fn: function() {
								Ext.Msg.show({
									title: _('storelocator.remove_store_title'),
									msg: _('storelocator.remove_store_description'),
									buttons: Ext.Msg.YESNO,
									icon: Ext.MessageBox.QUESTION,
									scope: this,
									fn: function(response) {
										if (response == 'yes') {
											slCore.ajax.request({
												url: slCore.config.connectorUrl,
												params: {
													id: this.rowMenu.baseParams.rowId,
													action: 'mgr/store/remove'
												},
												scope: this,
												success: function(response) {
													// Reload store
													this.storeGrid.getStore().load();
													
													// Reset ID
													this.rowMenu.baseParams.rowId = 0;
												}
											});
										} 
									}
								});
							}
						}
					}
				}
			]
		});
		
		this.storeGrid = new Ext.grid.GridPanel({
			store: slCore.stores.stores,
			autoHeight: true,
			loadMask: true,
			viewConfig: {
				forceFit: true,
				enableRowBody: true,
				autoFill: true,
				deferEmptyText: false,
				showPreview: true,
				scrollOffset: 0,
				emptyText: _('ext_emptymsg'),
				sm: new Ext.grid.RowSelectionModel({
					singleSelect: true
				})
			},
			loadMask: true,
			ddGroup: 'storeGridDD',
			enableDragDrop: true,
		    autoExpandColumn: 'store-description-column',
		    columns: [
				{
		            xtype: 'gridcolumn',
		            dataIndex: 'resource_id',
		            header: _('storelocator.resource_id'),
					width: 100
		        },
				{
		            xtype: 'gridcolumn',
		            dataIndex: 'description',
					id: 'store-description-column',
		            header: _('storelocator.description') 
		        },
				{
		            xtype: 'gridcolumn', 
		            dataIndex: 'longitude',
		            header: _('storelocator.longitude')
		        },
		    	{
		            xtype: 'gridcolumn',
		            dataIndex: 'latitude',
		            header: _('storelocator.latitude')
		        }
		    ], 
		    listeners: {
				added: {
		    		scope: this,
		    		fn: function() {
		        		this.storeGrid.getStore().load();
						slCore.stores.resources.load();
		        	}
		    	},
		    	rowContextMenu: {
			    	scope: this,
		    		fn: function(grid, rowIndex, event) {
		    			// Set the database ID in the menu's base params so we can access it when an action is performed
		    			this.rowMenu.baseParams.rowId = slCore.stores.stores.getAt(rowIndex).get('id');
		    			this.rowMenu.showAt(event.xy);
		    			event.stopEvent();
		    		}
				},
				render: {
					scope: this,
					fn: function(grid) {
						var ddrow = new Ext.dd.DropTarget(grid.container, {
							ddGroup: 'storeGridDD',
							copy: false,
							notifyDrop: function(dd, e, data) {

							var ds = grid.store;

							// NOTE:
							// you may need to make an ajax call here
							// to send the new order
							// and then reload the store


							// alternatively, you can handle the changes
							// in the order of the row as demonstrated below

							// ***************************************
							var sm = grid.getSelectionModel();
							var rows = sm.getSelections();
							if(dd.getDragData(e)) {
								var cindex = dd.getDragData(e).rowIndex;
								if(typeof(cindex) != "undefined") {
									for(i = 0; i <  rows.length; i++) {
										ds.remove(ds.getById(rows[i].id));
									}
									
									ds.insert(cindex, data.selections);
									sm.clearSelections();
								}
							}
							
							var newOrder = new Array();
							var store = grid.getStore();
							store.each(function(row) {
								newOrder.push(row.get('id'));
							});
							
							// Save the new order to the database
							slCore.showAjaxLoader();
							slCore.ajax.request({
								url: slCore.config.connectorUrl,
								params: {
									newOrder: Ext.encode(newOrder),
									action: 'mgr/store/saveorder'
								},
								scope: this,
								success: function(response) {
									// Reload store
									grid.getStore().load();
									
									// Hide ajax loader
									slCore.hideAjaxLoader();
								}
							});
							// ************************************
							}
						});
					}
				}
			}
		});
		
		//Formpanel for both grids
		this.storeForm = new Ext.form.FormPanel({
			border: false,
			labelWidth: 150,
			monitorValid: true,
			buttons: [
				{
					text: 'Cancel',
					scope: this,
					handler: function() {
						this.storeWindow.hide();	
					}
				},
				{
					text: 'Save',
					scope: this,
					formBind: true,
					handler: function() {
						var storeConfig = Ext.encode(this.storeForm.getForm().getFieldValues());
						slCore.ajax.request({
							url: slCore.config.connectorUrl,
							params: {
								id: this.rowMenu.baseParams.rowId,
								storeConfig: storeConfig,
								action: 'mgr/store/save'
							},
							scope: this,
							success: function(response) {
								// Reload store
								this.storeGrid.getStore().load();
								
								// Reset ID
								this.rowMenu.baseParams.rowId = 0;
								
								// Close the window
								this.storeWindow.hide();
								
								// Clear the form
								this.storeForm.getForm().setValues({
									'latitude': '',
									'longitude': '',
									'description': '' 
								});
							}
						});
					}
				}
			],
			items: [
				{
					xtype: 'textfield',
					name: 'description',
					allowBlank: false,
					anchor: '100%',
					fieldLabel: '<span id="tip-description" data-qtip="'+_('storelocator.description_desc')+'">'+_('storelocator.description_label')+'</span>',
					listeners: {
						focus: function() {
							slCore.getToolTip('description').show();
						},
						blur: function() {
							slCore.getToolTip('description').hide();
						}
					}
				},
				{
					xtype: 'textfield',
					name: 'longitude',
					allowBlank: false,
					id: 'sl-longitude',
					anchor: '100%',
					fieldLabel: '<span id="tip-longitude" data-qtip="'+_('storelocator.longitude_desc')+'">'+_('storelocator.longitude_label')+'</span>',
					regex: new RegExp('^(\\-)?[0-9]{1,3}\\.[0-9]{1,6}$'),
					maskRe: new RegExp('[0-9\\.-]'),
					regexText: _('storelocator.longitude_error'),
					listeners: {
						focus: function() {
							slCore.getToolTip('longitude').show();
						},
						blur: function() {
							slCore.getToolTip('longitude').hide();
						}
					}
				},
				{
					xtype: 'textfield',
					name: 'latitude',
					allowBlank: false,
					id: 'sl-latitude',
					anchor: '100%',
					fieldLabel: '<span id="tip-latitude" data-qtip="'+_('storelocator.latitude_desc')+'">'+_('storelocator.latitude_label')+'</span>',
					regex: new RegExp('^(\\-)?[0-9]{1,3}\\.[0-9]{1,6}$'),
					maskRe: new RegExp('[0-9\\.-]'),
					regexText: _('storelocator.latitude_error'),
					listeners: {
						focus: function() {
							slCore.getToolTip('latitude').show();
						},
						blur: function() {
							slCore.getToolTip('latitude').hide();
						}
					}
				},
				{
					xtype: 'combo',
					displayField: 'pagetitle',
					valueField: 'id',
					anchor: '100%',
					forceSelection: true,
					store: slCore.stores.resources,
					mode: 'remote',
					triggerAction: 'all',
					fieldLabel: '<span id="tip-resource" data-qtip="'+_('storelocator.resource_desc')+'">'+_('storelocator.resource')+'</span>',
					name: 'resource_id',
					allowBlank: false,
					listeners: {
						focus: function() {
							slCore.getToolTip('resource').show();
						},
						blur: function() {
							slCore.getToolTip('resource').hide();
						}
					}
				},
				{
					xtype: 'label',
					html: '<br /><br />'
				},
				{
					xtype: 'fieldset',
					title: _('storelocator.search_by_address'),
					items: [
						{
							xtype: 'textfield',
							anchor: '100%',
							name: 'sl-address',
							id: 'sl-address',
							fieldLabel: '<span id="tip-address" data-qtip="'+_('storelocator.address_desc')+'">'+_('storelocator.address')+'</span>',
							listeners: {
								focus: function() {
									slCore.getToolTip('address').show();
								},
								blur: function() {
									slCore.getToolTip('address').hide();
								}
							}
						},
						{
							xtype: 'button', 
							text: _('storelocator.search'),
							handler: function() {
								slCore.getCoordinates(Ext.getCmp('sl-address').getValue());
							}
						}
					]
				}
			]
		});
		
		this.storeWindow = new Ext.Window({
			padding: 10,
			title: _('storelocator.store_settings'),
			width: 350,
			modal: true,
			closeAction: 'hide',
			items: [
				this.storeForm
			],
			listeners: {
				show: function() {
					slCore.addToolTip('description', 'tip-description');
					slCore.addToolTip('longitude', 'tip-longitude');
					slCore.addToolTip('latitude', 'tip-latitude');
					slCore.addToolTip('resource', 'tip-resource');
					slCore.addToolTip('address', 'tip-address');
				}
			}
		});
	
		// The mainpanel always has to be in the "this.mainPanel" variable
		this.mainPanel = new Ext.Panel({
			renderTo: 'storelocator-content',
			padding: 15,
			border: false,
			tbar: [
				{
					xtype: 'button',
					text: _('storelocator.add_location'),
					handler: function() {
						// Reset the form
						this.storeForm.getForm().setValues({
							'latitude': '',
							'longitude': '',
							'description': '',
							'sl-address': '',
							'resource_id': ''
						});
						
						// Reset validation
						this.storeForm.getForm().reset();
						
						// Reset ID
						this.rowMenu.baseParams.rowId = 0;
						
						// Show the window
						this.storeWindow.show();
					},
					scope: this
				},
				'->',
				{
					xtype: 'button',
					text: _('storelocator.about'),
					handler: function() {
						new Ext.Window({
							title: _('storelocator.about'),
							modal: true,
							html: '<iframe width="420" height="390" frameborder="0" src="http://www.scherpontwikkeling.nl/components/storelocator/about.html"></iframe>',
							width: 430,
							height: 400,
							padding: 10
						}).show();
					}
				}
			],
			items: [
				this.storeGrid
			]
		});
	}
});

Ext.onReady(function() {
	// Set page title and load main panel
	slCore.setTitle(_('storelocator.manage_stores'));
		
	// this makes the main class accessible through slCore.pageClass and the panel through slCore.pagePanel
	slCore.loadPanel(slPageStores); 
});