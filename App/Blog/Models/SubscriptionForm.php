<?php

namespace App\Blog\Models;

use App\Blog\Entity\User;
use Lib\GBFram\Form\Form;
use \Lib\GBFram\Form\LengthValidator;
use \Lib\GBFram\Form\Input;

class SubscriptionForm extends Form
{

    public function __construct(User $user, string $target, $request = null)
    {
        parent::__construct('Subscription', $user, $target, $request);
    }

    public function setFields()
    {
        $this->addField(new Input(array('label' => 'Nom d\'utilisateur', 'name' => 'username', 'value' => $this->entity()->username(),
            'validators' => [new LengthValidator('Le nom de d\'utilisateur est trop long ou non rempli', 255)]
        )));

        $this->addField(new Input(array('type' => 'password', 'label' => 'Mot de passe', 'name' => 'password', 'value' => $this->entity()->password(),
            'validators' => [new LengthValidator('Le mot de passe n\'est pas rempli', 255)]
        )));

        /** Define the value in the field used to confirm the password */
        $confirmPassword = '';
        if (isset($_POST['postToken']) && isset($_SESSION['postToken'])) {
            if ($_POST['postToken'] != $_SESSION['postToken']) {
                throw new \UnexpectedValueException('Le formulaire édité à un token non valide.');
            }
            $confirmPassword = $_POST['confirmPassword'];
        }

        $this->addField(new Input(array('type' => 'password', 'label' => 'Confirmer le mot de passe', 'name' => 'confirmPassword', 'value' => $confirmPassword,
            'validators' => [new LengthValidator('Le mot de passe à confirmer n\'est pas rempli', 255)]
        )));
    }

}
