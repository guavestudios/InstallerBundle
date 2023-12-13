<?php

namespace Guave\InstallerBundle\Migration\Module;

use Contao\FilesModel;
use Contao\System;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class UserAdminModule
{
    private const TABLE = 'tl_user_group';
    private const NAME = 'UserAdmin';
    private const MODULES = ['user', 'group'];
    private const PERMISSIONS = [
        'tl_user::username',
        'tl_user::name',
        'tl_user::email',
        'tl_user::language',
        'tl_user::backendTheme',
        'tl_user::fullscreen',
        'tl_user::uploader',
        'tl_user::showHelp',
        'tl_user::thumbnails',
        'tl_user::useRTE',
        'tl_user::useCE',
        'tl_user::password',
        'tl_user::pwChange',
        'tl_user::groups',
        'tl_user::inherit',
        'tl_user::modules',
        'tl_user::themes',
        'tl_user::elements',
        'tl_user::fields',
        'tl_user::pagemounts',
        'tl_user::alpty',
        'tl_user::filemounts',
        'tl_user::fop',
        'tl_user::imageSizes',
        'tl_user::forms',
        'tl_user::formp',
        'tl_user::amg',
        'tl_user::disable',
        'tl_user::start',
        'tl_user::stop',
        'tl_user::session',
        'tl_user::calendars',
        'tl_user::calendarp',
        'tl_user::calendarfeeds',
        'tl_user::calendarfeedp',
        'tl_user::faqs',
        'tl_user::faqp',
        'tl_user::news',
        'tl_user::newp',
        'tl_user::newsfeeds',
        'tl_user::newsfeedp',
        'tl_user::newsletters',
        'tl_user::newsletterp',
        'tl_user_group::name',
        'tl_user_group::modules',
        'tl_user_group::themes',
        'tl_user_group::elements',
        'tl_user_group::fields',
        'tl_user_group::pagemounts',
        'tl_user_group::alpty',
        'tl_user_group::filemounts',
        'tl_user_group::fop',
        'tl_user_group::imageSizes',
        'tl_user_group::forms',
        'tl_user_group::formp',
        'tl_user_group::amg',
        'tl_user_group::alexf',
        'tl_user_group::disable',
        'tl_user_group::start',
        'tl_user_group::stop',
        'tl_user_group::calendars',
        'tl_user_group::calendarp',
        'tl_user_group::calendarfeeds',
        'tl_user_group::calendarfeedp',
        'tl_user_group::faqs',
        'tl_user_group::faqp',
        'tl_user_group::news',
        'tl_user_group::newp',
        'tl_user_group::newsfeeds',
        'tl_user_group::newsfeedp',
        'tl_user_group::newsletters',
        'tl_user_group::newsletterp',
    ];

    /**
     * @throws Exception
     */
    public static function getChecks(Connection $connection): bool
    {
        $schemaManager = $connection->createSchemaManager();
        if (!$schemaManager->tablesExist([self::TABLE])) {
            return false;
        }

        $stmt = $connection
            ->prepare("SELECT * FROM " . self::TABLE . " WHERE `name` = :name")
            ->executeQuery([
                'name' => self::NAME,
            ]);

        return $stmt->rowCount() <= 0;
    }

    /**
     * @throws Exception
     */
    public static function getQuery(Connection $connection): array
    {
        $stmt = $connection->prepare(
            "
            INSERT INTO
                " . self::TABLE . "
                (`name`, `modules`, `alexf`, `filemounts`)
            VALUES
                (:name, :modules, :permissions, :filemounts)
        "
        );

        $fileMounts = serialize([FilesModel::findByPath('files/uploads')->uuid]);

        $stmt->executeQuery([
            'name' => self::NAME,
            'modules' => serialize(self::MODULES),
            'permissions' => serialize(self::PERMISSIONS),
            'filemounts' => $fileMounts,
        ]);

        return [
            'status' => true,
            'message' => 'Created ' . self::NAME . ' Group.',
        ];
    }
}
