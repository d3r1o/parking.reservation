<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

global $APPLICATION;

$APPLICATION->ShowHeadStrings();

$APPLICATION->SetTitle('Бронь');

$request = Bitrix\Main\Context::getCurrent()->getRequest()->toArray();
?>

<?
$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    [
        'POPUP_COMPONENT_USE_BITRIX24_THEME' => 'Y',
        'DEFAULT_THEME_ID' => '.default',
        'POPUP_COMPONENT_NAME' => 'wv.parking:edit',
        'POPUP_COMPONENT_PARAMS' => $request,
        'USE_UI_TOOLBAR' => 'Y',
        'USE_PADDING' => false,
        'PLAIN_VIEW' => false,
        'PAGE_MODE' => false,
    ]
);
?>

<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>
