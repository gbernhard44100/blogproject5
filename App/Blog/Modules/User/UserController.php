<?php

namespace App\Blog\Modules\User;

use \Lib\GBFram\Controller;
use \App\Blog\Models\LoginForm;
use \App\Blog\Entity\User;
use \Lib\GBFram\HTTPRequest;
use \App\Blog\Models\SubscriptionForm;

class UserController extends Controller
{

    public function executeShowLoginPage(HTTPRequest $request)
    {
        $form = new LoginForm(new User, implode('', [explode('demandeconnexion', $request->requestURI())[0], 'seconnecter']));
        $this->page->addVar('form', $form);
    }

    public function executeAuthentication(HTTPRequest $request)
    {
        $user = new User();
        $form = new LoginForm($user, $request->requestURI(), $request);
        if (!$form->isValid()) {
            $this->page->addVar('form', $form);
            $this->setView('ShowLoginPage');
        } else {
            /* hashage et salage du mot de passe */
            $long = strlen($user->password());
            $password = "&=@+" . $long . $user->password() . "#1%";
            $password = hash('sha512', $password);
            $user->setPassword($password);
            $manager = $this->rm->getManagerOf('User');
            $matchedUser = $manager->getList([
                'username' => $request->postData('username'),
                'password' => $user->password(),]);
            if (!empty($matchedUser)) {
                if ($matchedUser[0]->valid() == TRUE) {
                    session_start();
                    $ticket = session_id() . microtime() . rand(0, 99999);
                    $ticket = hash('sha512', $ticket);
                    setcookie('tc', '', time() - 3600, '/', 'www.bernharddesign.com', false, true);
                    setcookie('tc', $ticket, time() + (60 * 20), '/', 'www.bernharddesign.com', false, true);
                    $_SESSION['ticket'] = $ticket;
                    $_SESSION['auth'] = TRUE;

                    if ($request->getData('adresse') == '') {
                        $this->app()->httpResponse()->redirect('/admin');
                    } else {
                        $this->app()->httpResponse()->redirect($request->getData('adresse'));
                    }
                } else {
                    $this->page->addVar('redflash', 'Connection impossible : Votre inscription n\'a pas encore été validé par l\'administrateur');
                    $this->page->addVar('form', $form);
                    $this->setView('ShowLoginPage');
                }
            } else {
                $this->page->addVar('redflash', 'Le pseudo ou le mot de passe est incorrect.');
                $this->page->addVar('form', $form);
                $this->setView('ShowLoginPage');
            }
        }
    }

    public function executeDisconnection()
    {
        setcookie('tc', '', time() - 3600, '/', 'www.bernharddesign.com', false, true);
        $_SESSION = array();
        session_destroy();
        $this->app()->httpResponse()->redirect('/');
    }

    public function executeShowSubscriptionPage()
    {
        $form = new SubscriptionForm(new User, '/inscription');
        $this->page->addVar('form', $form);
    }

    public function executeSubmitSubscription(HTTPRequest $request)
    {
        $user = new User();

        $form = new SubscriptionForm($user, '/inscription', $request);
        $form->fields()[2]->setValue($request->postData('confirmPassword'));
        $this->setView('ShowSubscriptionPage');

        if (!$form->isValid()) {
            $this->page->addVar('form', $form);
            /* Checking if the password match with the password taped to confirm */
        } elseif (!($request->postData('password') == $request->postData('confirmPassword'))) {
            $this->page->addVar('form', $form);
            $this->page->addVar('redflash', 'Inscription impossible : les mots de passe ne sont pas identiques.');
        } else {
            /* Checking if the username taped is not already in the database. If it is not we include the subscription in the database. */
            $manager = $this->rm->getManagerOf('User');
            $matchUser = $manager->getList(['username' => $user->username()]);
            if (!empty($matchUser)) {
                $this->page->addVar('form', $form);
                $this->page->addVar
                        ('redflash', 'Inscription impossible : Nom d\'utilisateur déjà utilisé.');
            } else {
                /* hashage et salage du mot de passe */
                $long = strlen($user->password());
                $password = "&=@+" . $long . $user->password() . "#1%";
                $password = hash('sha512', $password);
                $user->setPassword($password);

                $manager->add($user);
                $this->page->addVar('yellowflash', 'Votre demande d\'inscritption à bien été prise en compte.'
                        . ' Une fois celle-ci validé, vous pourrez vous connecter.');
                $this->setView('HomePage');
            }
        }
    }

    public function executeShowSubscriptions()
    {
        $manager = $this->rm->getManagerOf('User');
        $subscriptions = $manager->getList([], -1, -1, 'valid');
        $this->page->addVar('subscriptions', $subscriptions);
    }

    public function executeValidateSubscription(HTTPRequest $request)
    {
        $manager = $this->rm->getManagerOf('User');
        $subscription = $manager->getUnique($request->getData('id'));
        $subscription->setValid();
        $manager->upDate($subscription);
        $this->app()->HttpResponse()->redirect('/admin/inscriptions');
    }

}
