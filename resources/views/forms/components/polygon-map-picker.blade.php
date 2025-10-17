@php
    $statePath = $statePath ?? 'polygon_data';
    $id = 'polygon-map-' . str_replace(['.', '[', ']'], ['-', '-', '-'], $statePath) . '-' . uniqid();
    $defaultLocation = $defaultLocation ?? [-7.388119, 109.358398];
    $defaultZoom = $defaultZoom ?? 12;
    $height = $height ?? '400px';
    $label = $label ?? 'Tentukan Area Polygon';
    $latField = $latField ?? 'latitude';
    $lngField = $lngField ?? 'longitude';
    $polygonField = $polygonField ?? 'polygon_area';
@endphp

<div 
    x-data="polygonMapPicker({
        statePath: '{{ $statePath }}',
        latField: '{{ $latField }}',
        lngField: '{{ $lngField }}',
        polygonField: '{{ $polygonField }}',
        defaultLocation: {{ json_encode($defaultLocation) }},
        defaultZoom: {{ $defaultZoom }},
        id: '{{ $id }}'
    })"
    x-init="$nextTick(() => initMap())"
    wire:ignore
    class="polygon-map-picker-container"
>
    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
        </label>
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
            Klik ikon polygon (⬟) pada toolbar untuk mulai menggambar area. Klik beberapa titik untuk membuat polygon, double-click untuk menyelesaikan.
        </div>
    </div>

    <div class="relative border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
        <div 
            id="{{ $id }}" 
            style="height: {{ $height }}; width: 100%;"
            class="leaflet-map-container"
        ></div>
        
        <!-- Loading indicator -->
        <div 
            x-show="loading" 
            class="absolute inset-0 bg-gray-100 dark:bg-gray-800 bg-opacity-75 flex items-center justify-center"
            style="z-index: 1000;"
        >
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Loading map...</span>
            </div>
        </div>
    </div>

    <!-- Info panel -->
    <div x-show="polygonInfo" class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium text-green-800 dark:text-green-200">Polygon berhasil dibuat</span>
        </div>
        <div class="mt-2 text-xs text-green-700 dark:text-green-300">
            <div>Koordinat pusat: <span x-text="centerLat"></span>, <span x-text="centerLng"></span></div>
            <div x-show="areaSize">Luas area: <span x-text="areaSize"></span></div>
            <div class="text-xs mt-1 text-gray-600">Gunakan tools edit/delete untuk mengubah atau menghapus polygon</div>
        </div>
    </div>
</div>

@pushonce('polygon-map-styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<style>
    .leaflet-map-container {
        z-index: 1;
    }
    .leaflet-control-container {
        z-index: 1000;
    }
    .leaflet-draw-toolbar {
        margin-top: 10px;
    }
    .leaflet-draw-actions {
        margin-top: 5px;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }
</style>
@endpushonce

@pushonce('polygon-map-scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
function polygonMapPicker(config) {
    return {
        map: null,
        drawnItems: null,
        drawControl: null,
        loading: true,
        polygonInfo: false,
        centerLat: '',
        centerLng: '',
        areaSize: '',

        initMap() {
            // Pastikan container siap
            setTimeout(() => {
                this.createMap();
            }, 100);
        },

        createMap() {
            try {
                const container = document.getElementById(config.id);
                if (!container) {
                    console.error('Map container not found:', config.id);
                    return;
                }

                // Initialize map
                this.map = L.map(config.id, {
                    center: config.defaultLocation,
                    zoom: config.defaultZoom,
                    zoomControl: true
                });

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);

                // Initialize drawn items layer
                this.drawnItems = new L.FeatureGroup();
                this.map.addLayer(this.drawnItems);

                // Initialize drawing controls dengan konfigurasi yang benar
                this.drawControl = new L.Control.Draw({
                    position: 'topright',
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true,
                            drawError: {
                                color: '#e74c3c',
                                message: '<strong>Error:</strong> Area tidak boleh overlap!'
                            },
                            shapeOptions: {
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.3,
                                weight: 3
                            }
                        },
                        rectangle: {
                            shapeOptions: {
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.3,
                                weight: 3
                            }
                        },
                        circle: false,
                        circlemarker: false,
                        marker: false,
                        polyline: false
                    },
                    edit: {
                        featureGroup: this.drawnItems,
                        edit: true,
                        remove: true
                    }
                });

                this.map.addControl(this.drawControl);

                // Event listeners berdasarkan dokumentasi Leaflet.draw
                this.map.on(L.Draw.Event.CREATED, (e) => {
                    this.onPolygonCreated(e);
                });

                this.map.on(L.Draw.Event.EDITED, (e) => {
                    this.onPolygonEdited(e);
                });

                this.map.on(L.Draw.Event.DELETED, (e) => {
                    this.onPolygonDeleted(e);
                });

                // Load existing polygon if any
                this.loadExistingPolygon();

                this.loading = false;
                
                // Force map to resize after loading
                setTimeout(() => {
                    this.map.invalidateSize();
                }, 200);

            } catch (error) {
                console.error('Error initializing map:', error);
                this.loading = false;
            }
        },

        onPolygonCreated(e) {
            const layer = e.layer;
            
            // Clear existing polygons (hanya izinkan satu polygon)
            this.drawnItems.clearLayers();
            
            // Add new polygon
            this.drawnItems.addLayer(layer);
            
            // Save polygon data
            this.savePolygonData(layer);
        },

        onPolygonEdited(e) {
            const layers = e.layers;
            layers.eachLayer((layer) => {
                this.savePolygonData(layer);
            });
        },

        onPolygonDeleted(e) {
            this.polygonInfo = false;
            this.centerLat = '';
            this.centerLng = '';
            this.areaSize = '';
            this.updateFormFields('', '', '');
        },

        savePolygonData(layer) {
            try {
                // Create GeoJSON
                const geoJson = layer.toGeoJSON();
                
                // Calculate center (centroid)
                const bounds = layer.getBounds();
                const center = bounds.getCenter();
                this.centerLat = center.lat.toFixed(8);
                this.centerLng = center.lng.toFixed(8);
                
                // Calculate area menggunakan fungsi Leaflet
                let area = 0;
                if (layer.getLatLngs) {
                    // Gunakan L.GeometryUtil jika tersedia, atau implementasi sederhana
                    const latlngs = layer.getLatLngs()[0];
                    area = this.calculatePolygonArea(latlngs);
                }
                
                // Format area size
                this.areaSize = this.formatAreaSize(area);
                
                // Store GeoJSON data
                const polygonDataStr = JSON.stringify(geoJson);
                
                // Update form fields
                this.updateFormFields(polygonDataStr, this.centerLat, this.centerLng);
                
                // Show info panel
                this.polygonInfo = true;
                
                // Add popup with info
                const popupContent = `
                    <div style="font-size: 12px;">
                        <strong>Area Polygon</strong><br/>
                        Pusat: ${this.centerLat}, ${this.centerLng}<br/>
                        Luas: ${this.areaSize}
                    </div>
                `;
                layer.bindPopup(popupContent);
                
            } catch (error) {
                console.error('Error saving polygon data:', error);
            }
        },

        calculatePolygonArea(latlngs) {
            // Implementasi sederhana menggunakan shoelace formula
            // Konversi ke meter persegi (approximation)
            let area = 0;
            const n = latlngs.length;
            
            if (n < 3) return 0;
            
            for (let i = 0; i < n; i++) {
                const j = (i + 1) % n;
                const xi = latlngs[i].lng;
                const yi = latlngs[i].lat;
                const xj = latlngs[j].lng;
                const yj = latlngs[j].lat;
                
                area += xi * yj;
                area -= xj * yi;
            }
            
            area = Math.abs(area) / 2;
            
            // Convert to square meters (very approximate)
            // 1 degree ≈ 111,320 meters at equator
            const meterConversion = 111320 * 111320;
            return area * meterConversion;
        },

        loadExistingPolygon() {
            // Try to get existing polygon data dari Livewire/form
            try {
                if (this.$wire) {
                    const existingData = this.$wire.get(config.polygonField);
                    
                    if (existingData && existingData !== '') {
                        const geoJson = JSON.parse(existingData);
                        const layer = L.geoJSON(geoJson, {
                            style: {
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.3,
                                weight: 3
                            }
                        });
                        
                        // Tambahkan ke drawnItems agar bisa di-edit
                        layer.eachLayer((l) => {
                            this.drawnItems.addLayer(l);
                        });
                        
                        this.map.fitBounds(layer.getBounds());
                        
                        // Update display data
                        this.polygonInfo = true;
                        const bounds = layer.getBounds();
                        const center = bounds.getCenter();
                        this.centerLat = center.lat.toFixed(8);
                        this.centerLng = center.lng.toFixed(8);
                        
                        // Calculate area for existing polygon
                        layer.eachLayer((l) => {
                            if (l.getLatLngs) {
                                const area = this.calculatePolygonArea(l.getLatLngs()[0]);
                                this.areaSize = this.formatAreaSize(area);
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading existing polygon:', error);
            }
        },

        updateFormFields(polygonData, lat, lng) {
            // Update Livewire data
            try {
                if (this.$wire) {
                    this.$wire.set(config.polygonField, polygonData || '');
                    this.$wire.set(config.latField, lat || '');
                    this.$wire.set(config.lngField, lng || '');
                }
            } catch (error) {
                console.error('Error updating form fields:', error);
            }
        },

        formatAreaSize(area) {
            if (!area || area === 0) return '';
            
            if (area > 1000000) {
                return `${(area / 1000000).toFixed(2)} km²`;
            } else if (area > 10000) {
                return `${(area / 10000).toFixed(2)} ha`;
            } else {
                return `${area.toFixed(0)} m²`;
            }
        }
    }
}
</script>
@endpushonce

<div 
    x-data="polygonMapPicker({
        statePath: '{{ $statePath }}',
        latField: '{{ $latField }}',
        lngField: '{{ $lngField }}',
        polygonField: '{{ $polygonField }}',
        defaultLocation: {{ json_encode($defaultLocation) }},
        defaultZoom: {{ $defaultZoom }},
        id: '{{ $id }}'
    })"
    x-init="initMap()"
    wire:ignore
    class="polygon-map-picker-container"
>
    <div class="mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label }}
        </label>
        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
            Gunakan tools di peta untuk menggambar polygon area. Klik ikon polygon untuk mulai menggambar.
        </div>
    </div>

    <div class="relative border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
        <div 
            id="{{ $id }}" 
            style="height: {{ $height }}; width: 100%;"
            class="leaflet-map-container"
        ></div>
        
        <!-- Loading indicator -->
        <div 
            x-show="loading" 
            class="absolute inset-0 bg-gray-100 dark:bg-gray-800 bg-opacity-75 flex items-center justify-center"
        >
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-sm text-gray-600 dark:text-gray-400">Loading map...</span>
            </div>
        </div>
    </div>

    <!-- Hidden inputs to store data -->
    <input type="hidden" x-model="polygonData" name="{{ $polygonField }}" />
    <input type="hidden" x-model="centerLat" name="{{ $latField }}" />
    <input type="hidden" x-model="centerLng" name="{{ $lngField }}" />

    <!-- Info panel -->
    <div x-show="polygonData" class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-sm font-medium text-green-800 dark:text-green-200">Polygon berhasil dibuat</span>
        </div>
        <div class="mt-2 text-xs text-green-700 dark:text-green-300">
            <div>Koordinat pusat: <span x-text="centerLat"></span>, <span x-text="centerLng"></span></div>
            <div x-show="areaSize">Luas area: <span x-text="areaSize"></span></div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<style>
    .leaflet-map-container {
        z-index: 1;
    }
    .leaflet-control-container {
        z-index: 1000;
    }
    .leaflet-draw-toolbar {
        margin-top: 10px;
    }
    .leaflet-draw-draw-polygon {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAdgAAAHYBTnsmCAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAIgSURBVEiJtZU9SwNBEIafRCzSCFYWNlYWQoJYWVhYWNhYWFhZWVhYWFlYWFlZWNhYWFhYWFlYWFhYWFlYWFhZWFhYWNhYWFhYWFhYWNhYWFhYWNhYWFhYWNhYWFhZWFhYWNhYWFhYWNhYWFhZWFhYWNhYWFhYWNhYWFhZWFhYWNhYWFlYWFhYWNhYWFhZWFhYWNhY') !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
<script>
function polygonMapPicker(config) {
    return {
        map: null,
        drawnItems: null,
        loading: true,
        polygonData: '',
        centerLat: '',
        centerLng: '',
        areaSize: '',

        initMap() {
            // Wait for DOM to be ready
            this.$nextTick(() => {
                this.createMap();
            });
        },

        createMap() {
            try {
                // Initialize map
                this.map = L.map(config.id).setView(config.defaultLocation, config.defaultZoom);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);

                // Initialize drawn items layer
                this.drawnItems = new L.FeatureGroup();
                this.map.addLayer(this.drawnItems);

                // Initialize drawing controls
                const drawControl = new L.Control.Draw({
                    position: 'topright',
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true,
                            drawError: {
                                color: '#e1e100',
                                message: '<strong>Error:</strong> Area tidak boleh overlap!'
                            },
                            shapeOptions: {
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.3,
                                weight: 2
                            }
                        },
                        rectangle: false,
                        circle: false,
                        circlemarker: false,
                        marker: false,
                        polyline: false
                    },
                    edit: {
                        featureGroup: this.drawnItems,
                        remove: true
                    }
                });

                this.map.addControl(drawControl);

                // Event listeners
                this.map.on('draw:created', (e) => {
                    this.onPolygonCreated(e);
                });

                this.map.on('draw:edited', (e) => {
                    this.onPolygonEdited(e);
                });

                this.map.on('draw:deleted', (e) => {
                    this.onPolygonDeleted(e);
                });

                // Load existing polygon if any
                this.loadExistingPolygon();

                this.loading = false;
            } catch (error) {
                console.error('Error initializing map:', error);
                this.loading = false;
            }
        },

        onPolygonCreated(e) {
            const layer = e.layer;
            
            // Clear existing polygons
            this.drawnItems.clearLayers();
            
            // Add new polygon
            this.drawnItems.addLayer(layer);
            
            // Save polygon data
            this.savePolygonData(layer);
        },

        onPolygonEdited(e) {
            const layers = e.layers;
            layers.eachLayer((layer) => {
                this.savePolygonData(layer);
            });
        },

        onPolygonDeleted(e) {
            this.polygonData = '';
            this.centerLat = '';
            this.centerLng = '';
            this.areaSize = '';
            this.updateFormFields();
        },

        savePolygonData(layer) {
            try {
                // Get polygon coordinates
                const latlngs = layer.getLatLngs()[0];
                
                // Create GeoJSON
                const geoJson = layer.toGeoJSON();
                
                // Calculate center (centroid)
                const bounds = layer.getBounds();
                const center = bounds.getCenter();
                
                // Calculate area (approximate)
                const area = L.GeometryUtil ? L.GeometryUtil.geodesicArea(latlngs) : 0;
                
                // Format area size
                this.areaSize = this.formatAreaSize(area);
                
                // Store data
                this.polygonData = JSON.stringify(geoJson);
                this.centerLat = center.lat.toFixed(8);
                this.centerLng = center.lng.toFixed(8);
                
                // Update form fields
                this.updateFormFields();
                
            } catch (error) {
                console.error('Error saving polygon data:', error);
            }
        },

        loadExistingPolygon() {
            // Try to load existing polygon from form field
            const existingData = document.querySelector(`input[name="${config.polygonField}"]`)?.value;
            
            if (existingData) {
                try {
                    const geoJson = JSON.parse(existingData);
                    const layer = L.geoJSON(geoJson, {
                        style: {
                            color: '#3b82f6',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.3,
                            weight: 2
                        }
                    });
                    
                    this.drawnItems.addLayer(layer);
                    this.map.fitBounds(layer.getBounds());
                    
                    // Update display data
                    this.polygonData = existingData;
                    const center = layer.getBounds().getCenter();
                    this.centerLat = center.lat.toFixed(8);
                    this.centerLng = center.lng.toFixed(8);
                    
                } catch (error) {
                    console.error('Error loading existing polygon:', error);
                }
            }
        },

        updateFormFields() {
            // Update Livewire/Alpine data
            this.$wire.set(config.polygonField, this.polygonData);
            this.$wire.set(config.latField, this.centerLat);
            this.$wire.set(config.lngField, this.centerLng);
        },

        formatAreaSize(area) {
            if (area === 0) return '';
            
            if (area > 1000000) {
                return `${(area / 1000000).toFixed(2)} km²`;
            } else if (area > 10000) {
                return `${(area / 10000).toFixed(2)} ha`;
            } else {
                return `${area.toFixed(0)} m²`;
            }
        }
    }
}
</script>
@endpush