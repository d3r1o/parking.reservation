<?php

namespace Wv\Parking\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;

/**
 * Class SpotsTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_CITY int optional
 * <li> UF_NAME text optional
 * </ul>
 *
 * @package Wv\Parking\ORM
 **/

class SpotsTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'parking_spots';
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
                    'title' => Loc::getMessage('SPOTS_ENTITY_ID_FIELD'),
                ]
            ),
            new IntegerField(
                'UF_CITY',
                [
                    'title' => Loc::getMessage('SPOTS_ENTITY_UF_CITY_FIELD'),
                ]
            ),
            new TextField(
                'UF_NAME',
                [
                    'title' => Loc::getMessage('SPOTS_ENTITY_UF_NAME_FIELD'),
                ]
            ),
        ];
    }
}