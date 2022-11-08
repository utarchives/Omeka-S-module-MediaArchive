<?php

namespace MediaArchive;

use Omeka\Module\AbstractModule;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractController;

class Module extends AbstractModule
{

    public function onBootstrap(MvcEvent $event)
    {
        parent::onBootstrap($event);
        $acl = $this->getServiceLocator()->get('Omeka\Acl');
        $acl->allow(
            null,
            ['MediaArchive\Controller\Site\Index',
            ]
            );

    }
    public function install(ServiceLocatorInterface $serviceLocator)
    {

    }
    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $this->manageSettings($serviceLocator->get('Omeka\Settings'), 'uninstall');
        $this->manageSiteSettings($serviceLocator, 'install');
    }
    /**
     *
     * @param $settings
     * @param $process
     * @param string $key
     */
    protected function manageSettings($settings, $process, $key = 'settings')
    {
        $config = require __DIR__ . '/config/module.config.php';
        $defaultSettings = $config[strtolower(__NAMESPACE__)][$key];
        foreach ($defaultSettings as $name => $value) {
            switch ($process) {
                case 'install':
                    $settings->set($name, $value);
                    break;
                case 'uninstall':
                    $settings->delete($name);
                    break;
            }
        }
    }
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $process
     */
    protected function manageSiteSettings(ServiceLocatorInterface $serviceLocator, $process)
    {
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');
        $api = $serviceLocator->get('Omeka\ApiManager');
        $sites = $api->search('sites')->getContent();
        foreach ($sites as $site) {
            $siteSettings->setTargetId($site->id());
            $this->manageSettings($siteSettings, $process, 'site_settings');
        }
    }
    public function upgrade($oldVersion, $newVersion, ServiceLocatorInterface $serviceLocator)
    {

    }

    public function getConfig()
    {
        return include __DIR__.'/config/module.config.php';
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $controllers = [
            'Omeka\Controller\Site\Item',
        ];
        foreach ($controllers as $controller) {
            $sharedEventManager->attach(
                $controller,
                'view.show.after',
                [$this, 'displayMediaArchive']
                );
        }
    }
    /**
     * Display the quick Resource Tree.
     *
     * @param Event $event
     */
    public function displayMediaArchive(Event $event)
    {
        $view = $event->getTarget();
        $resource = $event->getTarget()->resource;
        echo $view->mediaArchive($resource);
    }

}
