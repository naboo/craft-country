<?php
namespace naboovalley\country\records;

use Craft;
use craft\db\ActiveRecord;

/**
 * Country record class.
 *
 * @package     ecommerce
 * @author      Johan Strömqvist
 * @version     1.0
 */
class CountryRecord extends ActiveRecord
{
    // Constants
    // =========================================================================

    // Protected Properties
    // =========================================================================

    // Public Properties
    // =========================================================================

    // Inheritance
    // =========================================================================

    /**
     * Define table name
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * Define rules
     */
    public function rules()
    {
        return [
            // Defaults
            [['name', 'isoAlpha2', 'isoAlpha3', 'isoNumeric', 'currencyCode', 'currencyName', 'currencySymbol'], 'default', 'value' => null],
            // Safe
            [['name', 'isoAlpha2', 'isoAlpha3', 'isoNumeric', 'currencyCode', 'currencyName', 'currencySymbol'], 'safe'],
        ];
    }

    // Public Methods
    // =========================================================================

    // Private Methods
    // =========================================================================

    // Relations
    // =============================================================================
}
