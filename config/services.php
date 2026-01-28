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

    $container->import(resource: 'services/shared.php');
    $container->import(resource: 'services/identity.php');
    $container->import(resource: 'services/files.php');
    $container->import(resource: 'services/catalog.php');
    $container->import(resource: 'services/order.php');
    $container->import(resource: 'services/payment.php');
    $container->import(resource: 'services/vendor_management.php');
    $container->import(resource: 'services/cart.php');
    $container->import(resource: 'services/chat.php');
};
