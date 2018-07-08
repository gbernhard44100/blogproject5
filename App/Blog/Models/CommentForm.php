<?php

namespace App\Blog\Models;

use App\Blog\Entity\Comment;
use Lib\GBFram\Form\Form;
use \Lib\GBFram\Form\LengthValidator;
use \Lib\GBFram\Form\TextArea;
use \Lib\GBFram\Form\Input;

class CommentForm extends Form
{

    public function __construct(Comment $comment, string $target, $request = null)
    {
        parent::__construct('Comment', $comment, $target, $request);
    }

    public function setFields()
    {
        $this->addField(new Input(array('label' => 'Auteur', 'name' => 'author', 'value' => $this->entity()->author(),
            'validators' => array(new LengthValidator('Le nom de l\'auteur est trop long ou non rempli', 255))
        )));

        $this->addField(new TextArea(array('label' => 'Votre texte', 'name' => 'content',
            'value' => $this->entity()->content(), 'validators' => array(new LengthValidator('Il n\'y a pas de texte écrit dans ce champs'))
        )));
    }

}
