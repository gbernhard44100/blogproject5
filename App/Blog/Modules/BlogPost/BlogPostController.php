<?php

namespace App\Blog\Modules\BlogPost;

use Lib\GBFram\Controller;
use \Lib\GBFram\HTTPRequest;
use \App\Blog\Entity\BlogPost;
use \App\Blog\Entity\Comment;
use \App\Blog\Models\BlogPostForm;
use \App\Blog\Models\CommentForm;

class BlogPostController extends Controller
{

    public function executeShowAllBlogPosts()
    {
        $manager = $this->rm->getManagerOf('BlogPost');
        $list = $manager->getList();
        $this->page->addVar('blogPosts', $list);
    }

    public function executeShowBlogPost(HTTPRequest $request)
    {
        $manager = $this->rm->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $this->page->addVar('blogPost', $blogpost);
        $commentManager = $this->rm->getManagerOf('Comment');
        $comments = $commentManager->getList(['idBlog' => $request->getData('id')]);
        $this->page->addVar('comments', $comments);
        $form = new CommentForm(new Comment(), '/blogpost/' . $request->getData('id') . '/submitcomment', $request);
        $this->page->addVar('commentForm', $form);
    }

    public function executeShowAdminPage(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $manager = $this->rm->getManagerOf('BlogPost');
        $blogposts = $manager->getList();
        $this->page->addVar('blogPosts', $blogposts);
    }

    public function executeShowAddBlogPostPage(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $blogpost = new BlogPost();
        $form = new BlogPostForm($blogpost, '/admin/addblogPost', $request);
        $this->page->addVar('form', $form);
    }

    public function executeAddBlogPost(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $blogpost = new BlogPost();
        $blogpost->setUpdateDate(date("Y-m-d H:i:s"));
        $form = new BlogPostForm($blogpost, '/admin/addblogPost', $request);

        if ($form->isValid()) {
            $manager = $this->rm->getManagerOf('BlogPost');
            $manager->add($blogpost);
            $this->app()->HttpResponse()->redirect('/mesblogposts');
        } else {
            $this->page->addVar('form', $form);
            $this->setView('ShowAddBlogPostPage');
        }
    }

    public function executeShowUpdateBlogPostPage(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $manager = $this->rm->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $form = new BlogPostForm($blogpost, '/admin/blogpost/' . $request->getData('id') . '/submitmodifications', $request);
        $this->page->addVar('form', $form);
    }

    public function executeUpdateBlogPost(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $blogpost = new BlogPost();
        $blogpost->setId($request->getData('id'));
        $blogpost->setUpdateDate(date("Y-m-d H:i:s"));
        $form = new BlogPostForm($blogpost, '/admin/blogpost/' . $request->getData('id') . '/submitmodifications', $request);

        if ($form->isValid()) {
            $manager = $this->rm->getManagerOf('BlogPost');
            $manager->upDate($blogpost);
            $this->app()->HttpResponse()->redirect('/admin');
        } else {
            $this->page->addVar('form', $form);
            $this->setView('ShowUpdateBlogPostPage');
        }
    }

    public function executeDeleteBlogPost(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $manager = $this->rm->getManagerOf('BlogPost');
        $manager->delete($request->getData('id'));
        $this->app()->HttpResponse()->redirect('/admin');
    }

    public function executeSubmitComment(HTTPRequest $request)
    {
        $manager = $this->rm->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $this->page->addVar('blogPost', $blogpost);
        $comment = new Comment();
        $comment->setIdBlog($request->getData('id'));
        $comment->setUpdateDate(date("Y-m-d H:i:s"));
        $form = new CommentForm($comment, '/blogpost/' . $request->getData('id') . '/submitcomment', $request);

        if ($form->isValid()) {
            $manager = $this->rm->getManagerOf('Comment');
            $manager->add($comment);
            $this->app()->HttpResponse()->redirect('/blogpost/' . $request->getData('id'));
        } else {
            $this->page->addVar('commentForm', $form);
            $this->setView('ShowBlogPost');
        }
    }

    public function executeShowComments(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $this->executeShowBlogPost($request);
    }

    public function executeDeleteComment(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $manager = $this->rm->getManagerOf('Comment');
        $manager->delete($request->getData('comment_id'));
        $this->app()->HttpResponse()->redirect('/admin/blogpost/' . $request->getData('id') . '/commentaires');
    }

    public function executeValidateComment(HTTPRequest $request)
    {
        $this->testAuthentication($request);
        $manager = $this->rm->getManagerOf('Comment');
        $comment = $manager->getUnique($request->getData('comment_id'));
        $comment->setValid(true);
        $manager->upDate($comment);
        $this->app()->HttpResponse()->redirect('/admin/blogpost/' . $request->getData('id') . '/commentaires');
    }

    public function testAuthentication(HTTPRequest $request)
    {
        /* On vérifie d'abord la validité du ticket */
        if (isset($_COOKIE['tc']) && isset($_SESSION['ticket'])) {
            if (isset($_SESSION['auth']) && $_COOKIE['tc'] == $_SESSION['ticket']) {
                $ticket = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
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
