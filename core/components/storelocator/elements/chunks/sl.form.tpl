<form method="post" action="" id="storelocator-form" onsubmit="storeLocator_getStores(document.getElementById('storelocator-query').value); return false;">
	[[%storelocator.address]]:<br />
	<input type="text" name="query" value="[[+query]]" id="storelocator-query" /><br /><br />
	[[%storelocator.radius]]:<br />
	<select name="radius">
		<option value="5" [[+radius:eq=`5`:then=`selected="selected"`:else=``]]>5 km</option>
		<option value="10" [[+radius:eq=`10`:then=`selected="selected"`:else=``]]>10 km</option>
		<option value="25" [[+radius:eq=`25`:then=`selected="selected"`:else=``]]>25 km</option>
		<option value="50" [[+radius:eq=`50`:then=`selected="selected"`:else=``]]>50 km</option>
		<option value="100" [[+radius:eq=`100`:then=`selected="selected"`:else=``]]>100 km</option>
	</select>
	<br /><br />
	<input type="button" value="[[%storelocator.submit]]" onclick="storeLocator_getStores(document.getElementById('storelocator-query').value);" />
	<input type="button" value="[[%storelocator.clear]]" onclick="window.location = window.location;" />
	
	<input type="hidden" name="longitude" id="storelocator-longitude" />
	<input type="hidden" name="latitude" id="storelocator-latitude" />
</form>