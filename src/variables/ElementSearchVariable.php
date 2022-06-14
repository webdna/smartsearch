<?php
/**
 * Element Search plugin for Craft CMS 3.x
 *
 * One search to search them all
 *
 * @link      https://webdna.co.uk
 * @copyright Copyright (c) 2022 WebDNA
 */

namespace webdna\elementsearch\variables;

use webdna\elementsearch\ElementSearch;

use Craft;
use craft\db\Query;
use craft\elements\db\ElementQuery;
use craft\elements\Entry;
use craft\elements\Category;
use craft\elements\User;
use craft\commerce\elements\Product;
use yii\base\Behavior;
use Illuminate\Support\Collection;
use craft\helpers\StringHelper;
use craft\helpers\ArrayHelper;

/**
 * @author    WebDNA
 * @package   ElementSearch
 * @since     0.0.1
 */
class ElementSearchVariable extends Behavior
{
    // Public Methods
    // =========================================================================

    /**
     * @param null $optional
     * @return string
     */
    public function elementSearch(string $keyword, array $criteria = []): Collection
    {
        $results = collect();
        
        $elementTypes = $criteria['elements'] ?? null;
        
        unset($criteria['elements']);
        
        foreach (Craft::$app->getElements()->getAllElementTypes() as $elementType) {
            $shortType = explode('\\', $elementType);
            $shortType = end($shortType);
            
            if (is_null($elementTypes) || (in_array($shortType, $elementTypes) || in_array($elementType, $elementTypes))) {
                $query = $elementType::find();
                Craft::configure($query, array_merge(['search' => $keyword, 'orderBy' => 'score'], $criteria));
                $elements = $query->collect();
                $results = $results->merge($elements);
            }
        }
        
        if (isset($criteria['orderBy'])) {
            if (StringHelper::contains($criteria['orderBy'], 'desc')) {
                $results = $results->sortByDesc(str_replace(' desc', '', $criteria['orderBy']));
            } else {
                $results = $results->sortBy(str_replace(' asc', '', $criteria['orderBy']));
            }
        } else {
            $results = $results->sortByDesc('searchScore');
        }
        
        $total = $results->count();
        
        if (isset($criteria['limit'])) {
            $results = $results->slice(0, $criteria['limit']);
        }
        
        return $results;
    }
}
