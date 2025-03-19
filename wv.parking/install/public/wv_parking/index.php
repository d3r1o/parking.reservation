<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

global $APPLICATION;

$APPLICATION->SetTitle("Расписание парковки");
?>

<?
$APPLICATION->IncludeComponent(
    "wv.parking:parking",
    "",
    [],
    false
);
?>

<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php'); ?>