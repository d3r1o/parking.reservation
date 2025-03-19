<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

use Wv\Parking\Install;


Loc::loadMessages(__FILE__);

class wv_parking extends CModule
{
    public $MODULE_ID = "wv.parking";

    function __construct()
    {
        $arModuleVersion = [];
        include dirname(__FILE__) . '/version.php';

        if (
            is_array($arModuleVersion)
            && array_key_exists('VERSION', $arModuleVersion)
        ) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('WV_PARKING_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('WV_PARKING_MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('WV_PARKING_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('WV_PARKING_PARTNER_URI');

        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        Loader::includeModule($this->MODULE_ID);

        Install\File::install();
        Install\HighLoad::install();
        Install\Handlers::install();
        Install\Menu::install();
    }

    function DoUninstall()
    {
        global $APPLICATION;

        Loader::includeModule($this->MODULE_ID);

        $modulePath = $this->getModulePath();

        Install\HighLoad::uninstall();
        Install\File::uninstall();
        Install\Handlers::uninstall();
        Install\Menu::uninstall();

        UnRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('ADMIN_HELPER_INSTALL_TITLE'),
            $modulePath . '/install/unstep2.php'
        );
    }

    /**
     * @return string|null
     */
    private function getModulePath(): ?string
    {
        $documentRoot = Application::getDocumentRoot();
        $moduleFolder = 'modules';

        $modulePathBitrix = $documentRoot . '/' . Loader::BITRIX_HOLDER . '/' . $moduleFolder . '/' . $this->moduleName;
        $modulePathLocal = $documentRoot . '/' . Loader::LOCAL_HOLDER . '/' . $moduleFolder . '/' . $this->moduleName;

        if (is_dir($modulePathBitrix)) {
            return $modulePathBitrix;
        } elseif (is_dir($modulePathLocal)) {
            return $modulePathLocal;
        }

        return null;
    }
}