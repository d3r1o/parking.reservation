<?php

namespace Wv\Parking\ORM;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;

/**
 * Class ReservationsTable
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> UF_SPOT int optional
 * <li> UF_START_DATETIME datetime optional
 * <li> UF_END_DATETIME datetime optional
 * <li> UF_CAR_DATA text optional
 * <li> UF_COMMENT text optional
 * <li> UF_CLIENT text optional
 * </ul>
 * @package Wv\Parking\ORM
 **/
class ReservationsTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'parking_reservations';
    }

    /**
     * Returns entity map definition.
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
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_ID_FIELD'),
                ]
            ),
            new IntegerField(
                'UF_SPOT',
                [
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_UF_SPOT_FIELD'),
                ]
            ),
            new DatetimeField(
                'UF_START_DATETIME',
                [
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_UF_START_DATETIME_FIELD'),
                ]
            ),
            new DatetimeField(
                'UF_END_DATETIME',
                [
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_UF_END_DATETIME_FIELD'),
                ]
            ),
            new TextField(
                'UF_CAR_DATA',
                [
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_UF_CAR_DATA_FIELD'),
                ]
            ),
            new TextField(
                'UF_COMMENT',
                [
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_UF_COMMENT_FIELD'),
                ]
            ),
            new TextField(
                'UF_CLIENT',
                [
                    'title' => Loc::getMessage('RESERVATIONS_ENTITY_UF_CLIENT_FIELD'),
                ]
            ),

            (new Reference(
                'SPOT',
                SpotsTable::class,
                Join::on('this.UF_SPOT', 'ref.ID')
            ))->configureJoinType(Join::TYPE_INNER),

            (new Reference(
                'CITY',
                CitiesTable::class,
                Join::on('this.SPOT.UF_CITY', 'ref.ID')
            ))->configureJoinType(Join::TYPE_INNER),
        ];
    }
}