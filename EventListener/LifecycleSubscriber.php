<?php
/**
 * @package Newscoop\ExamplePluginBundle
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
 * @copyright 2012 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace AHS\AdvertsPluginBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;

/**
 * Event lifecycle management
 */
class LifecycleSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function install(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function update(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function remove(GenericEvent $event)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->dropSchema($this->getClasses(), true);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'plugin.install.ahs_adverts_plugin' => array('install', 1),
            'plugin.update.ahs_adverts_plugin' => array('update', 1),
            'plugin.remove.ahs_adverts_plugin' => array('remove', 1),
        );
    }

    private function getClasses(){
        return array(
          $this->em->getClassMetadata('AHS\AdvertsPluginBundle\Entity\Announcement'),
          $this->em->getClassMetadata('AHS\AdvertsPluginBundle\Entity\Category'),
          $this->em->getClassMetadata('AHS\AdvertsPluginBundle\Entity\Image'),
          $this->em->getClassMetadata('AHS\AdvertsPluginBundle\Entity\User'),
        );
    }
}
