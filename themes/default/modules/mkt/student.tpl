<!-- BEGIN: main -->
<script type="text/javascript">
    jQuery(function($) {
        // Asynchronously Load the map API 
        var script = document.createElement('script');
        script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
        document.body.appendChild(script);
    });
    var maker_move;
    var map;
    function initialize() {
        
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap',
            center: {lat: {MAPS_CONFIG.gmap_lat}, lng: {MAPS_CONFIG.gmap_lng}}
        };          
        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        map.setTilt(45); 
        // Multiple Markers
        var markers = [
            <!-- BEGIN: map_point -->
            ['{VIEW.school_title}', {VIEW.gmap_lat},{VIEW.gmap_lng}],
            <!-- END: map_point -->
        ];
                            
        // Info Window Content
        var infoWindowContent = [
            <!-- BEGIN: map_info -->
            ['<div class="info_content">' +
            '<h3>{VIEW.student_name}</h3>' +
            '{LANG.birthday} <strong>{VIEW.birthday}</strong>' + 
            '' +
            '</div>'],
            <!-- END: map_info -->
        ];
            
        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
        
        var image = {
            url: '{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/point.png',
            size: new google.maps.Size(30, 41),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
          };

        // Loop through our array of markers & place each one on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            //bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                icon:image,
                title: markers[i][0]
            });
            
            // Allow each marker to have an info window    
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));
    
        }
        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom({MAPS_CONFIG.gmap_z});
            google.maps.event.removeListener(boundsListener);
        });
        //dia diem de tinh ban kinh
        var pointA = new google.maps.LatLng({MAPS_CONFIG.gmap_lat}, {MAPS_CONFIG.gmap_lng});   // Circle center
        var khoangcach = {khoangcach};                                      // 500m
        // Draw the circle
          var makerCircle = new google.maps.Circle({
             center: pointA,
             radius: khoangcach,
             fillColor: '#FF0000',
             fillOpacity: 0.2,
             map: map
          });
    
          // Show marker at circle center
          maker_move = new google.maps.Marker({
             position: pointA,
             center:pointA,
             draggable: true,
             map: map
          });
          google.maps.event.addListener(maker_move, 'dragend', function (event) {
            document.getElementById("map_lat").value = this.getPosition().lat();
            document.getElementById("map_lon").value = this.getPosition().lng();
            var latlng = new google.maps.LatLng(this.getPosition().lat(), this.getPosition().lng());
            map.setCenter({lat: this.getPosition().lat(), lng: this.getPosition().lng()});
            makerCircle.setCenter(latlng);//update lai vi tri trung tam
        });
        //set center map
        var latlng = new google.maps.LatLng({MAPS_CONFIG.gmap_lat}, {MAPS_CONFIG.gmap_lng});
        bounds.extend(latlng);
        map.fitBounds(bounds);
    }
</script>
<style type="text/css">
    #map_wrapper {
        height: 900px;
    }
    #map_canvas {
        width: 100%;
        height: 100%;
    }
    .search-box{
        padding:10px
    }
</style>
<div class="search-box">
    <form class="form-inline" action="{NV_BASE_SITEURL}index.php" method="get">
    	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
        <input type="hidden" name="map_lat" id="map_lat" value="{MAPS_CONFIG.gmap_lat}" />
        <input type="hidden" name="map_lon" id="map_lon" value="{MAPS_CONFIG.gmap_lng}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    	<strong>{LANG.search_khoangcach}</strong>&nbsp;<input class="form-control" type="text" value="{khoangcach}" name="khoangcach" maxlength="255" />&nbsp;
        <label> {LANG.search_schooltype}: </label>
		<!-- BEGIN: schooltype -->
		<input type="checkbox" class="schooltype" name="schooltype[]"{SCHOOLTYPE.ck} value="{SCHOOLTYPE.key}" />&nbsp;{SCHOOLTYPE.title}&nbsp;
		<!-- END: schooltype -->
        <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
        <!--<input class="btn btn-primary" type="button" name="view_data_student" value="{LANG.view_data_student}" />-->
        <i>{LANG.note_maps_center}</i>
        <div id="loading_bar"></div>
    </form>
</div>
<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>
<script type="text/javascript">
    function nv_data_export() 
    {
        var schooltype = schooltype = $('.schooltype:checked').map(function() {
          return this.value;
        }).get();
        var khoangcach = $('input[name=khoangcach]').val();
        var map_lat = $('input[name=map_lat]').val();
        var map_lon = $('input[name=map_lon]').val();
		$.ajax({
			type : "POST",
			url : nv_siteroot + "index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export-school&nocache=" + new Date().getTime(),
			data : "step=1&khoangcach=" + khoangcach + '&map_lat=' + map_lat + '&map_lon=' +map_lon + '&schooltype=' + schooltype,
			success : function(response) {
				if (response == "COMPLETE") {
					$("#loading_bar").hide();
					alert('{LANG.export_complete}');
					window.location.href = nv_siteroot + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export-school&step=2';
				} else {
					$("#loading_bar").hide();
					alert(response);
				}
			}
		});
	}
    $("input[name=view_data_student]").click(function() {
		var schooltype = schooltype = $('.schooltype:checked').map(function() {
          return this.value;
        }).get();
        var khoangcach = $('input[name=khoangcach]').val();
        var map_lat = $('input[name=map_lat]').val();
        var map_lon = $('input[name=map_lon]').val();
        window.location.href = nv_siteroot + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=student-list&khoangcach=' + khoangcach + '&map_lat=' + map_lat + '&map_lon=' +map_lon + '&schooltype=' + schooltype;
	});
    $("input[name=export_data]").click(function() {
		//$("input[name=export_data]").attr("disabled", "disabled");
		$('#loading_bar').html('<center>{LANG.export_note}<br /><br /><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" /></center>');
		nv_data_export();
	});
</script>
<!-- END: main -->