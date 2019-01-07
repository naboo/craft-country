<?php
namespace naboovalley\country\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\helpers\Db;

use yii\db\Schema;

/**
 * Country_MultipleCountries field class.
 *
 * @package     country
 * @author      Johan Strömqvist
 * @version     1.0.0
 */
class Country_MultipleCountries extends Field implements PreviewableFieldInterface
{
    // Constants
    // =========================================================================

    // Public Properties
    // =========================================================================

    // Inheritance
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('country', 'Countries');
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        /*$rules[] = [[$this->handle], 'safe'];*/
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        return $value !== '' ? json_encode($value) : null;
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate('country/_fields/countries',
            [
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        return $value;
    }
}