<!-- BEGIN: main -->
<script type="text/javascript">
    jQuery(function($) {
        // Asynchronously Load the map API 
        var script = document.createElement('script');
        script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
        document.body.appendChild(script);
    });
    
    function initialize() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap'
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
            '<h3>{VIEW.school_title}</h3>' +
            '{LANG.address}: <strong>{VIEW.address}</strong><br/>' +
            '<hr/>' +
            '<p>{VIEW.hometext}</p>' + '</div>'],
            <!-- END: map_info -->
        ];
            
        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;
        
        var image = {
            url: '{NV_BASE_SITEURL}themes/{TEMPLATE}/images/{MODULE_FILE}/school_icon.png',
            size: new google.maps.Size(30, 41),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
          };


        // Loop through our array of markers & place each one on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
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
    
            // Automatically center the map fitting all markers on the screen
            map.fitBounds(bounds);
        }
    
        // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(14);
            google.maps.event.removeListener(boundsListener);
        });
        
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
</style>

<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>
<!-- END: main -->