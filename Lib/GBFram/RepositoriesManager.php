<?php

namespace Lib\GBFram;

class RepositoriesManager extends ApplicationComponent
{

    protected $managers = [];

    public function __construct($app)
    {
        parent::__construct($app);

        $xml = new \DOMDocument;
        $xml->load(__DIR__ . '/../../App//' . $app->name() . '/Config/config.xml');
        $repositories = $xml->getElementsByTagName('repository');

        foreach ($repositories as $repository) {
            $name = $repository->getAttribute('name');
            $class = $repository->getAttribute('class');
            if (!class_exists($class)) {
                throw new \InvalidArgumentException(
                'La class de repository spécifié dans le fichier config n\'existe pas.'
                );
            }
            $this->managers[$name] = new $class($app);
        }
    }

    public function getManagerOf($manager)
    {
        if (!array_key_exists($manager, $this->managers)) {
            throw new \InvalidArgumentException('Le manager spécifié est inexistant.');
        }
        return $this->managers[$manager];
    }

}
