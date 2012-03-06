<script type="text/javascript">
	storeLocatorMarkers.push({
		position: new google.maps.LatLng([[+store.latitude]], [[+store.longitude]]),
		html: unescape('[[+content:StoreLocatorEscape]]')[[+markerImage:eq=`0`:then=``:else=`,
		icon: new google.maps.MarkerImage('[[+markerImage]]')`]]
	});
</script>