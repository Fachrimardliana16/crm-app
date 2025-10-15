<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasGeometry
{
    /**
     * Scope untuk mencari berdasarkan jarak dari titik tertentu
     */
    public function scopeWithinDistance($query, $latitude, $longitude, $distance = 1000)
    {
        return $query->whereRaw(
            "ST_DWithin(ST_SetSRID(ST_MakePoint(longitude, latitude), 4326), ST_SetSRID(ST_MakePoint(?, ?), 4326), ?)",
            [$longitude, $latitude, $distance]
        );
    }

    /**
     * Scope untuk mencari berdasarkan polygon area
     */
    public function scopeContainsPoint($query, $latitude, $longitude)
    {
        return $query->whereRaw(
            "ST_Contains(polygon_area, ST_SetSRID(ST_MakePoint(?, ?), 4326))",
            [$longitude, $latitude]
        );
    }

    /**
     * Get distance to a specific point in meters
     */
    public function getDistanceTo($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $result = DB::selectOne(
            "SELECT ST_Distance(
                ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography,
                ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography
            ) as distance",
            [$this->longitude, $this->latitude, $longitude, $latitude]
        );

        return $result ? round($result->distance, 2) : null;
    }

    /**
     * Check if a point is within the polygon area
     */
    public function containsPoint($latitude, $longitude)
    {
        if (!$this->polygon_area) {
            return false;
        }

        $table = $this->getTable();
        $result = DB::selectOne(
            "SELECT ST_Contains({$table}.polygon_area, ST_SetSRID(ST_MakePoint(?, ?), 4326)) as contains 
             FROM {$table} WHERE {$this->getKeyName()} = ?",
            [$longitude, $latitude, $this->getKey()]
        );

        return $result ? (bool) $result->contains : false;
    }

    /**
     * Get polygon area in square meters
     */
    public function getPolygonAreaSizeAttribute()
    {
        if (!$this->polygon_area) {
            return null;
        }

        $table = $this->getTable();
        $result = DB::selectOne(
            "SELECT ST_Area({$table}.polygon_area::geography) as area_size 
             FROM {$table} WHERE {$this->getKeyName()} = ?",
            [$this->getKey()]
        );

        return $result ? round($result->area_size, 2) : null;
    }

    /**
     * Set polygon from WKT string
     */
    public function setPolygonFromWKT($wkt)
    {
        $this->polygon_area = DB::raw("ST_GeomFromText('$wkt', 4326)");
        return $this;
    }

    /**
     * Set polygon from coordinates array
     * Format: [[lng, lat], [lng, lat], ...]
     */
    public function setPolygonFromCoordinates(array $coordinates)
    {
        if (count($coordinates) < 3) {
            throw new \InvalidArgumentException('Polygon requires at least 3 coordinates');
        }

        // Ensure polygon is closed
        if ($coordinates[0] !== $coordinates[count($coordinates) - 1]) {
            $coordinates[] = $coordinates[0];
        }

        $wkt = 'POLYGON((' . implode(',', array_map(function ($coord) {
            return $coord[0] . ' ' . $coord[1];
        }, $coordinates)) . '))';

        return $this->setPolygonFromWKT($wkt);
    }

    /**
     * Get polygon coordinates as array
     */
    public function getPolygonCoordinatesAttribute()
    {
        if (!$this->polygon_area) {
            return null;
        }

        $table = $this->getTable();
        $result = DB::selectOne(
            "SELECT ST_AsGeoJSON({$table}.polygon_area) as geojson 
             FROM {$table} WHERE {$this->getKeyName()} = ?",
            [$this->getKey()]
        );

        if ($result && $result->geojson) {
            $geojson = json_decode($result->geojson, true);
            return $geojson['coordinates'][0] ?? null;
        }

        return null;
    }

    /**
     * Get center point of the polygon
     */
    public function getPolygonCenterAttribute()
    {
        if (!$this->polygon_area) {
            return null;
        }

        $table = $this->getTable();
        $result = DB::selectOne(
            "SELECT ST_X(ST_Centroid({$table}.polygon_area)) as lng, ST_Y(ST_Centroid({$table}.polygon_area)) as lat 
             FROM {$table} WHERE {$this->getKeyName()} = ?",
            [$this->getKey()]
        );

        return $result ? [
            'latitude' => $result->lat,
            'longitude' => $result->lng
        ] : null;
    }

    /**
     * Scope untuk mencari area yang bersinggungan dengan polygon lain
     */
    public function scopeIntersectsWith($query, $wkt)
    {
        return $query->whereRaw(
            "ST_Intersects(polygon_area, ST_GeomFromText(?, 4326))",
            [$wkt]
        );
    }
}