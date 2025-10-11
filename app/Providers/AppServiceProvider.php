<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add Leaflet marker shadow fix to Filament
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => Blade::render('
                <style>
                    /* Leaflet marker styling - sekarang kita punya asset yang proper */
                    .leaflet-marker-icon {
                        margin-left: -12px !important;
                        margin-top: -41px !important;
                    }

                    .leaflet-div-icon {
                        background: transparent !important;
                        border: none !important;
                    }

                    /* Custom marker if needed */
                    .custom-map-marker {
                        background-color: #3b82f6 !important;
                        width: 12px !important;
                        height: 12px !important;
                        border-radius: 50% !important;
                        border: 2px solid white !important;
                        box-shadow: 0 0 5px rgba(0,0,0,0.3) !important;
                    }
                </style>                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        if (typeof L !== "undefined") {
                            // Override default icon paths to use our local files
                            L.Icon.Default.mergeOptions({
                                iconRetinaUrl: "/vendor/leaflet-map-picker/images/marker-icon-2x.png",
                                iconUrl: "/vendor/leaflet-map-picker/images/marker-icon.png",
                                shadowUrl: "/vendor/leaflet-map-picker/images/marker-shadow.png",
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });
                        }

                        // Suppress console errors for marker assets
                        const originalConsoleError = console.error;
                        console.error = function(message) {
                            if (typeof message === "string" &&
                                (message.includes("marker-shadow.png") ||
                                 message.includes("marker-icon") ||
                                 message.includes("leaflet-map-picker"))) {
                                return;
                            }
                            originalConsoleError.apply(console, arguments);
                        };
                    });
                </script>
            ')
        );
    }
}
