<?php

namespace ContainerQGvGC4b;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getSinhvienTypeService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'App\Form\SinhvienType' shared autowired service.
     *
     * @return \App\Form\SinhvienType
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/form/FormTypeInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/form/AbstractType.php';
        include_once \dirname(__DIR__, 4).'/src/Form/SinhvienType.php';

        return $container->privates['App\\Form\\SinhvienType'] = new \App\Form\SinhvienType();
    }
}