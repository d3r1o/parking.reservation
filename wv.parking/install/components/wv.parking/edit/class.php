<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Type\DateTime;
use Wv\Parking\ORM;


class Edit extends \CBitrixComponent implements Controllerable
{
    public function __construct($component = null)
    {
        parent::__construct($component);
        Loader::includeModule('wv.parking');
    }

    public function configureActions(): array
    {
        return [
            'update' => [
                'prefilters' => []
            ],
            'remove' => [
                'prefilters' => []
            ],
        ];
    }

    public function executeComponent()
    {
        Bitrix\Main\UI\Extension::load(['jquery3', "ui.buttons", "ui.forms", "ui.entity-selector"]);

        $this->includeComponentTemplate();
    }

    public function updateAction($params)
    {
        $fields = $this->prepareField($params);

        if ($params['ID']) {
            ORM\ReservationsTable::update($params['ID'], $fields);
        } else {
            ORM\ReservationsTable::add($fields);
        }

        return 'success';
    }

    public function removeAction($id)
    {
        if ($id) {
            ORM\ReservationsTable::delete($id);
        }

        return 'success';
    }

    public function prepareField($arField = []): array
    {
        $fields = [];

        if ($arField['SPOT_ID']) {
            $fields['UF_SPOT'] = $arField['SPOT_ID'];
        }

        $fields['UF_START_DATETIME'] = new DateTime($this->formatDateTime($arField['DATE'], $arField['TIME_START']));
        $fields['UF_END_DATETIME'] = new DateTime($this->formatDateTime($arField['DATE'], $arField['TIME_END']));

        $carInfo = [
            'CAR_BRAND' => $arField['CAR_BRAND'],
            'CAR_COLOR' => $arField['CAR_COLOR'],
            'CAR_NUMBER' => $arField['CAR_NUMBER'],
        ];

        $fields['UF_CAR_DATA'] = json_encode($carInfo);

        if ($arField['COMMENT']) {
            $fields['UF_COMMENT'] = $arField['COMMENT'];
        }
        if ($arField['CLIENT']) {
            $fields['UF_CLIENT'] = $arField['CLIENT'];
        }

        return $fields;
    }


    private function formatDateTime($date, $hour): string
    {
        $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
        $time = "{$formattedHour}:00:00";

        return "{$date} {$time}";
    }
}
