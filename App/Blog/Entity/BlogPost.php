<?php

namespace App\Blog\Entity;

use \Lib\GBFram\Entity;

class BlogPost extends Entity
{

    protected $id;
    protected $author;
    protected $title;
    protected $content;
    protected $updateDate;

    public function __construct()
    {
        $this->repository = 'App\Blog\Models\BlogPostRepositoryPDO';
    }

    public function id()
    {
        return $this->id;
    }

    public function author()
    {
        return $this->author;
    }

    public function title()
    {
        return $this->title;
    }

    public function content()
    {
        return $this->content;
    }

    public function updateDate()
    {
        return $this->updateDate;
    }

    public function setId(int $id)
    {
        if ($id > 0) {
            $this->id = $id;
        }
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
    }

}
