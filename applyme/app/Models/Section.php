<?php

namespace App\Models;

use App\Models\AppModel;

class Section extends AppModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "sections";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];


    // RELATIONSHIPS -----------------------------------------------------------

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
     * Get the browse queries associated
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function browseQueries()
    {
       return $this->hasMany(BrowseQuery::class);
    }

    // SCOPES ----------------------------------------------------------------

    /**
     * Scope a query to only include majors section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMajors($query)
    {
        return $query->where('name', 'like', 'majors');
    }

    /**
     * Scope a query to only include careers section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCareers($query)
    {
        return $query->where('name', 'like', 'careers');
    }

    /**
     * Scope a query to only include interests section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInterests($query)
    {
        return $query->where('name', 'like', 'interests');
    }

    /**
     * Scope a query to only include ethnicities section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEthnicities($query)
    {
        return $query->where('name', 'like', 'ethnicities');
    }

    /**
     * Scope a query to only include religions section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReligions($query)
    {
        return $query->where('name', 'like', 'religions');
    }

    /**
     * Scope a query to only include disabilities section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabilities($query)
    {
        return $query->where('name', 'like', 'disabilities');
    }

    /**
     * Scope a query to only include states section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStates($query)
    {
        return $query->where('name', 'like', 'states');
    }

    /**
     * Scope a query to only include memberships section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMemberships($query)
    {
        return $query->where('name', 'like', 'memberships');
    }

    /**
     * Scope a query to only include military affiliations section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMilitaryAffiliations($query)
    {
        return $query->where('name', 'like', 'military affiliations');
    }

    /**
     * Scope a query to only include sports section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSports($query)
    {
        return $query->where('name', 'like', 'sports');
    }

    /**
     * Scope a query to only include special circumstances section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCircumstances($query)
    {
        return $query->where('name', 'like', 'special attributes and circumstances');
    }

    /**
     * Scope a query to only include scholarhips for women section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForWomen($query)
    {
        return $query->where('name', 'like', 'scholarships for women');
    }

    /**
     * Scope a query to only include easy scholarships section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEasyScholarships($query)
    {
        return $query->where('name', 'like', 'easy scholarships');
    }

    /**
     * Scope a query to only include scholarships by school year section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScholarshipsBySchoolYear($query)
    {
        return $query->where('name', 'like', 'scholarships by school year');
    }

    /**
     * Scope a query to only include scholarships for specific universities section.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeScholarshipsForSpecificUniversities($query)
    {
        return $query->where('name', 'like', 'scholarships for specific universities');
    }

}
