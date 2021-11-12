<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
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
            ['{VIEW.student_name}', {VIEW.gmap_lat},{VIEW.gmap_lng}],
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
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
    	<input type="hidden" name="{NV_LANG_VARIABLE}"  value="{NV_LANG_DATA}" />
    	<input type="hidden" name="{NV_NAME_VARIABLE}"  value="{MODULE_NAME}" />
    	<input type="hidden" name="{NV_OP_VARIABLE}"  value="{OP}" />
        <input type="hidden" name="map_lat" id="map_lat" value="{MAPS_CONFIG.gmap_lat}" />
        <input type="hidden" name="map_lon" id="map_lon" value="{MAPS_CONFIG.gmap_lng}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    	<p>
            <strong>{LANG.search_khoangcach}</strong>&nbsp;<input class="form-control" placeholder="Từ" type="text" value="{from}" name="from" maxlength="255" />&nbsp;<input class="form-control" placeholder="Đến" type="text" value="{to}" name="to" maxlength="255" />&nbsp;
            <label> {LANG.search_class}: </label>
    		<!-- BEGIN: schooltype -->
    		<label><input type="checkbox" class="schooltype" name="schooltype[]"{SCHOOLTYPE.ck} value="{SCHOOLTYPE.key}" />&nbsp;{SCHOOLTYPE.title}</label>&nbsp;
    		<!-- END: schooltype -->
            <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
            <!-- BEGIN: export_data -->
            <input class="btn btn-primary" type="button" name="export_data" value="{LANG.export_data}" />
            <!-- END: export_data -->
        </p>
        <p>
            <strong>{LANG.search_status}:</strong>
    		<!-- BEGIN: status -->
    		<label><input type="checkbox" class="status" name="status[]"{STATUS.ck} value="{STATUS.key}" />&nbsp;{STATUS.title}</label>&nbsp;
    		<!-- END: status -->
        </p>
        <p>
            <strong>{LANG.remkt_time}:</strong>
    		<input class="form-control" value="{DATA_SEARCH.from}" type="text" id="date_from" name="date_from" style="width:100px" placeholder="{LANG.filter_from}" />
            <input class="form-control" value="{DATA_SEARCH.to}" type="text" id="date_to" name="date_to" style="width:100px" placeholder="{LANG.filter_to}" />
        </p>
        <i>{LANG.note_maps_center}</i>
        <div id="loading_bar"></div>
    </form>
</div>
<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript">
    $("#date_from,#date_to").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonText : '{LANG.select}',
		showButtonPanel : true,
		showOn : 'focus'
	});
    function nv_data_export() 
    {
        var schooltype = $('.schooltype:checked').map(function() {
          return this.value;
        }).get();
        var status = $('.status:checked').map(function() {
          return this.value;
        }).get();
        var from = $('input[name=from]').val();
        var to = $('input[name=to]').val();
        var date_to = $('input[name=date_to]').val();
        var date_from = $('input[name=date_from]').val();
        var map_lat = $('input[name=map_lat]').val();
        var map_lon = $('input[name=map_lon]').val();
		$.ajax({
			type : "POST",
			url : script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=export-student&nocache=" + new Date().getTime(),
			data : "step=1&from=" + from + '&to=' + to + '&map_lat=' + map_lat + '&map_lon=' +map_lon + '&schooltype=' + schooltype + '&status=' + status + '&date_to=' + date_to + '&date_from=' + date_from,
			success : function(response) {
				if (response == "COMPLETE") {
					$("#loading_bar").hide();
					alert('{LANG.export_complete}');
					window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=export-student&step=2';
				} else {
					$("#loading_bar").hide();
					alert(response);
				}
			}
		});
	}
    $("input[name=export_data]").click(function() {
		//$("input[name=export_data]").attr("disabled", "disabled");
		$('#loading_bar').html('<center>{LANG.export_note}<br /><br /><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="" /></center>');
		nv_data_export();
	});
</script>
<!-- END: main -->