<?php
namespace naboovalley\country\services;

use Craft;

use yii\base\Component;

use naboovalley\country\records\CountryRecord;

/**
 * Country service class.
 *
 * @package     modules/hubspot/services
 * @author      Johan Strömqvist
 * @version     1.0
 */
class CountryService extends Component
{
    // Public Properties
    // =========================================================================

    // Public Methods
    // =========================================================================

    /**
     * Get all countries from database
     *
     * @return array
     */
    public function getAllCountries()
    {
        return CountryRecord::find()->orderBy(['name' => SORT_ASC])->all();
    }

    /**
     * Get country
     *
     * @param str
     * @return array|null
     */
    public function getCountryByCode($countryCode)
    {
        return CountryRecord::find()->where(['isoAlpha2' => strtolower($countryCode)])->one();
    }

    /**
     * Get a visitors country code based on IP. Uses the GeoPlugin.net json API.
     *
     * @param ip
     * @return str
     */
    public function getCountryCodeFromIp($ip)
    {
        // Init call
        $ch = curl_init();

        // Call
        curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 2); // The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); // The maximum number of seconds to allow cURL functions to execute.

        // Response
        $response   = curl_exec($ch);

        // Data
        $json_data  = json_decode($response);

        $data = null;

        /*
        {
          "geoplugin_request":"94.254.112.78",
          "geoplugin_status":206,
          "geoplugin_credit":"Some of the returned data includes GeoLite data created by MaxMind, available from <a href=\\'http:\/\/www.maxmind.com\\'>http:\/\/www.maxmind.com<\/a>.",
          "geoplugin_city":"",
          "geoplugin_region":"",
          "geoplugin_areaCode":"0",
          "geoplugin_dmaCode":"0",
          "geoplugin_countryCode":"SE",
          "geoplugin_countryName":"Sweden",
          "geoplugin_continentCode":"EU",
          "geoplugin_latitude":"62",
          "geoplugin_longitude":"15",
          "geoplugin_regionCode":"",
          "geoplugin_regionName":null,
          "geoplugin_currencyCode":"SEK",
          "geoplugin_currencySymbol":"&#107;&#114;",
          "geoplugin_currencySymbol_UTF8":"kr",
          "geoplugin_currencyConverter":7.1838
        }
        */

        // Did we get a response?
        if(isset($json_data->geoplugin_countryCode))
        {
            $country = CountryRecord::find()->where(['isoAlpha2' => strtolower($json_data->geoplugin_countryCode)])->one();

            $data = $country;
        }

        // Close
        curl_close($ch);

        return $data;
    }

    // Private Methods
    // =========================================================================
}
