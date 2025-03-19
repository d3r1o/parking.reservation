<?php

namespace Wv\Parking\Install;

use Bitrix\Main\EventManager;

class Handlers
{
    /**
     * @return array
     */
    private static function getHandlers(): array
    {
        return [
            [
                'fromModuleId' => 'crm',
                'eventType' => 'onEntityDetailsTabsInitialized',
                'toClass' => 'Wv\Parking\Handlers\onEntityDetailsTabsInitialized',
                'toMethod' => 'getEntityTab',
            ],
        ];
    }

    /**
     * @return void
     */
    public static function install()
    {
        $instance = self::getInstance();
        $arHandlers = self::getHandlers();

        foreach ($arHandlers as $handler) {
            $instance->registerEventHandler(
                $handler['fromModuleId'],
                $handler['eventType'],
                'wv.parking',
                $handler['toClass'],
                $handler['toMethod'],
            );
        }
    }

    /**
     * @return void
     */
    public static function uninstall()
    {
        $instance = self::getInstance();
        $arHandlers = self::getHandlers();

        foreach ($arHandlers as $handler) {
            $instance->registerEventHandler(
                $handler['fromModuleId'],
                $handler['eventType'],
                'wv.parking',
                $handler['toClass'],
                $handler['toMethod'],
            );
        }
    }

    /**
     * @return mixed
     */
    private static function getInstance(): mixed
    {
        return EventManager::getInstance();
    }
}