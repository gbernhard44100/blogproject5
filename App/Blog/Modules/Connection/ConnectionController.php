<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Blog\Modules\Connection;

use \Lib\GBFram\BackController;
use \App\Blog\Models\ConnectionForm;
use \App\Blog\Entity\Connection;
use \Lib\GBFram\HTTPRequest;
use \App\Blog\Models\SubscriptionForm;

/**
 * Description of ConnectionController
 *
 * @author CathyGaetanB
 */
class ConnectionController extends BackController {

    public function executeShowConnectionPage(HTTPRequest $request) {
        $form = new ConnectionForm(new Connection, implode('', [explode('demandeconnexion', $request->requestURI())[0], 'seconnecter']));
        $this->page->addVar('form', $form);
    }

    public function executeAuthentication(HTTPRequest $request) {
        $connection = new Connection();
        $connection->hydrateFromPostRequest($request);
        $form = new ConnectionForm($connection, $request->requestURI());

        if (!$form->isValid()) {
            $this->page->addVar('form', $form);
            $this->setView('ShowConnectionPage');
        } else {
            /* hashage et salage du mot de passe */
            $long = strlen($connection->password());
            $password = "&=@+" . $long . $connection->password() . "#1%";
            $password = hash('sha512', $password);

            $connection->setPassword($password);

            $manager = $this->managers->getManagerOf('Connection');
            $matchedConnection = $manager->getList([
                'username' => $request->postData('username'),
                'password' => $connection->password(),]);
            if (!empty($matchedConnection)) {
                if ($matchedConnection[0]->valid() == TRUE) {
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
                    $this->setView('ShowConnectionPage');
                }
            } else {
                $this->page->addVar('redflash', 'Le pseudo ou le mot de passe est incorrect.');
                $this->page->addVar('form', $form);
                $this->setView('ShowConnectionPage');
            }
        }
    }

    public function executeDisconnection() {
        setcookie('tc', '', time() - 3600, '/', 'www.bernharddesign.com', false, true);
        $_SESSION = array();
        session_destroy();
        $this->app()->httpResponse()->redirect('/');
    }

    public function executeShowSubscriptionPage(HTTPRequest $request) {
        $form = new SubscriptionForm(new Connection, '/inscription');
        $this->page->addVar('form', $form);
    }

    public function executeSubmitSubscription(HTTPRequest $request) {
        $connection = new Connection();
        $connection->hydrateFromPostRequest($request);

        $form = new SubscriptionForm($connection, '/inscription');
        $form->fields()[2]->setValue($request->postData('confirmPassword'));

        if (!$form->isValid()) {
            $this->page->addVar('form', $form);
        }
        /* Checking if the password match with the password taped to confirm */ elseif (!($request->postData('password') == $request->postData('confirmPassword'))) {
            $this->page->addVar('form', $form);
            $this->page->addVar('redflash', 'Inscription impossible : les mots de passe ne sont pas identiques.');
            $this->setView('ShowSubscriptionPage');
        } else {
            /* Checking if the username taped is not already in the database. If it is not we include the subscription in the database. */
            $manager = $this->managers->getManagerOf('connection');
            $matchUser = $manager->getList(['username' => $connection->username()]);
            if (!empty($matchUser)) {
                $this->page->addVar('form', $form);
                $this->page->addVar
                        ('redflash', 'Inscription impossible : Nom d\'utilisateur déjà utilisé.');
                $this->setView('ShowSubscriptionPage');
            } else {
                /* hashage et salage du mot de passe */
                $long = strlen($connection->password());
                $password = "&=@+" . $long . $connection->password() . "#1%";
                $password = hash('sha512', $password);
                $connection->setPassword($password);

                $manager->add($connection);
                $this->page->addVar('yellowflash', 'Votre demande d\'inscritption à bien été prise en compte.'
                        . ' Une fois celle-ci validé, vous pourrez vous connecter.');
                $this->setView('HomePage');
            }
        }
    }

    public function executeShowSubscriptions(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('connection');
        $subscriptions = $manager->getList([], -1, -1, 'valid');
        $this->page->addVar('subscriptions', $subscriptions);
    }

    public function executeValidateSubscription(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('connection');
        $subscription = $manager->getUnique($request->getData('id'));
        $subscription->setValid();
        $manager->upDate($subscription);
        $this->app()->HttpResponse()->redirect('/admin/inscriptions');
    }

}
