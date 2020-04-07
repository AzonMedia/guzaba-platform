<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Models;


use Guzaba2\Authorization\Role;
use Guzaba2\Base\Base;
use Guzaba2\Coroutine\Cache;
use Guzaba2\Event\Event;
use Guzaba2\Kernel\Interfaces\ClassInitializationInterface;
use Guzaba2\Kernel\Kernel;
use GuzabaPlatform\Platform\Application\GuzabaPlatform;

class ClassInitialization extends Base implements ClassInitializationInterface
{
    protected const CONFIG_DEFAULTS = [
        'services' => [
            'Events',
            'GuzabaPlatform',
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public static function run_all_initializations(): array
    {
        self::initialize_roles();
        return ['initialize_roles'];
    }

    public static function initialize_roles(): void
    {
        $Events = self::get_service('Events');
        //if the RolesHierarchy is modified remove all cached roles inheritance for the current request
        $Callback = static function (Event $Event): void {
            $file_contents = <<<FILE
<?php
declare(strict_types=1);

/**
 * This file is generated at application startup.
 * Contains static define sections that are to be used by the IDE to find the definitions of the roles.
 * This file is not included by the application as it is a policy not to include any code from the startup_generated dir in the application it self.
 */


FILE;
            foreach (Role::get_system_roles_data() as $record) {
                define('GuzabaPlatform\\Platform\\Authentication\\Roles\\'.$record['role_name'], $record['role_id']);
                $file_contents .= "define('GuzabaPlatform\\Platform\\Authentication\\Roles\\{$record['role_name']}', {$record['role_id']});".PHP_EOL;
            }
            /** @var GuzabaPlatform $GuzabaPlatform */
            $GuzabaPlatform = self::get_service('GuzabaPlatform');
            $app_dir = $GuzabaPlatform->get_app_dir();
            $generated_dir = $app_dir.'/startup_generated';
            $roles_file = $generated_dir.'/roles.php';
            Kernel::file_put_contents($roles_file, $file_contents);

        };
        $Events->add_class_callback(GuzabaPlatform::class, '_before_execute', $Callback);


    }

}