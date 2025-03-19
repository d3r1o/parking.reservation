<?php

namespace Wv\Parking\Handlers;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

use Wv\Parking\Helpers\CrmTabManager;

class onEntityDetailsTabsInitialized
{
    public static function getEntityTab(Event $event): EventResult
    {
        $crmTabManager = new CrmTabManager([
            'entityId' => $event->getParameter('entityID'),
            'entityTypeId' => $event->getParameter('entityTypeID'),
            'tabs' => $event->getParameter('tabs'),
        ]);

        return new EventResult(EventResult::SUCCESS, [
            'tabs' => $crmTabManager->getTabs(),
        ]);
    }
}