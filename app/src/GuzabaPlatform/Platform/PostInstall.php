<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use GuzabaPlatform\Installer\Installer;
use GuzabaPlatform\Installer\Interfaces\PostInstallHookInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PostInstall implements PostInstallHookInterface
{
    public static function post_install_hook(Installer $Installer, InstalledRepositoryInterface $Repo, PackageInterface $Package) : void
    {
        $guzaba_platform_dir = $Installer->getInstallPath($Package);

        $database_file = $guzaba_platform_dir . '/app/database/guzaba2.sql';
        $import_script = $guzaba_platform_dir . '/app/database/import_database.sh';
        $env_file = $guzaba_platform_dir . '/app/dockerfiles/GuzabaPlatform/guzaba-platform.env';

        if (file_exists($database_file) && is_readable($database_file) && file_exists($import_script) && is_executable($import_script)) {
            if (\version_compare(\PHP_VERSION, '7.4.0', '>=')) {
                $process = new Process([ $import_script, $database_file, $env_file ]);
            } else {
                $process = new Process($import_script . ' ' . $database_file . ' ' . $env_file);
            }

            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
        }
    }
}
