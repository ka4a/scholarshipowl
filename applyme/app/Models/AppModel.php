<?php

namespace App\Models;

use App\Traits\MetadataParserTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AppModel extends Model
{
    use MetadataParserTrait;

    // MUTATORS ----------------------------------------------------------------

    /**
     * Set the slug.
     *
     * @param  string  $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        // A slug can be generated either from a name or a title.
        $source = isset($this->attributes['name'])
            ? $this->attributes['name']
            : $this->attributes['title'];

        // A slug can be generated automaticaly or custom.
        $this->attributes['slug'] = empty($value)
            ? str_slug(mb_strtolower($source))
            : str_slug(mb_strtolower($value), '-');
    }

    // ACCESSORS ---------------------------------------------------------------

    // CUSTOMS -----------------------------------------------------------------

    /**
     * Parse and store metadata for the given model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $model
     * @return \App\Model\Metadata
     */
    public function createMetadata(Request $request, $model)
    {
        return $this->metadata()->create([
            'meta_title'       => $model->evalString($request->meta_title),
            'meta_description' => $model->evalString($request->meta_description),
            'description'      => $model->evalString($request->description),
            'h1'               => $model->evalString($request->h1),
            'link'             => $model->evalString($request->link),
            'link_title'       => $model->evalString($request->link_title),
        ]);
    }

    /**
     * Parse and update metadata for the given model.
     *
     * @param  mixed  $values
     * @param  mixed  $model
     * @return void
     */
    public function updateMetadata($values, $model)
    {
        $attributes = [
            'meta_title'       => $model->evalString($values->meta_title),
            'meta_description' => $model->evalString($values->meta_description),
            'description'      => $model->evalString($values->description),
            'h1'               => $model->evalString($values->h1),
            'link'             => $model->evalString($values->link),
            'link_title'       => $model->evalString($values->link_title),
        ];

        $this->metadata()->update($attributes);
    }

}
