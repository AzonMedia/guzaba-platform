<?php


namespace GuzabaPlatform\Platform\Components\Models;


use Composer\Installer\PackageEvent;

//NOT USED
//"scripts" : {
//    "post-package-install": "GuzabaPlatform\\Platform\\Components\\Models\\PostInstall::post_installation_script"
//    }
/**
 * Class PostInstall
 * @package GuzabaPlatform\Platform\Components\Models
 * This is the only class in GuzabaPlatform that is supposed to be autoloaded by the composer autoloaded.
 * This is so because it is invoked in the post-installation hooks.
 *
 */
abstract class PostInstall
{

    public static function post_installation_script(PackageEvent $PackageEvent) : bool
    {

        $Package = $PackageEvent->getOperation()->getPackage();

        //print 'target-dir: '.$Package->getTargetDir().PHP_EOL;
        //print 'installation source: '.$Package->getInstallationSource().PHP_EOL;
        //print 'package name: '.$Package->getName().PHP_EOL;//guzaba-platform/tags
        //print 'url: '.$Package->getSourceUrl().PHP_EOL;//https://github.com/AzonMedia/component-tags.git
        //print 'autoload: '.print_R($Package->getAutoload(), true).PHP_EOL;
//        [psr-4] => Array
//    (
//        [GuzabaPlatform\Tags\] => src/
//        )


        $package_name = $Package->getName();
        print str_repeat(' ', 4).sprintf('GuzabaPlatform: Running post installation script for package %s.', $package_name).PHP_EOL;

        //print $vendor_dir;// /home/local/PROJECTS/guzaba2-platform/guzaba-platform/vendor

        //print_r($Package);
        $vendor_dir = $PackageEvent->getComposer()->getConfig()->get('vendor-dir');
        $autoloader = $vendor_dir . '/autoload.php';
        if (file_exists($autoloader)) {
            $autoload = $Package->getAutoload();
            if (!isset($autoload['psr-4'])) {
                throw new \RuntimeException(sprintf('The component %s does not define a "psr-4" autoloader.', $package_name));
            }
            $namespace = array_key_first($autoload['psr-4']);
            $component_class = $namespace.'Component';
//        if (!class_exists($component_class)) {
//            throw new \RuntimeException(sprintf('The component %s does not have a %s class.', $package_name, $component_class));
//        }
            if (class_exists($component_class) && $component_class) {
                if (method_exists($component_class,'post_installation_hook')) {
                    //if (is_a($component_class, )
                    $component_class::post_installation_hook($PackageEvent);
                    print str_repeat(' ',4).sprintf('GuzabaPlatform: Running post installation hook %s.', $component_class.'::post_installation_hook()');
                }
            }
        }



        return TRUE;
    }
}