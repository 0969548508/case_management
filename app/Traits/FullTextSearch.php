<?php

namespace App\Traits;

use Auth;
use stdClass;
use Log;

trait FullTextSearch
{
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * Scope a query that matches a full text search of term.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $slug, $term, $take = 10)
    {
        $columns = implode(',', $this->searchable);
        $keyword = $this->fullTextWildcards($term);
        $items = new stdClass;

        if (!empty($columns) && !empty($keyword)) {
            switch ($slug) {
                case 'user':
                    $items = $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $keyword)
                        ->orWhereHas('emails', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (email) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('phones', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (phone_number) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('licenses', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (country, state, `number`) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('addresses', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (address, country, state, city, postal_code) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('accreditations', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (qualification) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('equipments', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (type, model) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('offices', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, address) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('types', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name) AGAINST (? IN BOOLEAN MODE)", $keyword)
                            ->orWhereHas('parent', function($relation) use ($keyword){
                                    $relation->whereRaw("MATCH (name) AGAINST (? IN BOOLEAN MODE)", $keyword);
                            });
                        })->take($take)->get();
                    break;

                case 'matter':
                    $items = $query->where('user_id', auth()->user()->id)
                        ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $keyword)
                        ->orWhereHas('locations', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, abn) AGAINST (? IN BOOLEAN MODE)", $keyword)
                                ->orWhereHas('priceLocations', function($relation) use ($keyword){
                                    $relation->whereRaw("MATCH (name, description) AGAINST (? IN BOOLEAN MODE)", $keyword);
                            });
                        })
                        ->orWhereHas('clients', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, abn) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('types', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name) AGAINST (? IN BOOLEAN MODE)", $keyword)
                            ->orWhereHas('parent', function($relation) use ($keyword){
                                    $relation->whereRaw("MATCH (name) AGAINST (? IN BOOLEAN MODE)", $keyword);
                            });
                        })
                        ->orWhereHas('offices', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, address) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('users', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, family_name, middle_name, email) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })->take($take)->get();
                    break;

                case 'client':
                    $items = $query->where('user_id', auth()->user()->id)
                        ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $keyword)
                        ->orWhereHas('locations', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, abn) AGAINST (? IN BOOLEAN MODE)", $keyword)
                                ->orWhereHas('priceLocations', function($relation) use ($keyword){
                                    $relation->whereRaw("MATCH (name, description) AGAINST (? IN BOOLEAN MODE)", $keyword);
                            });
                        })
                        ->orWhereHas('contacts', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, job_title, email, phone, mobile, fax, note) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })
                        ->orWhereHas('priceClients', function($relation) use ($keyword){
                            $relation->whereRaw("MATCH (name, description) AGAINST (? IN BOOLEAN MODE)", $keyword);
                        })->take($take)->get();
                    break;

                default:
                    # code...
                    break;
            }
        }

        return $items;
    }
}