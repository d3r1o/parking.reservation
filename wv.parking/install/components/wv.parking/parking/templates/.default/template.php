<?
use Bitrix\Main\Page\Asset;
use Bitrix\UI\Toolbar\Facade\Toolbar;

Toolbar::deleteFavoriteStar();

$assetInstance = Asset::getInstance();

$srcPath = $componentPath . '/templates/' . $templateName . '/src/';

$assetInstance->addJs($srcPath . 'jquery-ui.js');
$assetInstance->addCss($srcPath . 'jquery-ui.css');
$assetInstance->addJs($srcPath . 'datepicker-ru.js');
?>
<select id="citiChoose">
    <? foreach ($arResult['CITIES'] as $city): ?>
        <option value="<?= $city['ID']; ?>"><?= $city['NAME']; ?></option>
    <? endforeach; ?>
</select>
<div class="container <?if($arParams['WIDGET']):?>widget<?endif;?>">
    <div id="parkingCalendar" class="parking-calendar-container"></div>

    <div class="table-container">

        <div id="fixedHeader" class="fixed-header"></div>

        <table id="parkingTable">
            <thead>
            <tr id="headerRow"></tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<script>
    BX.ready(function () {
         new WvParking({
             parkingSpots: <?= CUtil::PhpToJsObject($arResult['SPOTS']); ?>,
             bookedSlots: <?= CUtil::PhpToJsObject($arResult['RESERVATIONS']); ?>,
             componentName: '<?= $this->getComponent()->getName(); ?>',
             wid: <?= CUtil::PhpToJsObject($arParams['WIDGET'] ?? []); ?>,
        });
    });
</script>