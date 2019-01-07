<?php
namespace naboovalley\country\migrations;

use Craft;
use craft\db\Query;
use craft\config\DbConfig;
use craft\db\Migration;

use naboovalley\country\records\CountryRecord;

/**
 * @author    Johan Strömqvist
 * @package   Ecommerce
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
        $this->insertDefaultData();

        Craft::$app->db->schema->refresh();

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $this->createTable(
            '{{%countries}}',
            [
                'id' => $this->primaryKey(11)->unsigned(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'name' =>$this->string(200)->null(),
                'isoAlpha2' => $this->string(2)->null(),
                'isoAlpha3' => $this->string(3)->null(),
                'isoNumeric' => $this->string(11)->null(),
                'currencyCode' => $this->string(3)->null(),
                'currencyName' => $this->string(32)->null(),
                'currencySymbol' => $this->string(3)->null(),
                'flag' => $this->string(6)->null(),
            ]
        );
    }

    /**
     * @return void
     */
    /*protected function createIndexes()
    {
    }*/

    /**
     * @return void
     */
    /*protected function addForeignKeys()
    {
    }*/

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
        // Get path to SQL-dump file
        $path = Craft::getAlias("@naboovalley/country/sql/countries.sql");

        // Dump SQL-data
        $query = Craft::$app->db->createCommand(file_get_contents($path))->execute();

        // Get all newly added entries from dump-file
        $entries = (new Query)->select('*')->from('craft_countries_DUMP')->all();

        // Loop and add into new table
        foreach($entries as $entry)
        {
            $record = new CountryRecord;

            $record->name = $entry['name'];
            $record->isoAlpha2 = $entry['iso_alpha2'];
            $record->isoAlpha3 = $entry['iso_alpha3'];
            $record->isoNumeric = $entry['iso_numeric'];
            $record->currencyCode = $entry['currency_code'];
            $record->currencyName = $entry['currency_name'];
            $record->currencySymbol = $entry['currrency_symbol'];
            $record->flag = $entry['flag'];

            $record->save();
        }

        // Drop temp-table
        $this->dropTableIfExists('craft_countries_DUMP');
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('craft_countries');
        $this->dropTableIfExists('craft_countries_DUMP');
    }
}
