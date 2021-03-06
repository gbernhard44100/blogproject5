<?php

namespace App\Blog\Models;

use App\Blog\Entity\User;
use Lib\GBFram\Form\Form;
use \Lib\GBFram\Form\LengthValidator;
use \Lib\GBFram\Form\Input;

class LoginForm extends Form
{

    public function __construct(User $user, string $target, $request = null)
    {
        parent::__construct('Login', $user, $target, $request);
    }

    public function setFields()
    {
        $this->addField(new Input(array('label' => 'Nom d\'utilisateur', 'name' => 'username', 'value' => $this->entity()->username(),
            'validators' => [new LengthValidator('Le nom de d\'utilisateur est trop long ou non rempli', 255)]
        )));

        $this->addField(new Input(array('type' => 'password', 'label' => 'Mot de passe', 'name' => 'password', 'value' => $this->entity()->password(),
            'validators' => [new LengthValidator('Le mot de passe n\'est pas rempli', 255)]
        )));
    }

}
