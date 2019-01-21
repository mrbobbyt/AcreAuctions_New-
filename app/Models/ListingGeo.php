<?php
declare(strict_types = 1);

namespace App\Models;

use App\Traits\ModelBuilderScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int listing_id
 * @property int acreage
 * @property string state
 * @property string county
 * @property string city
 * @property string address
 * @property int road_access
 * @property double longitude
 * @property double latitude
 */
class ListingGeo extends Model
{
    use ModelBuilderScopes;

    protected $fillable = [
        'listing_id', 'state', 'county', 'city', 'address', 'longitude', 'latitude', 'acreage', 'road_access'
    ];

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'listing_id'];


    /**
     * Get listing road access
     * @return BelongsTo
     */
    public function getRoadAccess()
    {
        return $this->belongsTo('App\Models\RoadAccess', 'road_access', 'id');
    }
}
