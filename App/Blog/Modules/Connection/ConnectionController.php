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
class ConnectionController extends BackController{
    public function executeShowConnectionPage(HTTPRequest $request){
        $form = new ConnectionForm(new Connection, implode('',[explode('demandeconnexion', $request->requestURI())[0],'seconnecter']));
        $this->page->addVar('form',$form);
    }
    
    public function executeAuthentication(HTTPRequest $request){
        $connection = new Connection();
        $connection->hydrateFromPostRequest($request);
        $form = new ConnectionForm($connection, $request->requestURI());
        
        if(!$form->isValid()){
            $this->page->addVar('form',$form);
        }
        else{
            $manager = $this->managers->getManagerOf('Connection');
            $matchedConnection =$manager->getList([
                'username' => $request->postData('username'),
                'password' => $request->postData('password'),]);
            
            if(!empty($matchedConnection))
            {
                if($matchedConnection[0]->valid() == TRUE){
                    session_start();
                    $ticket = session_id().microtime().rand(0,99999);
                    $ticket = hash('sha512', $ticket);
                    setcookie('tc','', time() - 3600, '/', 'www.bernharddesign.com', false, true);
                    setcookie('tc',$ticket, time() + (60 * 20), '/', 'www.bernharddesign.com', false, true);
                    $_SESSION['ticket'] = $ticket;                
                    $_SESSION['auth'] = TRUE;                
                    $this->app()->httpResponse()->redirect($request->getData('adresse'));
                }
                else{
                    $this->page->addVar('flash',
                        'Connection impossible : Votre inscription n\'a pas encore été validé par l\'administrateur');
                $this->page->addVar('form',$form);
                }
            }
            else{
                $this->page->addVar('flash',
                        'Le pseudo ou le mot de passe est incorrect.');
                $this->page->addVar('form',$form);
            }
        }
    }
    
    public function executeDisconnection(){
        setcookie('tc','', time() - 3600, '/', 'www.bernharddesign.com', false, true);
        $_SESSION = array();
        session_destroy();
        $this->app()->httpResponse()->redirect('/');
    }
    
    public function executeShowSubscriptionPage(HTTPRequest $request){
        $form = new SubscriptionForm(new Connection,'/inscription');
        $this->page->addVar('form',$form);
    }
    
    public function executeSubmitSubscription(HTTPRequest $request){
        $connection = new Connection();
        $connection->hydrateFromPostRequest($request);
        $form = new SubscriptionForm($connection, '/inscription');
        $form->fields()[2]->setValue($request->postData('confirmPassword'));
        
        if(!$form->isValid()){
            $this->page->addVar('form',$form);
        }
        /* Checking if the password match with the password taped to confirm */
        elseif(!($request->postData('password') == $request->postData('confirmPassword'))){
            $this->page->addVar('form',$form);
            $this->page->addVar('flash',
                        'Inscription impossible : les mots de passe ne sont pas identiques.');
        }
        else{
            /* Checking if the username taped is not already in the database. If it is not we include the subscription in the database. */
            $manager = $this->managers->getManagerOf('connection');
            $matchUser = $manager->getList(['username' =>$connection->username()]);
            if(!empty($matchUser)){
                $this->page->addVar('form',$form);
                $this->page->addVar
                        ('flash',
                        'Inscription impossible : Nom d\'utilisateur déjà utilisé.');
            }
            else{
                $manager->add($connection);
                $this->page->addVar('flash',
                        'Votre demande d\'inscritption à bien été prise en compte.'
                        . 'Une fois celle-ci validé, vous pourrez vous connecter.');
                $this->app()->httpResponse()->redirect('/');
            }
        }
    }
    
    public function executeShowSubscriptions(HTTPRequest $request){
        $manager = $this->managers->getManagerOf('connection');
        $subscriptions = $manager->getList([],-1,-1,'valid');
        $this->page->addVar('subscriptions', $subscriptions);
    }
    
    public function executeValidateSubscription(HTTPRequest $request){
        $manager = $this->managers->getManagerOf('connection');
        $subscription = $manager->getUnique($request->getData('id'));
        $subscription->setValid();
        $manager->upDate($subscription);
        $this->app()->HttpResponse()->redirect('/admin/inscriptions');
    }
}
