<?php

namespace App\Blog\Models;

/**
 * Description of BlogPostForm
 *
 * @author CathyGaetanB
 */
use App\Blog\Entity\Comment;
use Lib\GBFram\Form\Form;
use \Lib\GBFram\Form\LengthValidator;
use \Lib\GBFram\Form\IntValidator;
use \Lib\GBFram\Form\TextArea;
use \Lib\GBFram\Form\Input;

class CommentForm extends Form 
{

    public function __construct(Comment $comment, string $target) 
    {
        parent::__construct(array(
            'name' => 'Comment',
            'entity' => $comment,
            'targetUrl' => $target,
        ));
        $this->setfields($comment);
    }

    public function setfields() 
    {
        $this->addField(new Input(array('label' => 'Auteur', 'name' => 'author', 'value' => $this->entity()->author(),
            'validators' => array(new LengthValidator('Le nom de l\'auteur est trop long ou non rempli', 255))
        )));

        $this->addField(new TextArea(array('label' => 'Votre texte', 'name' => 'content',
            'value' => $this->entity()->content(), 'validators' => array(new LengthValidator('Il n\'y a pas de texte écrit dans ce champs'))
        )));
    }

}
