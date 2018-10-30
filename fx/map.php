<?php
/**
 * Generate and display a Google Map
 * @param string $id ID to be used for wrapper div
 * @param float $latitude Latitude for map centre
 * @param float $longitude Longitude for map centre
 * @param array $markers Optional. List of markers in format array('lng' => $longitude, 'lat' => $latitude). If empty a single marker will be placed at map centre.
 * @param string $width Optional. Map width (default '500px').
 * @param string $height Optional. Map height (default '500px').
 * @param string $class Optional. Additional CSS class to be added to wrapper div (default 'map').
 * @param integer $zoom Optional. Zoom level (default 14).
 */
function spark_map($id, $latitude, $longitude, $markers = array(), $width='500px', $height='500px', $class="map", $zoom = 14, $echo = true) {
    if (empty($markers)) {
        $markers = array(
                array(
                        'lat' => $latitude,
                        'lng' => $longitude,
                )
        );
    }
    $api = spark_google_map_api();

    $map  = '';
    $map .= '<style>'."\n";
    $map .= '#'.$id.' {'."\n";
    $map .= '    width: '.$width.';'."\n";
    $map .= '    height: '.$height.';'."\n";
    $map .= '}'."\n";
    $map .= '</style>'."\n";
    $map .= '<div id="'.$id.'" class="'.$class.'"></div>'."\n";
    $map .= '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='.$api['key'].'"></script>'."\n";
    $map .= '<script type="text/javascript">'."\n";
    $map .= 'jQuery(document).ready(function() {'."\n";
    $map .= "    var map_canvas = document.getElementById('$id');"."\n";
    $map .= '    var map_options = {'."\n";
    $map .= '        center: {lat: '.$latitude.', lng: '.$longitude.'},'."\n";
    $map .= '        zoom: '.$zoom.','."\n";
    $map .= '        mapTypeId: google.maps.MapTypeId.ROADMAP'."\n";
    $map .= '    }'."\n";
    $map .= '    var map = new google.maps.Map(map_canvas, map_options)'."\n";
    $i = 1;
    foreach ($markers as $marker) {
        $map .= '    var marker'.$i.' = new google.maps.Marker({'."\n";
        $map .= '        position: {lat: '.$marker['lat'].', lng: '.$marker['lng'].'},'."\n";
        $map .= '        map: map'."\n";
        $map .= '    });'."\n";
        $i++;
    }
    $map .= '});'."\n";
    $map .= '</script>'."\n";

    if ($echo) {
        echo $map;
    } else {
        return $map;
    }
}

add_filter('acf/fields/google_map/api', 'spark_google_map_api');
function spark_google_map_api($api = array()) {
    $api['key'] = 'AIzaSyDqkzFgSnr9f9tNqlBtthZ8vDRWLg4kOIk';
    return $api;
}
