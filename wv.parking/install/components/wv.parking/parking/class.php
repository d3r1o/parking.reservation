<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Engine\Contract\Controllerable;

use Wv\Parking\ORM;

class Parking extends \CBitrixComponent implements Controllerable
{

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loader::includeModule('wv.parking');
    }

    public function configureActions()
    {
        return [
            'refresh' => [
                'prefilters' => []
            ],
        ];
    }

    public function executeComponent()
    {
        $this->arResult['CITIES'] = ORM\CitiesTable::getList([
            'select' => ['ID', 'NAME' => 'UF_NAME']
        ])->fetchAll();

        if (!empty($this->arResult['CITIES'])) {
            $info = $this->getInfoByCityId([
                'cityId' => $this->arResult['CITIES'][0]['ID']
            ]);

            $this->arResult['SPOTS'] = $info['SPOTS'];
            $this->arResult['RESERVATIONS'] = $info['RESERVATIONS'];
        }

        Bitrix\Main\UI\Extension::load(['jquery3', 'ui.hint']);

        $this->includeComponentTemplate();
    }

    public function getInfoByCityId($params): array
    {
        $result = [];

        $result['SPOTS'] = ORM\SpotsTable::getList([
            'select' => ['ID', 'NAME' => 'UF_NAME'],
            'filter' => ['UF_CITY' => $params['cityId']]
        ])->fetchAll();

        $rsReservation = ORM\ReservationsTable::getList([
            'select' => [
                '*',
                'CITY_' => 'CITY'
            ],
            'filter' => [
                'CITY_ID' => $params['cityId'],
                '>=UF_START_DATETIME' => (new DateTime($params['date']))->setTime(0, 0),
                '<=UF_END_DATETIME' => (new DateTime($params['date']))->setTime(23, 59),
            ],
        ]);

        while ($reservation = $rsReservation->fetch()) {
            $result['RESERVATIONS'][] = [
                'id' => $reservation['ID'],
                'spot' => $reservation['UF_SPOT'],
                'car' => json_decode($reservation['UF_CAR_DATA'], true),
                'start' => $reservation['UF_START_DATETIME']->format('G'),
                'end' => $reservation['UF_END_DATETIME']->format('G'),
                'comment' => $reservation['UF_COMMENT'],
                'client' => json_decode($reservation['UF_CLIENT'], true),
            ];
        }

        return $result;
    }

    public function refreshAction($cityId, $date): array
    {
        return $this->getInfoByCityId([
            'cityId' => $cityId,
            'date' => $date
        ]);
    }
}