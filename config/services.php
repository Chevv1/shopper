<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $container->parameters()
        ->set(name: 'jwt.secret_key', value: '%env(JWT_SECRET_KEY)%')
        ->set(name: 'jwt.refresh_secret_key', value: '%env(JWT_REFRESH_SECRET_KEY)%')
        ->set(name: 'jwt.access_token_ttl', value: 3600)
        ->set(name: 'jwt.refresh_token_ttl', value: 2592000)
        ->set(name: 'files_directory', value: '%kernel.project_dir%/public/uploads')
        ->set(name: 'files_base_url', value: '/uploads');

    $container->import(resource: '../src/Shared/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Identity/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Files/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Catalog/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Order/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Payment/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/VendorManagement/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Cart/Infrastructure/Symfony/config/services.php');
    $container->import(resource: '../src/Chat/Infrastructure/Symfony/config/services.php');
};
