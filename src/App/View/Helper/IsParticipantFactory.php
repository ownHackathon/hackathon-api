<?php declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Participant;
use App\Model\User;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\View\HelperPluginManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IsParticipantFactory
{
    public function __invoke(ContainerInterface $container): IsParticipant
    {
        $d = $container->get(HelperPluginManager::class);
        $t = $d->get('Psr\Http\Message\ResponseInterface');
        var_dump($t);die();
        return new IsParticipant();
    }
}
