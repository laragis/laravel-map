<?php

namespace TungTT\LaravelMap\Models\GeoNode;

use Illuminate\Database\Eloquent\Model;

class GeoNodeModel extends Model
{
    public function getConnectionName()
    {
        return $this->connection ?? env('GEONODE_CONNECTION_NAME');
    }
}