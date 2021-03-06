<?php

namespace Lib\GBFram;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Page extends ApplicationComponent
{

    protected $contentFile;
    protected $vars = [];
    protected $twig;
    protected $twigTemplatesPath = array();

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $xml = new \DOMDocument;
        $xml->load(__DIR__ . '/../../App/' . $this->app->name() . '/Config/twig.xml');
        $elements = $xml->getElementsByTagName('define');

        foreach ($elements as $element) {
            $this->twigTemplatesPath[] = $element->getAttribute('path');
        }
        $templatesloader = new FilesystemLoader($this->twigTemplatesPath);
        $this->twig = new Environment($templatesloader, array(
            'cache' => false,
            'debug' => true,
        ));
        $this->twig->addExtension(new \Twig_Extensions_Extension_Text);
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('server', $_SERVER);
    }

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }
        $this->vars[$var] = $value;
    }

    public function getGeneratedPage()
    {
        $fileExist = false;
        foreach ($this->twigTemplatesPath as $path) {
            if (file_exists($path . '/' . $this->contentFile)) {
                $fileExist = true;
            }
        }
        if (!isset($fileExist)) {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }
        $this->twig->display($this->contentFile, $this->vars);
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }
        $this->contentFile = $contentFile;
    }

}
