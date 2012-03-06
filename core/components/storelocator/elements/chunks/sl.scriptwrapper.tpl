<script type="text/javascript">
	var storeLocatorMap;
	var storeLocatorMarkers = [];
	var storeLocatorInfoWindows = [];
	var storeLocatorGeocoder = null;
	
	function storeLocator_initialize() {
		storeLocatorGeocoder = new google.maps.Geocoder();
		var mapOptions = {
			center: new google.maps.LatLng([[+centerLatitude]], [[+centerLongitude]]),
			zoom: [[+zoom]],
			mapTypeId: google.maps.MapTypeId.[[+mapType]]
		};
		storeLocatorMap = new google.maps.Map(document.getElementById("storelocator_canvas"), mapOptions);
		
		var infoWindow = new google.maps.InfoWindow({
			content: null
		});
		
		// Add all markers
		for (var i = 0; i < storeLocatorMarkers.length; i++) {
			storeLocatorMarkers[i].map = storeLocatorMap;
			var marker = new google.maps.Marker(storeLocatorMarkers[i]);
			
			google.maps.event.addListener(marker, 'click', function() { 
				infoWindow.setContent(this.html);
				infoWindow.open(storeLocatorMap, this);
			}); 
		}
	}
	
	function storeLocator_openInfoWindow(infoWindow, marker) {
		infoWindow.open(storeLocatorMap, marker);
	}
	
	function storeLocator_getStores(address) {
		storeLocatorGeocoder.geocode({
			address: address
		}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				
				// Set values and submit form
				document.getElementById('storelocator-latitude').value = latitude;
				document.getElementById('storelocator-longitude').value = longitude;
				document.getElementById('storelocator-form').submit();
			} else {
				alert('[[%storelocator.address_not_found]]');
			}
		});
	}
	
	google.maps.event.addDomListener(window, 'load', storeLocator_initialize);
</script>