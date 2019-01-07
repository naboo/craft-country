<?php
namespace naboovalley\country\variables;

use Craft;
use craft\helpers\UrlHelper;

use naboovalley\country\CountryPlugin;

/**
 * Country variable class.
 *
 * @package     naboovalley/country/variables
 * @author      Johan Strömqvist
 * @version     1.0
 */
class CountryVariable
{
    // Public Methods
    // =========================================================================

    // Global
    // -------------------------------------------------------------------------

    /**
     * Get all countries
     *
     * @param bool
     * @return array|null
     */
    public function getAllCountries($countryCodesOnly=false)
    {
        // Get
        $results = CountryPlugin::getInstance()->country->getAllCountries();

        // Swap object array to new array with "countryCode" as index
        if($countryCodesOnly)
        {
            $list = array();

            foreach($results as $item)
            {
                $list[$item->isoAlpha2] = $item;
            }

            return $list;
        }

        return $results;
    }

    /**
     * Get country
     *
     * @param str
     * @return array|null
     */
    public function getCountryByCode($countryCode)
    {
        return CountryPlugin::getInstance()->country->getCountryByCode($countryCode);
    }

    // User
    // -------------------------------------------------------------------------

    /**
     * Get a users country from session
     *
     * @param str
     */
    public function getUserCountry()
    {
        $countryData = null;

        if(!$countryData = Craft::$app->session->get('country_countryData'))
        {
            // Check what country the visitor comes from based on IP
            $countryData = CountryPlugin::getInstance()->country->getCountryCodeFromIp(Craft::$app->request->userIP);

            Craft::$app->session->set('country_countryData', $countryData);
        }

        return $countryData;
    }
}
