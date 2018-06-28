<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Blog\Models;

use App\Blog\Entity\Connection;
use Lib\GBFram\Form\Form;
use \Lib\GBFram\Form\LengthValidator;
use \Lib\GBFram\Form\Input;

/**
 * Description of ConnectionForm
 *
 * @author CathyGaetanB
 */
class SubscriptionForm extends Form 
{

    public function __construct(Connection $connection, string $target) 
    {
        parent::__construct(array(
            'name' => 'Connection',
            'entity' => $connection,
            'targetUrl' => $target,
        ));
        $this->setfields($connection);
    }

    public function setfields() 
    {
        $this->addField(new Input(array('label' => 'Nom d\'utilisateur', 'name' => 'username', 'value' => $this->entity()->username(),
            'validators' => [new LengthValidator('Le nom de d\'utilisateur est trop long ou non rempli', 255)]
        )));

        $this->addField(new Input(array('type' => 'password', 'label' => 'Mot de passe', 'name' => 'password', 'value' => $this->entity()->password(),
            'validators' => [new LengthValidator('Le mot de passe n\'est pas rempli', 255)]
        )));
        $this->addField(new Input(array('type' => 'password', 'label' => 'Confirmer le mot de passe', 'name' => 'confirmPassword', 'value' => '',
            'validators' => [new LengthValidator('Le mot de passe à confirmer n\'est pas rempli', 255)]
        )));
    }

}
