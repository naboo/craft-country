# Country plugin for Craft CMS 3.x

Custom country integration Plugin for Craft CMS. A set of functions to use country codes, names and other related country information through Twig and/or the Plugin service classes.

## Contents

- [License](#license)
- [Requirements](#installation)
- [Usage](#usage)
- [How It Works](#how-it-works)
- [Roadmap](#roadmap)
- [Credits](#credits)

## License

This plugin is licensed for free under the MIT License. Please see the LICENSE file for details.

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Usage

Install the plugin manually (locally) using composer.

```
composer require naboovalley/craft-country
```

Once the plugin is installed you can start using its build in Twig tags to interact with country code and other related functions. Please see the [How It Works](#how-it-works) section.

## How It Works

### Field types

There are two field types that work in a similar way. The first field type is a dropdown letting the user select one (1) country. 

The second field type allows the user to select multiple countries using a list of checkboxes. The field type stores the selected country/countries country code(s) which are returned in Twig.

### Twig

```twig
{% set countries = craft.country.getAllCountries(COUNTRY_CODES_ONLY) %}

{% for country in countries %}
  {{ country.name }}
  {{ country.isoAlpha2 }}
  {{ country.isoAlpha3 }}
  {{ country.isoNumeric }}
  {{ country.currencyCode }}
  {{ country.currencyName }}
  {{ country.currencySymbol }}
{% endfor %}
```

Returns a list of all countries. If param `COUNTRY_CODES_ONLY` is set to `true` the list is returned in a key => item format with country codes as keys `('sv' => "Sweden", 'is' => "Island")`.

```twig
{% set country = craft.country.getCountryByCode(code) %}

{{ country.name }}
{{ country.isoAlpha2 }}
{{ country.isoAlpha3 }}
{{ country.isoNumeric }}
{{ country.currencyCode }}
{{ country.currencyName }}
{{ country.currencySymbol }}
```

Returns country details by country code. The `code` parameter is required.

#### Store country data in user session

If you need to use the country information for your site visitors multiple times during the visit there are some functions for storing the data in the users session for repeated use (to save database queries). The first time any of these functions are used there's a call made to the `http://www.geoplugin.net/` service to determine the users location. *Note* this service is nowhere near perfect. The results may vary. I've used it on production sites with good results over the years _but_ there are edge cases were it just doesn't work as expected. Use fallbacks...

```twig
{% set userCountry = craft.country.getUserCountry() %}
```

Returns country information associated with the current user. The data is stored in the users session so repeated calls to the functions will fetch the data from the session.

### Services

More information to come.

## Roadmap

### Version X

- More functions
- Better documentation
- Drink more wine
- Plugin Store release

## Credits

Brought to you by [Johan Strömqvist](http://www.naboovalley.com)
