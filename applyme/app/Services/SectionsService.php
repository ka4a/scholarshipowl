<?php

namespace App\Services;

use App\Models\Section;
use Illuminate\Contracts\Cache\Repository as Cache;

class SectionsService
{
    /**
     * Cache instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * Section instance.
     *
     * @var \App\Models\Section
     */
    protected $section;

    /**
     * Constructor.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @param  \App\Models\Section  $section
     * @return void
     */
    public function __construct(
        Cache $cache,
        Section $section
    )
    {
        $this->cache   = $cache;
        $this->section = $section;
    }

    /**
     * Get all sections with metadata.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        // Fetch the sections and eager load the queries, then only keep 20
        // queries + the medatada. Cache the result.
        return $this->cache->rememberForever('sections', function() {
            return $this->section->with('metadata')->get();
        });
    }

    /**
     * Get all sections with metadata and queries.
     *
     * @param  int  $queries
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allWithQueries($queries = 10)
    {
        // Fetch the sections and eager load the queries, then only keep 20
        // queries + the medatada. Cache the result.
        return $this->cache->rememberForever('sections-with-queries', function() use ($queries) {
            return $this->section
                ->with('metadata', 'queries')
                ->get()
                ->each(function ($item, $key) use ($queries) {
                    $item->queries = $item->queries
                        ->shuffle()
                        ->take($queries)
                        ->sortBy(function($e) {
                        return $e->name;
                    });

                    $item->queries->load('metadata');
                });
        });
    }

    /**
     * Get the section with metadata and browse queries.
     *
     * @param  int  $queries
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function bySlugWithBrowseQueries($section, $queries = 10)
    {
        // Fetch the sections and eager load the queries, then only keep 20
        // queries + the medatada. Cache results.
        return $this->cache->remember("{$section}_with-queries", 1440, function() use ($section, $queries) {
            return $this->section
                ->where('slug', $section)
                ->with('metadata', 'browseQueries')
                ->get()
                ->each(function ($item, $key) use ($queries) {
                    $item->browseQueries = $item->browseQueries
                        ->shuffle()
                        ->take($queries)
                        ->sortBy('name');

                    $item->browseQueries->load('metadata');
                });
        })->first();
    }

}
