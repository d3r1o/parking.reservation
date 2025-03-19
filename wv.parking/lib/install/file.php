<?php

namespace Wv\Parking\Install;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

class File
{
    public static function install()
    {
        $path = self::getModulePath() . '/install/';

        $documentRoot = Application::getDocumentRoot();

        CopyDirFiles($path . 'public', $documentRoot . '/', true, true);
        CopyDirFiles($path . 'components', $documentRoot . '/local/components', true, true);
    }

    public static function uninstall()
    {
        $documentRoot = Application::getDocumentRoot();

        $arDirectory = [
            $documentRoot . '/wv_parking',
            $documentRoot . '/local/components/wv.parking'
        ];

        foreach ($arDirectory as $ex) {
            Directory::deleteDirectory($ex);
        }
    }

    private static function getModulePath(): ?string
    {
        $documentRoot = Application::getDocumentRoot();
        $moduleFolder = 'modules';

        $modulePathBitrix = $documentRoot . '/' . Loader::BITRIX_HOLDER . '/' . $moduleFolder . '/wv.parking' ;
        $modulePathLocal = $documentRoot . '/' . Loader::LOCAL_HOLDER . '/' . $moduleFolder . '/wv.parking' ;

        if (is_dir($modulePathBitrix)) {
            return $modulePathBitrix;
        } elseif (is_dir($modulePathLocal)) {
            return $modulePathLocal;
        }

        return null;
    }
}