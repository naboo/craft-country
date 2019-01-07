<?php
namespace naboovalley\country;

use Craft;
use craft\base\Plugin;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use craft\services\Plugins;

use yii\base\Event;

use naboovalley\country\models\SettingsModel;
use naboovalley\country\services\CountryService;
use naboovalley\country\variables\CountryVariable;
use naboovalley\country\fields\Country_Countries;
use naboovalley\country\fields\Country_MultipleCountries;

/**
 * Country plugin class.
 *
 * @package     country
 * @author      Johan Strömqvist
 * @version     1.0.0
 */
class CountryPlugin extends Plugin
{
    // Constants
    // =========================================================================

    // Public Properties
    // =========================================================================

    /**
     * @var Ecommerce
     */
    public static $plugin;

    /**
     * @var Settings
     */
    public static $settings;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Inheritance
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        self::$plugin = $this;
        self::$settings = $this->getSettings();

        // Register service components
        $this->setComponents([
            'country' => CountryService::class,
        ]);

        // Register Site URL rules
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function (RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, $this->getSiteRoutes());
            }
        );

        // Register CP URL rules
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function (RegisterUrlRulesEvent $event) {
                $event->rules = array_merge($event->rules, $this->getCpRoutes());
            }
        );

        // Register "Variable" Twig template classes
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function (Event $event) {
                $variable = $event->sender;
                $variable->set('country', CountryVariable::class);
            }
        );

        // Register additional events efter Plugin has loaded
        Event::on(Plugins::class, Plugins::EVENT_AFTER_LOAD_PLUGINS, function () {

                $request = Craft::$app->getRequest();

                // Only for front-end site requests
                /*if($request->getIsSiteRequest() && !$request->getIsConsoleRequest())
                {
                    $this->handleSiteRequest();
                }*/
            }
        );

        // Register field types
        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
                $event->types[] = Country_Countries::class;
                $event->types[] = Country_MultipleCountries::class;
            }
        );

        Craft::info(Craft::t('country', '{name} plugin loaded', ['name' => $this->name]), __METHOD__);
    }

    // Public Methods
    // =========================================================================

    // Private Methods
    // =========================================================================

    // Protected Methods
    // =========================================================================

    /**
     * Create settings model
     *
     * @return SettingsModel
     */
    protected function createSettingsModel()
    {
        return new SettingsModel();
    }

    /**
     * Define CP routes
     *
     * @return array
     */
    protected function getCpRoutes()
    {
        return [
        ];
    }

    /**
     * Define Site routes
     *
     * @return array
     */
    protected function getSiteRoutes()
    {
        return [
        ];
    }

    /**
     * Handle site request
     *
     */
    protected function handleSiteRequest()
    {
        // Set user(guest) currency by country code IP
        if(!$countryData = Craft::$app->session->get('country_countryData'))
        {
            Craft::info(Craft::t('country', 'Country code not set for IP {ip}', ['ip' => Craft::$app->request->userIP]), __METHOD__);

            // Check what country the visitor comes from based on IP
            $countryData = $this->country->getCountryCodeFromIp(Craft::$app->request->userIP);

            // Add data to session
            Craft::$app->session->set('country_countryData', $countryData);

        } else {

            Craft::info(Craft::t('country', 'Country code is {code} for IP {ip}', ['ip' => Craft::$app->request->userIP, 'code' => $countryData['countryCode']]), __METHOD__);
        }
    }
}
