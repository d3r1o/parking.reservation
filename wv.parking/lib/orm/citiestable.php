<?php

namespace Wv\Parking\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;

/**
 * Class CitiesTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_NAME text optional
 * </ul>
 *
 * @package Wv\Parking\ORM
 **/

class CitiesTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'parking_cities';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('CITIES_ENTITY_ID_FIELD'),
                ]
            ),
            new TextField(
                'UF_NAME',
                [
                    'title' => Loc::getMessage('CITIES_ENTITY_UF_NAME_FIELD'),
                ]
            ),
        ];
    }
}