<?php

namespace App\Blog\Models;

/**
 * Description of BlogPostForm
 *
 * @author CathyGaetanB
 */
use App\Blog\Entity\BlogPost;
use Lib\GBFram\Form\Form;
use \Lib\GBFram\Form\LengthValidator;
use \Lib\GBFram\Form\IntValidator;
use \Lib\GBFram\Form\TextArea;
use \Lib\GBFram\Form\Input;

class BlogPostForm extends Form 
{

    public function __construct(BlogPost $blogpost, string $target) 
    {
        parent::__construct(array(
            'name' => 'BlogPost',
            'entity' => $blogpost,
            'targetUrl' => $target,
        ));
        $this->setfields($blogpost);
    }

    public function setfields() 
    {
        $this->addField(new Input(array('label' => 'Auteur', 'name' => 'author', 'value' => $this->entity()->author(),
            'validators' => [new LengthValidator('Le nom de l\'auteur est trop long ou non rempli', 255)]
        )));

        $this->addField(new Input(array('label' => 'Titre', 'name' => 'title', 'value' => $this->entity()->title(),
            'validators' => [new LengthValidator('Le titre du Blogpost est trop long ou non rempli', 255)]
        )));

        $this->addField(new TextArea(array('label' => 'Votre texte', 'name' => 'content',
            'value' => $this->entity()->content(), 'validators' => [new LengthValidator('Il n\'y a pas de texte écrit dans ce champs')]
        )));
    }

}
