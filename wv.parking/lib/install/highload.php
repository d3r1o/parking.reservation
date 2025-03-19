<?php
namespace Wv\Parking\Install;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\SystemException;
use CUserTypeEntity;

Loader::includeModule("highloadblock");

class HighLoad
{
    public static function install()
    {
        $cityHlId = self::createHighloadBlock(
            'ParkingCities',
            'parking_cities',
            [
                'UF_NAME' => [
                    'type' => 'string',
                    'settings' => [
                        'SIZE' => 20,
                        'ROWS' => 1,
                        'REGEXP' => '',
                        'MIN_LENGTH' => 0,
                        'MAX_LENGTH' => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                ],
            ],
        );

        $spotHlId = self::createHighloadBlock(
            'ParkingSpots',
            'parking_spots',
            [
                'UF_NAME' => [
                    'type' => 'string',
                    'settings' => [
                        'SIZE' => 20,
                        'ROWS' => 1,
                        'REGEXP' => '',
                        'MIN_LENGTH' => 0,
                        'MAX_LENGTH' => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                ],
                'UF_CITY' => [
                    'type' => 'hlblock',
                    'settings' => [
                        'HLBLOCK_ID' => $cityHlId,
                    ],
                ],
            ],
        );

        self::createHighloadBlock(
            'ParkingReservations',
            'parking_reservations',
            [
                'UF_CLIENT' => [
                    'type' => 'string',
                    'settings' => [
                        'SIZE' => 20,
                        'ROWS' => 1,
                        'REGEXP' => '',
                        'MIN_LENGTH' => 0,
                        'MAX_LENGTH' => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                ],
                'UF_START_DATETIME' => [
                    'type' => 'datetime',
                    'settings' => [
                        'USE_SECOND' => 'N',
                        'USE_TIMEZONE' => 'N',
                    ],
                ],
                'UF_END_DATETIME' => [
                    'type' => 'datetime',
                    'settings' => [
                        'USE_SECOND' => 'N',
                        'USE_TIMEZONE' => 'N',
                    ],
                ],
                'UF_CAR_DATA' => [
                    'type' => 'string',
                    'settings' => [
                        'SIZE' => 20,
                        'ROWS' => 1,
                        'REGEXP' => '',
                        'MIN_LENGTH' => 0,
                        'MAX_LENGTH' => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                ],
                'UF_COMMENT' => [
                    'type' => 'string',
                    'settings' => [
                        'SIZE' => 20,
                        'ROWS' => 1,
                        'REGEXP' => '',
                        'MIN_LENGTH' => 0,
                        'MAX_LENGTH' => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                ],
                'UF_SPOT' => [
                    'type' => 'hlblock',
                    'settings' => [
                        'HLBLOCK_ID' => $spotHlId,
                    ],
                ],
            ],
        );
    }

    public static function uninstall()
    {
        $hlblockNames = ['ParkingCities', 'ParkingSpots', 'ParkingReservations'];

        foreach ($hlblockNames as $hlblockName) {
            $hlblock = HL\HighloadBlockTable::getList([
                'select' => ['ID'],
                'filter' => ['NAME' => $hlblockName],
            ]);

            while($hl = $hlblock->fetch()){
                HL\HighloadBlockTable::delete($hl['ID']);
            }
        }
    }

    private static function createHighloadBlock(string $hlblockName, string $tableName, array $userFields, array $langNames = []): int
    {
        $hlblock = HL\HighloadBlockTable::getRow([
            'select' => ['ID'],
            'filter' => ['NAME' => $hlblockName],
        ]);

        if ($hlblock) {
            return $hlblock['ID'];
        }

        $result = HL\HighloadBlockTable::add([
            'NAME' => $hlblockName,
            'TABLE_NAME' => $tableName,
        ]);

        if (!$result->isSuccess()) {
            throw new SystemException(implode(', ', $result->getErrorMessages()));
        }

        $hlblockId = $result->getId();

        if (!empty($langNames)) {
            foreach ($langNames as $lang => $name) {
                HL\HighloadBlockLangTable::add([
                    'ID' => $hlblockId,
                    'LID' => $lang,
                    'NAME' => $name,
                ]);
            }
        }

        $userTypeEntity = new CUserTypeEntity();

        foreach ($userFields as $fieldName => $fieldSettings) {
            $userField = [
                'ENTITY_ID' => 'HLBLOCK_' . $hlblockId,
                'FIELD_NAME' => $fieldName,
                'USER_TYPE_ID' => $fieldSettings['type'],
                'MULTIPLE' => 'N',
                'MANDATORY' => 'N',
                'SHOW_FILTER' => 'N',
                'SHOW_IN_LIST' => 'Y',
                'EDIT_IN_LIST' => 'Y',
                'IS_SEARCHABLE' => 'N',
                'SETTINGS' => $fieldSettings['settings'] ?? [],
            ];

            $userTypeEntity->Add($userField);
        }

        return $hlblockId;
    }
}