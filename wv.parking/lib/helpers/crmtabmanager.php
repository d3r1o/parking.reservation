<?php

namespace Wv\Parking\Helpers;

use Bitrix\Main\Loader;


class CrmTabManager
{
    /**
     * @var array
     */
    public array $params = [];

    public function __construct($params)
    {
        Loader::includeModule('crm');

        $this->params = $params;
    }

    public function getTabs()
    {
        $entityId = $this->params['entityTypeId'];

        if ($entityId === \CCrmOwnerType::Deal || $entityId === \CCrmOwnerType::Lead) {
            return $this->getActualDealTabs();
        }

        return $this->params['tabs'];
    }

    public function getActualDealTabs()
    {
        $this->params['tabs'][] = [
            'id' => 'wv_parking',
            'name' => 'Расписание парковки',
            'loader' => [
                'serviceUrl' => '/local/components/wv.parking/parking/lazyload.ajax.php?&site=' . \SITE_ID . '&' . \bitrix_sessid_get(),
                'componentData' => [
                    'template' => '',
                    'params' => [
                        'WIDGET' => [
                            'entityId' => $this->specifyOwnerType($this->params['entityTypeId']),
                            'id' => $this->params['entityId'],
                        ],
                    ]
                ]
            ]
        ];

        return $this->params['tabs'];
    }

    private function specifyOwnerType($type)
    {
        if ($type === 1) {
            return 'lead';
        }
        if ($type === 2) {
            return 'deal';
        }
    }
}