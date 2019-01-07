<?php
namespace naboovalley\country\models;

use craft\base\Model;

/**
 * Craft settings model class.
 *
 * @package     country/models
 * @author      Johan Strömqvist
 * @version     1.0
 */
class SettingsModel extends Model
{
    // Constants
    // =========================================================================

    // Protected Properties
    // =========================================================================

    // Public Properties
    // =========================================================================

    public $pluginName = "Country";

    // Inheritance
    // =========================================================================

    /**
     * Define rules
     */
    public function rules()
    {
        /*return [
            [
                [
                    'var'
                ], 
                'required'
            ],
        ];*/

        return [];
    }

    /**
     * Init
     */
    public function init()
    {
        //$this->var = getenv('VAR');
    }

    // Public Methods
    // =========================================================================

    // Private Methods
    // =========================================================================
}
