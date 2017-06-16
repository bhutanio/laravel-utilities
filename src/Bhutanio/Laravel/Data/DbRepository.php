<?php

namespace Bhutanio\Laravel\Data;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DbRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @param array $param
     * @param int $limit
     * @return DbSearchResult
     */
    public function search(array $param, $limit = 20)
    {
        array_multisort($param);

        return new DbSearchResult($param, 'Search Results', $this->model->paginate($limit));
    }

    protected function filterCommonKeywords($keywords)
    {
        $stopwords = [
            'a',
            'about',
            'an',
            'are',
            'as',
            'at',
            'be',
            'by',
            'com',
            'de',
            'en',
            'for',
            'from',
            'how',
            'i',
            'in',
            'is',
            'it',
            'la',
            'of',
            'on',
            'or',
            'that',
            'the',
            'this',
            'to',
            'was',
            'what',
            'when',
            'where',
            'who',
            'will',
            'with',
            'und',
            'the',
            'www',
        ];

        $keywords = array_unique($keywords);
        $keywords = array_filter($keywords, function ($keyword) use ($stopwords) {
            $keyword = mb_strtolower($keyword);
            $qkeyword = str_replace("'", '', $keyword);
            if (in_array($qkeyword, $stopwords)) {
                return null;
            }
            if (in_array($keyword, $stopwords)) {
                return null;
            }

            return $keyword;
        });
        $keywords = array_filter($keywords);

        return $keywords;
    }

    /**
     * Perform a fulltext search
     *
     * @param Model|Builder $query
     * @param string $column
     * @param string $q Search Query
     * @return mixed
     */
    protected function fulltextSearch($query, $column, $q)
    {
        list($search, $all, $filtered) = $this->fulltextClean($q);

        if (mb_strlen($search) < 3) {
            return $query->where($column, 'like', $this->formatSearchParameter($search));
        }

        if (starts_with($search, ['"', "'"]) && ends_with($search, ['"', "'"])) {
            return $query->whereRaw("MATCH($column) AGAINST(? IN BOOLEAN MODE)")->setBindings(["\"$search*\""]);
        }

        return $query
            ->whereRaw("MATCH($column) AGAINST(? IN BOOLEAN MODE)")->setBindings(["\"$search\""])
            ->orWhere(function ($q) use ($all, $filtered, $column) {
                $bindings = [];
                $keywords = $filtered;
                if (count($filtered) < 1) {
                    $keywords = $all;
                }
                foreach ($keywords as $keyword) {
                    $q->whereRaw("MATCH($column) AGAINST(? IN BOOLEAN MODE)");
                    $bindings[] = $keyword . '*';
                }
                $q->setBindings($bindings);
            });
    }

    /**
     * cleanup and breakdown search string into keywords
     *
     * @param string $string
     * @return array
     */
    protected function fulltextClean($string)
    {
        $search = preg_replace('/\*|\"/', '', $string);
        $search = preg_replace(['/[^\p{L}\p{N}_]+/u', '/[+\-><\(\)~*\"@]+/'], ' ', $search);
        $search = trim($search);

        $all = preg_split('/(\s|\-|\.)/', $search);
        $all = array_filter($all);

        $filtered = $this->filterCommonKeywords($all);

        return [$search, $all, $filtered];
    }

    protected function formatSearchParameter($keyword)
    {
        if (mb_strlen($keyword) < 3) {
            return $keyword . '%';
        }

        return '%' . $keyword . '%';
    }

    /**
     * Multi Search on child tables
     *
     * @param Model|Builder $query
     * @param array $child_ids
     * @param string $foreign_key
     * @param string $child
     * @param string $child_id
     * @return Builder
     */
    protected function searchChildren($query, $child_ids, $foreign_key, $child, $child_id)
    {
        $child_ids = (array)$child_ids;
        $child_ids = array_unique($child_ids);

        $parent = $query->getTable();

        if (!empty($child_ids)) {
            foreach ($child_ids as $key => $value) {
                $child_table = $child . '_' . $key;

                $query = $query->join($child . ' as ' . $child_table, $parent . '.id', '=',
                    $child_table . '.' . $foreign_key)->where($child_table . '.' . $child_id, $value);
            }
        }

        return $query;
    }
}