<?php

namespace App\Models;

use App\Models\AppModel;
use GrahamCampbell\Markdown\Facades\Markdown;

class Metadata extends AppModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "metadatas";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'metadatable_id',
        'metadatable_type',
        'description',
        'h1',
        'meta_title',
        'meta_description',
        'link',
        'link_title'
    ];

    // RELATIONSHIPS -----------------------------------------------------------

    /**
     * Get all of the owning metadatable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metadatable()
    {
        return $this->morphTo();
    }

    // MUTATORS ----------------------------------------------------------------

    /**
     * Set the meta title.
     *
     * @param  string  $value
     * @return void
     */
    public function setMetaTitleAttribute($value)
    {
        $this->attributes['meta_title'] = $this->removeWhiteSpaces($value);
    }

    /**
     * Set the meta description.
     *
     * @param  string  $value
     * @return void
     */
    public function setMetaDescriptionAttribute($value)
    {
        $this->attributes['meta_description'] = $this->removeWhiteSpaces($value);
    }

    /**
     * Set the h1.
     *
     * @param  string  $value
     * @return void
     */
    public function setH1Attribute($value)
    {
        $this->attributes['h1'] = $this->removeWhiteSpaces($value);
    }

    /**
     * Set the description.
     *
     * @param  string  $value
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $this->removeWhiteSpaces($value);
    }

    // ACCESSORS ---------------------------------------------------------------

    /**
     * Get the meta title.
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->parseDate($this->meta_title);
    }

    /**
     * Get the meta description.
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->parseDate($this->meta_description);
    }

    /**
     * Get the description.
     *
     * @return string
     */
    public function getDescription()
    {
        return Markdown::convertToHtml($this->parseDate($this->description));
    }

    /**
     * Get the h1.
     *
     * @return string
     */
    public function getH1()
    {
        return $this->parseDate($this->h1);
    }

    /**
     * Get the link.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->parseDate($this->link);
    }

    /**
     * Get the link title.
     *
     * @return string
     */
    public function getLinkTitle()
    {
        return $this->parseDate($this->link_title);
    }


    // CUSTOM ------------------------------------------------------------------

    /**
     * Remove extra 2 or more white spaces.
     *
     * @param  string  $content
     * @return string
     */
    protected function removeWhiteSpaces($content)
    {
        return trimWhiteSpaces(trim($content));
    }

}
