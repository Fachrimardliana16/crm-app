# PostGIS Integration Documentation

## Overview
This CRM application now includes comprehensive PostGIS integration for spatial data management, enabling advanced geographic and location-based functionality.

## Features Implemented

### 🗺️ Geometry Columns
Added spatial geometry columns to location-based tables:
- **cabang**: Cabang office locations and coverage areas
- **rayon**: Rayon service areas with boundaries  
- **sub_rayon**: Sub-rayon coverage zones
- **kecamatan**: District geographic boundaries
- **kelurahan**: Village/subdistrict boundaries
- **spam**: SPAM facility locations and service areas

### 📊 Geometry Fields
Each spatial table includes:
- `latitude` (DECIMAL): Point latitude coordinate
- `longitude` (DECIMAL): Point longitude coordinate  
- `polygon_area` (GEOMETRY): Polygon boundary using SRID 4326 (WGS84)

### 🔧 HasGeometry Trait
Comprehensive trait providing spatial functionality:

#### Distance Calculations
- `getDistanceTo($lat, $lng)`: Calculate distance between points in meters
- `scopeWithinDistance($query, $lat, $lng, $distance)`: Find records within distance

#### Polygon Operations
- `containsPoint($lat, $lng)`: Check if point is within polygon boundary
- `scopeContainsPoint($query, $lat, $lng)`: Query records containing point
- `getPolygonAreaSizeAttribute()`: Get polygon area in square meters
- `getPolygonCenterAttribute()`: Get polygon centroid coordinates
- `getPolygonCoordinatesAttribute()`: Get polygon boundary coordinates

#### Polygon Management
- `setPolygonFromWKT($wkt)`: Set polygon from WKT string
- `setPolygonFromCoordinates($coords)`: Set polygon from coordinate array
- `scopeIntersectsWith($query, $wkt)`: Find overlapping polygons

## Usage Examples

### Basic Distance Queries
```php
// Find cabangs within 5km of a point
$nearbyCabangs = Cabang::withinDistance(-7.240, 112.750, 5000)->get();

// Calculate distance between two locations
$distance = $cabang->getDistanceTo($rayon->latitude, $rayon->longitude);
```

### Point-in-Polygon Checks
```php
// Check if customer location is within service area
$isInServiceArea = $rayon->containsPoint($customerLat, $customerLng);

// Find all rayons containing a specific point
$containingRayons = Rayon::whereRaw(
    'ST_Contains(polygon_area, ST_SetSRID(ST_MakePoint(?, ?), 4326))',
    [$lng, $lat]
)->get();
```

### Polygon Management
```php
// Set coverage area from WKT
$cabang->setPolygonFromWKT('POLYGON((112.75 -7.25, 112.76 -7.25, 112.76 -7.26, 112.75 -7.26, 112.75 -7.25))');

// Set area from coordinates
$cabang->setPolygonFromCoordinates([
    [112.75, -7.25],
    [112.76, -7.25], 
    [112.76, -7.26],
    [112.75, -7.26]
]);

// Get polygon information
$area = $cabang->polygon_area_size; // Area in sq meters
$center = $cabang->polygon_center; // Centroid coordinates
$coords = $cabang->polygon_coordinates; // Boundary points
```

## Test Data
Sample geometry data has been created for testing:

### Test Cabang (Code: TEST)
- **Location**: -7.2575, 112.7521
- **Polygon**: Small rectangular area around Surabaya
- **Area**: ~1.22 km²

### Test Rayon (Code: T1)  
- **Location**: -7.240, 112.750
- **Polygon**: Larger rectangular coverage area
- **Area**: ~19.54 km²

### Test SubRayon (Code: T1)
- **Location**: -7.245, 112.745
- **Polygon**: Smaller area within rayon
- **Area**: ~1.22 km²

## Filament Integration

### Form Fields
Geometry fields are available in Filament resources:
- Latitude/Longitude numeric inputs
- Polygon area textarea with WKT format
- Validation and formatting helpers

### Table Columns
Enhanced table displays include:
- Coordinate display (lat, lng)
- Polygon area with unit formatting (m² / km²)
- Toggleable geometry columns

### Example Usage in Resource
```php
// Form fields
Forms\Components\TextInput::make('latitude')
    ->label('Latitude')
    ->numeric()
    ->step(0.000001),

Forms\Components\Textarea::make('polygon_area')
    ->label('Area Polygon (WKT)')
    ->placeholder('POLYGON((lng lat, lng lat, ...))')
    ->hint('Format Well-Known Text untuk polygon area'),

// Table columns  
Tables\Columns\TextColumn::make('polygon_area_size')
    ->label('Area Polygon')
    ->formatStateUsing(function ($record) {
        $area = $record->polygon_area_size;
        return $area > 1000000 
            ? number_format($area / 1000000, 2) . ' km²'
            : number_format($area, 0) . ' m²';
    }),
```

## Technical Implementation

### Database Schema
- PostGIS extension enabled
- GEOMETRY(POLYGON, 4326) columns for boundaries
- GIST indexes for spatial performance
- WGS84 coordinate system (SRID 4326)

### Performance Considerations
- Spatial indexes (GIST) on geometry columns
- Distance calculations use geography casting for accuracy
- Polygon operations optimized with proper SRID handling

### Data Validation
- Coordinate range validation
- Polygon closure validation  
- WKT format validation
- SRID consistency checks

## Future Enhancements

### Potential Features
- Interactive map components in Filament
- Geocoding integration for address lookup
- Route optimization between locations
- Service area overlap detection
- Customer assignment based on location
- Spatial data import/export tools

### Integration Opportunities
- Google Maps / OpenStreetMap integration
- GPS tracking for field operations
- Location-based reporting and analytics
- Automated territory management
- Distance-based pricing models

## Maintenance Notes

### Regular Tasks
- Monitor spatial query performance
- Update polygon boundaries as needed
- Validate coordinate accuracy
- Clean up test data in production

### Troubleshooting
- Ensure PostGIS extension is installed
- Check SRID consistency (4326)
- Verify polygon closure (first = last point)
- Monitor geometry column constraints

## Testing

### Comprehensive Tests Completed
✅ Geometry columns migration  
✅ HasGeometry trait functionality  
✅ Distance calculations (1,949m between test points)  
✅ Point-in-polygon detection  
✅ Polygon area calculations (1.22 km² - 19.54 km²)  
✅ Spatial query scopes  
✅ WKT polygon management  
✅ Filament form integration  

The PostGIS integration is fully functional and ready for production use with comprehensive spatial capabilities for the CRM system.