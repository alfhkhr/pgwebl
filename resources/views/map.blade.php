@extends('layout.template')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px)
        }
    </style>
@endsection

    @section('content')
        <div id="map"></div>

    <!-- Modal Create Point -->
    <div class="modal fade" id="CreatePointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Point</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{route('points.store')}}">
            <div class="modal-body">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Fill point name">
                      </div>

                      <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                      </div>

                      <div class="mb-3">
                        <label for="geom_point" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
                      </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        </div>
        </div>
    </div>

    <!-- Modal Create polyline -->
    <div class="modal fade" id="CreatePolylineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polyline</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{route('polylines.store')}}">
            <div class="modal-body">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Fill line name">
                      </div>

                      <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                      </div>

                      <div class="mb-3">
                        <label for="geom_polylines" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_polylines" name="geom_polylines" rows="3"></textarea>
                      </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        </div>
        </div>
    </div>

    <!-- Modal Create Polygon-->
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polygon</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{route('polygons.store')}}">
            <div class="modal-body">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Fill polygon name">
                      </div>

                      <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                      </div>

                      <div class="mb-3">
                        <label for="geom_polygons" class="form-label">Geometry</label>
                        <textarea class="form-control" id="geom_polygons" name="geom_polygons" rows="3"></textarea>
                      </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        </div>
        </div>
    </div>
    @endsection

    @section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script>
        var map = L.map('map').setView([-7.811725820224939, 110.36315612928558], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

/* Digitize Function */
var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
	draw: {
		position: 'topleft',
		polyline: true,
		polygon: true,
		rectangle: true,
		circle: false,
		marker: true,
		circlemarker: false
	},
	edit: false
});

map.addControl(drawControl);

map.on('draw:created', function(e) {
	var type = e.layerType,
		layer = e.layer;

	console.log(type);

	var drawnJSONObject = layer.toGeoJSON();
	var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

	console.log(drawnJSONObject);
	// console.log(objectGeometry);


	if (type === 'polyline') {
		console.log("Create " + type);

    // memunculkan modal polyline
        $('#geom_polylines').val(objectGeometry);
        $('#CreatePolylineModal').modal('show');

    // memunculkan modal polygon
	} else if (type === 'polygon' || type === 'rectangle') {
		console.log("Create " + type);

        $('#geom_polygons').val(objectGeometry);
        $('#CreatePolygonModal').modal('show');

	} else if (type === 'marker') {
		console.log("Create " + type);

        // memunculkan modal point
        $('#geom_point').val(objectGeometry);
        $('#CreatePointModal').modal('show');
	} else {
		console.log('__undefined__');
	}

	drawnItems.addLayer(layer);
});

//GeoJSON Points
var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Name: " + feature.properties.name + "<br>" +
                    "Description: " + feature.properties.description + "<br>" +
                    "Created: " + feature.properties.created_at;
                layer.on({
                    click: function(e) {
                        point.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        point.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

//GeoJSON Polylines
var polyline = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Name: " + feature.properties.name + "<br>" +
                    "Description: " + feature.properties.description + "<br>" +
                    "Length (KM): " + feature.properties.length_km.toFixed(2) + "<br>" +
                    "Created: " + feature.properties.created_at;
                layer.on({
                    click: function(e) {
                        polyline.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        polyline.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.polylines') }}", function(data) {
            polyline.addData(data);
            map.addLayer(polyline);
        });

        //GeoJSON Polygons
        var polygon = L.geoJson(null, {
               onEachFeature: function(feature, layer) {
                   var popupContent = "Nama: " + feature.properties.name + "<br>" + "Luas (Hektar): " + feature
                       .properties
                       .area_hektar.toFixed(2) + "<br>" + "Luas (Km): " + feature.properties
                       .area_km.toFixed(2) + "br" + "<br>" + "Luas (M): " + feature.properties
                       .area_m.toFixed(2) + "br" + "Deskripsi: " + feature.properties.description + "<br>" +
                       "Dibuat: " + feature.properties.created_at;
                   layer.on({
                       click: function(e) {
                           polygon.bindPopup(popupContent);
                       },
                       mouseover: function(e) {
                           polygon.bindTooltip(feature.properties.name);
                       },
                   });
               },
           });
           $.getJSON("{{ route('api.polygons') }}", function(data) {
               polygon.addData(data);
               map.addLayer(polygon);
           });

           // Layer Control
        var baseMaps = {
            "OpenStreetMap": L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        };

        var overlayMaps = {
            "Points": point,
            "Polylines": polyline,
            "Polygons": polygon
        };

        L.control.layers(baseMaps, overlayMaps, { collapsed: false }).addTo(map);
    </script>
@endsection

