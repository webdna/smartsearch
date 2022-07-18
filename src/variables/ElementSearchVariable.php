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
        return ElementSearch::$plugin->service->ElementSearch($keyword, $criteria);
    }
}
