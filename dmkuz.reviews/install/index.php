<?

use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Entity\Base;

class dmkuz_reviews extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . '/version.php');

        $this->MODULE_ID = 'dmkuz.reviews';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage("DMKUZ_REVIEWS_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("DMKUZ_REVIEWS_DESCRIPTION");
    }

    function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        if (!Application::getConnection(\Dmkuz\Reviews\BookTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmkuz\Reviews\BookTable')->getDBTableName()
        )
        ) {
            Base::getInstance('\Dmkuz\Reviews\BookTable')->createDbTable();
        }

        if (!Application::getConnection(\Dmkuz\Reviews\ReviewTable::getConnectionName())->isTableExists(
            Base::getInstance('\Dmkuz\Reviews\ReviewTable')->getDBTableName()
        )
        ) {
            Base::getInstance('\Dmkuz\Reviews\ReviewTable')->createDbTable();
        }
    }

    function UnInstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        Application::getConnection(\Dmkuz\Reviews\BookTable::getConnectionName())->
        queryExecute('drop table if exists ' . Base::getInstance('\Dmkuz\Reviews\BookTable')->getDBTableName());

        Application::getConnection(\Dmkuz\Reviews\ReviewTable::getConnectionName())->
        queryExecute('drop table if exists ' . Base::getInstance('\Dmkuz\Reviews\ReviewTable')->getDBTableName());

        Option::delete($this->MODULE_ID);
    }

    function DoInstall()
    {
        \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

        global $APPLICATION;
        $this->InstallDB();
        $this->InstallEvents();

        $this->addBooks();
        $this->addReviews();

        $APPLICATION->IncludeAdminFile(Loc::getMessage("DMKUZ_REVIEWS_INSTALL_TITLE"), $this->GetPath() . '/install/step.php');
    }

    function DoUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $this->UnInstallEvents();

        if ($request['step'] < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("DMKUZ_REVIEWS_UNINSTALL_TITLE"), $this->GetPath() . '/install/unstep1.php');
        } elseif ($request['step'] == 2) {

            if ($request['savedata'] != 'Y')
                $this->UnInstallDB();

        }

        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(Loc::getMessage("DMKUZ_REVIEWS_UNINSTALL_TITLE"), $this->GetPath() . '/install/unstep2.php');
    }

    function InstallEvents()
    {
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onAfterAdd', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'addHandler');
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onAfterUpdate', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'updateHandler');
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onBeforeDelete', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'onBeforeDelete');
        \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onAfterDelete', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'onAfterDelete');
    }

    function UnInstallEvents()
    {
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onAfterAdd', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'addHandler');
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onAfterUpdate', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'updateHandler');
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onBeforeDelete', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'onBeforeDelete');
        \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, '\Dmkuz\Reviews\Review::onAfterDelete', $this->MODULE_ID, '\Dmkuz\Reviews\Event', 'onAfterDelete');
    }

    function InstallFiles()
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }


    //Определяем место размещения модуля
    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    public function addBooks()
    {
        Dmkuz\Reviews\BookTable::add(
            [
                'NAME' => 'Мастер и Маргарита',
                'AUTHOR' => 'Михаил Булгаков',
                'RELEASED' => 1966
            ]
        );
        Dmkuz\Reviews\BookTable::add(
            [
                'NAME' => '451 градус по Фаренгейту',
                'AUTHOR' => 'Рэй Брэдбери',
                'RELEASED' => 1953
            ]
        );
        Dmkuz\Reviews\BookTable::add(
            [
                'NAME' => '1984',
                'AUTHOR' => 'Джордж Оруэлл',
                'RELEASED' => 1949
            ]
        );
        Dmkuz\Reviews\BookTable::add(
            [
                'NAME' => 'Унесенные ветром',
                'AUTHOR' => 'Маргарет Митчелл',
                'RELEASED' => 1936
            ]
        );
        Dmkuz\Reviews\BookTable::add(
            [
                'NAME' => 'Война и мир',
                'AUTHOR' => 'Лев Толстой',
                'RELEASED' => 1869
            ]
        );
    }

    public function addReviews()
    {
        Dmkuz\Reviews\ReviewTable::add(
            [
                'TEXT' => 'Классная книга!',
                'RATING' => '5',
                'BOOK_ID' => 1
            ]
        );
        Dmkuz\Reviews\ReviewTable::add(
            [
                'TEXT' => 'Классная книга!',
                'RATING' => '5',
                'BOOK_ID' => 2
            ]
        );
    }
}

?>