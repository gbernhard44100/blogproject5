<?php
namespace App\Blog\Modules\BlogPost;

use Lib\GBFram\BackController;
use \Lib\GBFram\HTTPRequest;
use \App\Blog\Entity\BlogPost;
use \App\Blog\Entity\Comment;
use \App\Blog\Models\BlogPostForm;
use \App\Blog\Models\CommentForm;

class BlogPostController extends BackController{
    
    public function executeShowAllBlogPosts(HTTPRequest $request){        
        $manager = $this->managers->getManagerOf('BlogPost');
        $list = $manager->getList();
        $this->page->addVar('blogPosts',$list);
    }

    public function executeShowBlogPost(HTTPRequest $request){
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $this->page->addVar('blogPost',$blogpost);
        $commentManager = $this->managers->getManagerOf('Comment');
        $comments = $commentManager->getList(['idBlog' => $request->getData('id')]);
        $this->page->addVar('comments',$comments);
        $form = new CommentForm(new Comment(),'/blogpost/'.$request->getData('id').'/submitcomment');
        $this->page->addVar('commentForm',$form);
    }
    
    public function executeShowAdminPage(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogposts = $manager->getList();
        $this->page->addVar('blogPosts',$blogposts);
    }    
    
    public function executeShowAddBlogPostPage(HTTPRequest $request){
        $blogpost = new BlogPost();
        $blogpost->hydrateFromPostRequest($request);
        $form = new BlogPostForm($blogpost,'/admin/addblogPost');
        $this->page->addVar('form',$form);
    }  
    
    public function executeAddBlogPost(HTTPRequest $request){
        $blogpost = new BlogPost();
        $blogpost->hydrateFromPostRequest($request);
        $blogpost->setUpdateDate(date("Y-m-d H:i:s"));
        $form =new BlogPostForm($blogpost,'/admin/addblogPost');

        if($form->isValid()){
            $manager = $this->managers->getManagerOf('BlogPost');
            $manager->add($blogpost);
            $this->app()->HttpResponse()->redirect('/mesblogposts');
        }
        else{
            $this->page->addVar('form',$form);
        }
    }   
    
    public function executeShowUpdateBlogPostPage(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $form =new BlogPostForm($blogpost,'/admin/blogpost/'.$request->getData('id').'/submitmodifications');
        $this->page->addVar('form',$form);        
    }    
    
    public function executeUpdateBlogPost(HTTPRequest $request) {
        $blogpost = new BlogPost();
        $blogpost->hydrateFromPostRequest($request);
        $blogpost->setId($request->getData('id'));
        $blogpost->setUpdateDate(date("Y-m-d H:i:s"));
        $form =new BlogPostForm($blogpost,'/admin/blogpost/'.$request->getData('id').'/submitmodifications');
        
        if($form->isValid()){
            $manager = $this->managers->getManagerOf('BlogPost');
            $manager->upDate($blogpost);
            $this->app()->HttpResponse()->redirect('/admin');
        }
        else{
            $this->page->addVar('form',$form);
        }
    }
    
    public function executeDeleteBlogPost(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $manager->delete($request->getData('id'));
        $this->app()->HttpResponse()->redirect('/admin');
    }

    public function executeSubmitComment(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('BlogPost');
        $blogpost = $manager->getUnique($request->getData('id'));
        $this->page->addVar('blogPost',$blogpost);
        $comment = new Comment();
        $comment->hydrateFromPostRequest($request);
        $comment->setIdBlog($request->getData('id'));
        $comment->setUpdateDate(date("Y-m-d H:i:s"));
        $form =new CommentForm($comment,'/blogpost/'.$request->getData('id').'/submitcomment');

        if($form->isValid()){
            $manager = $this->managers->getManagerOf('Comment');
            $manager->add($comment);
            $this->app()->HttpResponse()->redirect('/blogpost/'.$request->getData('id'));
        }
        else{
            $this->page->addVar('commentForm',$form);
        }
    }    
    
    public function executeShowComments(HTTPRequest $request) {
        $this->executeShowBlogPost($request);
    }
    
    public function executeDeleteComment(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('Comment');
        $manager->delete($request->getData('comment_id'));
        $this->app()->HttpResponse()->redirect('/admin/blogpost/'.$request->getData('id').'/commentaires');        
    }
    
    public function executeValidateComment(HTTPRequest $request) {
        $manager = $this->managers->getManagerOf('Comment');
        $comment = $manager->getUnique($request->getData('comment_id'));
        $comment->setValid(TRUE);
        $manager->upDate($comment);
        $this->app()->HttpResponse()->redirect('/admin/blogpost/'.$request->getData('id').'/commentaires');
    }
    
}