<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\UI\Buttons;
use Bitrix\Main\Localization\Loc;
use Bitrix\UI\Toolbar\Facade\Toolbar;

Toolbar::deleteFavoriteStar();

$addButton = new Buttons\AddButton([
    'click' => new Buttons\JsCode(
        'BX.WvParkingRemoveAction()'
    ),
    'text' => 'Удалить',
    'color' => Buttons\Color::DANGER
]);

if ($arParams['params']['id']) {
    Toolbar::addButton($addButton);
}

Loc::loadMessages(__FILE__);

$params = $arParams['params'] ?? [];
?>
<div class="container">
    <input class="ui-ctl-element" type="hidden" name="ID" value="<?=$params['id']?>">
    <input class="ui-ctl-element" type="hidden" name="DATE" value="<?=$params['date']?>">
    <?if($params['spotId']):?>
        <input class="ui-ctl-element" type="hidden" name="SPOT_ID" value="<?=$params['spotId']?>">
    <?endif;?>

    <div><?= Loc::getMessage('CLIENT'); ?></div>
    <input class="ui-ctl-element" type="hidden" name="CLIENT" >
    <div id="clientField"></div>

    <div><?= Loc::getMessage('TIME_START'); ?></div>
    <div class="ui-ctl ui-ctl-textbox ui-ctl-w75">
        <select class="ui-ctl-element" name="TIME_START">
            <option value=""></option>
            <? for ($hour = 8; $hour <= 18; $hour++): ?>
                <? $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT); ?>
                <option
                    value="<?= $hour ?>"
                    <? if ($params['selectedTime'] == $hour || $params['start'] == $hour): ?>selected<? endif; ?>
                ><?= $formattedHour ?>:00</option>
            <? endfor; ?>
        </select>
    </div>

    <div><?= Loc::getMessage('TIME_END'); ?></div>
    <div class="ui-ctl ui-ctl-textbox ui-ctl-w75">
        <select class="ui-ctl-element" name="TIME_END">
            <option value=""></option>
            <? for ($hour = 8; $hour <= 18; $hour++): ?>
                <? $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT); ?>
                <option value="<?= $hour ?>"
                        <? if ($params['end'] == $hour): ?>selected<? endif; ?>
                ><?= $formattedHour ?>:00</option>
            <? endfor; ?>
        </select>
    </div>

    <div><?= Loc::getMessage('CAR_NUMBER'); ?></div>
    <div class="ui-ctl ui-ctl-w75">
        <input type="text" class="ui-ctl-element" name="CAR_NUMBER" value="<?=$params['car']['CAR_NUMBER']?>">
    </div>

    <div><?= Loc::getMessage('CAR_BRAND'); ?></div>
    <div class="ui-ctl ui-ctl-w75">
        <input type="text" class="ui-ctl-element" name="CAR_BRAND" value="<?=$params['car']['CAR_BRAND']?>">
    </div>

    <div><?= Loc::getMessage('CAR_COLOR'); ?></div>
    <div class="ui-ctl ui-ctl-w75">
        <input type="text" class="ui-ctl-element" name="CAR_COLOR" value="<?=$params['car']['CAR_COLOR']?>">
    </div>

    <div><?= Loc::getMessage('COMMENT'); ?></div>
    <div class="ui-ctl ui-ctl-textarea ui-ctl-no-resize ui-ctl-w75">
        <textarea name="COMMENT" class="ui-ctl-element" placeholder=""><?=$params['comment']?></textarea>
    </div>
</div>

<?
$APPLICATION->IncludeComponent('bitrix:ui.button.panel', '', [
    'BUTTONS' => [
        [
            'TYPE' => 'save',
            'ONCLICK' => "BX.WvParkingEditAction()",
        ],
        [
            'TYPE' => 'cancel',
        ],
    ],
]);
?>

<script>
    BX.ready(function () {
        let component = new WvEdit({
            componentName: '<?= $this->getComponent()->getName(); ?>',
            id: '<?= $params['id'] ?? 0; ?>',
            client: <?= CUtil::PhpToJsObject($params['client']); ?>,
        });

        BX.WvParkingEditAction = function () {
            component.editAction();
        }

        BX.WvParkingRemoveAction = function () {
            component.removeAction();
        }
    });
</script>
