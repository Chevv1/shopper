<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Security;

use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class WebhookSignatureSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $webhookSecret) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        // 1. Обработка массива [ControllerClass, 'methodName']
        if (is_array($controller)) {
            $reflection = new ReflectionMethod($controller[0], $controller[1]);
        }
        // 2. Обработка Closure или строк-функций
        elseif ($controller instanceof \Closure || is_string($controller)) {
            $reflection = new ReflectionFunction($controller);
        }
        // 3. Обработка Invokable объектов (самый частый случай для CQRS контроллеров)
        elseif (is_object($controller) && method_exists($controller, '__invoke')) {
            $reflection = new ReflectionMethod($controller, '__invoke');
        }
        // 4. Если это какой-то странный тип контроллера, который мы не поддерживаем
        else {
            return;
        }

        $attribute = $reflection->getAttributes(IsSignedWebhook::class)[0] ?? null;

        if (!$attribute) {
            return;
        }

        $request = $event->getRequest();
        $signature = $request->headers->get('X-Signature');
        $content = $request->getContent();

        $computed = hash_hmac('sha256', $content, $this->webhookSecret);

        if (!$signature || !hash_equals($computed, $signature)) {
//            throw new AccessDeniedHttpException(message: 'Invalid Signature');
        }
    }
}
