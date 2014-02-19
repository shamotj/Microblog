<?php
namespace Neonus\Microblog;

class Post
{
    private $date;
    private $title;
    private $type;
    private $filename;

    public function __construct($date = null, $title = null, $type = null, $filename = null)
    {
        if ($date) $this->setDate($date);
        if ($title) $this->setTitle($title);
        if ($type) $this->setType($type);
        if ($filename) $this->setFilename($filename);
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getTitle()
    {
        return $this->title();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

}

?>
