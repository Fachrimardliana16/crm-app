// Fix untuk masalah Leaflet marker assets - sekarang dengan asset yang proper
document.addEventListener('DOMContentLoaded', function() {
    // Fix Leaflet marker icon path issue
    if (typeof L !== 'undefined') {
        // Set path untuk icon yang benar
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: '/vendor/leaflet-map-picker/images/marker-icon-2x.png',
            iconUrl: '/vendor/leaflet-map-picker/images/marker-icon.png',
            shadowUrl: '/vendor/leaflet-map-picker/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    }

    // Remove error messages from console untuk marker assets (jika masih ada)
    const originalConsoleError = console.error;
    const originalConsoleWarn = console.warn;

    console.error = function(message) {
        if (typeof message === 'string' &&
            (message.includes('marker-shadow.png') ||
             message.includes('marker-icon') ||
             message.includes('leaflet-map-picker'))) {
            return; // Suppress marker asset errors
        }
        originalConsoleError.apply(console, arguments);
    };

    console.warn = function(message) {
        if (typeof message === 'string' &&
            (message.includes('marker-shadow.png') ||
             message.includes('marker-icon') ||
             message.includes('leaflet-map-picker'))) {
            return; // Suppress marker asset warnings
        }
        originalConsoleWarn.apply(console, arguments);
    };
});
