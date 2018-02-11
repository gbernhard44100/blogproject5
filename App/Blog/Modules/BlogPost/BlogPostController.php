<?php

namespace App\Blog\Modules\BlogPost;

use Lib\GBFram\BackController;
use \Lib\GBFram\HTTPRequest;
use \App\Blog\Entity\BlogPost;
use \App\Blog\Entity\Comment;
use \App\Blog\Models\BlogPostForm;
use \App\Blog\Models\CommentForm;

class BlogPostController extends BackController {

    public function executeShowAllBlogPosts(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $list = $manager->getList();
        $this->page->addVar('blogPosts', $list);
    }

    public function executeShowBlogPost(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $this->page->addVar('blogPost', $blogpost);
        $commentManager = $this->managers->getManagerOf('Comment');
        $comments = $commentManager->getList(['idBlog' => $request->getData('id')]);
        $this->page->addVar('comments', $comments);
        $form = new CommentForm(new Comment(), '/blogpost/' . $request->getData('id') . '/submitcomment');
        $this->page->addVar('commentForm', $form);
    }

    public function executeShowAdminPage(HTTPRequest $request) {
        $this->testAuthentication($request);
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogposts = $manager->getList();
        $this->page->addVar('blogPosts', $blogposts);
    }

    public function executeShowAddBlogPostPage(HTTPRequest $request) {
        $this->testAuthentication($request);
        $blogpost = new BlogPost();
        $blogpost->hydrateFromPostRequest($request);
        $form = new BlogPostForm($blogpost, '/admin/addblogPost');
        $this->page->addVar('form', $form);
    }

    public function executeAddBlogPost(HTTPRequest $request) {
        $this->testAuthentication($request);
        $blogpost = new BlogPost();
        $blogpost->hydrateFromPostRequest($request);
        $blogpost->setUpdateDate(date("Y-m-d H:i:s"));
        $form = new BlogPostForm($blogpost, '/admin/addblogPost');

        if ($form->isValid()) {
            $manager = $this->managers->getManagerOf('BlogPost');
            $manager->add($blogpost);
            $this->app()->HttpResponse()->redirect('/mesblogposts');
        } else {
            $this->page->addVar('form', $form);
            $this->setView('ShowAddBlogPostPage');
        }
    }

    public function executeShowUpdateBlogPostPage(HTTPRequest $request) {
        $this->testAuthentication($request);
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $form = new BlogPostForm($blogpost, '/admin/blogpost/' . $request->getData('id') . '/submitmodifications');
        $this->page->addVar('form', $form);
    }

    public function executeUpdateBlogPost(HTTPRequest $request) {
        $this->testAuthentication($request);
        $blogpost = new BlogPost();
        $blogpost->hydrateFromPostRequest($request);
        $blogpost->setId($request->getData('id'));
        $blogpost->setUpdateDate(date("Y-m-d H:i:s"));
        $form = new BlogPostForm($blogpost, '/admin/blogpost/' . $request->getData('id') . '/submitmodifications');

        if ($form->isValid()) {
            $manager = $this->managers->getManagerOf('BlogPost');
            $manager->upDate($blogpost);
            $this->app()->HttpResponse()->redirect('/admin');
        } else {
            $this->page->addVar('form', $form);
            $this->setView('ShowUpdateBlogPostPage');
        }
    }

    public function executeDeleteBlogPost(HTTPRequest $request) {
        $this->testAuthentication($request);
        $manager = $this->managers->getManagerOf('BlogPost');
        $manager->delete($request->getData('id'));
        $this->app()->HttpResponse()->redirect('/admin');
    }

    public function executeSubmitComment(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $this->page->addVar('blogPost', $blogpost);
        $comment = new Comment();
        $comment->hydrateFromPostRequest($request);
        $comment->setIdBlog($request->getData('id'));
        $comment->setUpdateDate(date("Y-m-d H:i:s"));
        $form = new CommentForm($comment, '/blogpost/' . $request->getData('id') . '/submitcomment');

        if ($form->isValid()) {
            $manager = $this->managers->getManagerOf('Comment');
            $manager->add($comment);
            $this->app()->HttpResponse()->redirect('/blogpost/' . $request->getData('id'));
        } else {
            $this->page->addVar('commentForm', $form);
            $this->setView('ShowBlogPost');
        }
    }

    public function executeShowComments(HTTPRequest $request) {
        $this->testAuthentication($request);
        $this->executeShowBlogPost($request);
    }

    public function executeDeleteComment(HTTPRequest $request) {
        $this->testAuthentication($request);
        $manager = $this->managers->getManagerOf('Comment');
        $manager->delete($request->getData('comment_id'));
        $this->app()->HttpResponse()->redirect('/admin/blogpost/' . $request->getData('id') . '/commentaires');
    }

    public function executeValidateComment(HTTPRequest $request) {
        $this->testAuthentication($request);
        $manager = $this->managers->getManagerOf('Comment');
        $comment = $manager->getUnique($request->getData('comment_id'));
        $comment->setValid(TRUE);
        $manager->upDate($comment);
        $this->app()->HttpResponse()->redirect('/admin/blogpost/' . $request->getData('id') . '/commentaires');
    }

    public function testAuthentication(HTTPRequest $request) {
        /* On vérifie d'abord la validité du ticket */
        if (isset($_COOKIE['tc']) && isset($_SESSION['ticket'])) {
            if (isset($_SESSION['auth']) && $_COOKIE['tc'] == $_SESSION['ticket']) {
                $ticket = session_id() . microtime() . rand(0, 99999);
                $ticket = hash('sha512', $ticket);
                setcookie('tc', '', time() - 3600, '/', 'www.bernharddesign.com', false, true);
                setcookie('tc', $ticket, time() + (60 * 20), '/', 'www.bernharddesign.com', false, true);
                $_SESSION['ticket'] = $ticket;
            } else {
                $_SESSION = array();
                session_destroy();
            }
        } else {
            $_SESSION = array();
            session_destroy();
            $this->page->addVar('redflash', 'Vous devez vous connecter et permettre l\'accès aux cookies pour pouvoir vous connecter');
        }
        /* Si l'Url commence par "/admin" et qu'il n'y a pas de ticket valide,
         *  alors l'utilisateur est renvoyé vers la page de connexion */
        if (preg_match('(^\/admin)', $request->requestURI()) && !isset($_SESSION['ticket']) && !isset($_SESSION['auth'])) {
            $request->app()->httpResponse()->redirect($request->requestURI() . '/demandeconnexion');
        }
    }

}
