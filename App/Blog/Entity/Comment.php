<?php

namespace App\Blog\Entity;

use \Lib\GBFram\Entity;

class Comment extends Entity 
{

    protected $id;
    protected $author;
    protected $valid = FALSE;
    protected $content;
    protected $updateDate;
    protected $idBlog;

    public function id() 
    {
        return $this->id;
    }

    public function author() 
    {
        return $this->author;
    }

    public function content() 
    {
        return $this->content;
    }

    public function valid() 
    {
        return $this->valid;
    }

    public function updateDate() 
    {
        return $this->updateDate;
    }

    public function idBlog() 
    {
        return $this->idBlog;
    }

    public function setId(int $id)
    {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    public function setValid() 
    {
        $this->valid = TRUE;
    }

    public function setAuthor(string $author) 
    {
        $this->author = $author;
    }

    public function setContent(string $content) 
    {
        $this->content = $content;
    }

    public function setUpdateDate($updateDate) 
    {
        $this->updateDate = $updateDate;
    }

    public function setIdBlog(int $id) 
    {
        if ($id > 0) {
            $this->idBlog = $id;
        }
    }

}
