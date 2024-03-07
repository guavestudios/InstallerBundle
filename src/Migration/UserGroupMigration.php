<?php

namespace Guave\InstallerBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Guave\InstallerBundle\Migration\Module\UserAdminModule;

class UserGroupMigration extends AbstractMigration
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function shouldRun(): bool
    {
        return UserAdminModule::getChecks($this->connection);
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        [$status, $message] = UserAdminModule::getQuery($this->connection);

        return $this->createResult(
            (bool)$status,
            $message
        );
    }
}
