<?php

namespace App\Models;

use App\Models\AppModel;

class BrowseQuery extends AppModel
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "browse_queries";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'section_id',
        'category_id',
        'major_id',
    ];

    /**
     * Get the metadata associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function metadata()
    {
        return $this->morphOne(Metadata::class, 'metadatable');
    }

    /**
     * Get the section associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }


    // CUSTOM ------------------------------------------------------------------

    /**
     * Get the query params.
     *
     * @return array
     */
    public function getParams()
    {
        return [
            'categoryId' => $this->category_id,
            'majorId'    => $this->major_id,
        ];
    }
}
