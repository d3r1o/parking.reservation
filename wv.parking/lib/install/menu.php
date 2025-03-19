<?php

namespace Wv\Parking\Install;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

class Menu
{
    private static string $MENU = '/.top.menu.php';

    private static function getMenuItems(): array
    {
        return [
            [
                Loc::getMessage('PAGE_TITLE'),
                '/wv_parking/',
                [],
                [],
                ''
            ]
        ];
    }

    public static function install()
    {
        $menu = self::getMenuItems();

        foreach ($menu as $item) {
            self::addMenuItem(self::$MENU, $item);
        }
    }

    public static function uninstall()
    {
        $menu = self::getMenuItems();

        foreach ($menu as $item) {
            self::delMenuItem(self::$MENU, $item);
        }
    }

    private static function addMenuItem($menuFile, $menuItem, $pos = -1)
    {
        if (Loader::includeModule('fileman')) {
            $result = self::getMenu($menuFile);

            $aMenuLinks = $result['aMenuLinks'];

            $found = false;
            foreach ($aMenuLinks as $item) {
                if ($item[1] == $menuItem[1]) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                if ($pos < 0 || $pos >= count($aMenuLinks)) {
                    $aMenuLinks[] = $menuItem;
                } else {
                    for ($i = count($aMenuLinks); $i > $pos; $i--) {
                        $aMenuLinks[$i] = $aMenuLinks[$i - 1];
                    }

                    $aMenuLinks[$pos] = $menuItem;
                }

                \CFileMan::SaveMenu(['s1', $menuFile], $aMenuLinks, $result['sMenuTemplate']);
            }
        }
    }

    private static function delMenuItem($menuFile, $menuItem)
    {
        if (Loader::includeModule('fileman')) {
            $result = self::getMenu($menuFile);

            foreach ($result['aMenuLinks'] as $i => $item) {
                if ($item[1] == $menuItem[1]) {
                    unset($result['aMenuLinks'][$i]);
                }
            }

            \CFileMan::SaveMEnu(['s1', $menuFile], $result['aMenuLinks'], $result['sMenuTemplate']);
        }
    }

    private static function getMenu($menuFile): array
    {
        return \CFileMan::GetMenuArray(Application::getDocumentRoot() . $menuFile);
    }
}