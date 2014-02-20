<?php
namespace Neonus\Microblog;

use \Exception;

class Post
{
    private $date;
    private $title;
    private $type;
    private $filename;

    /**
    * Constructor.
    * @param $date Integer Date in epoch format. Default null.
    * @param $title String Post title. Default null.
    * @param $type String Type of file. This is used later for parsing and templating. Default null.
    * @param $filename String Path to file storing this post.
    **/
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

    /** 
    * Read post content from file and return it in RAW format.
    * @return String RAW data from file.
    **/
    public function getContent()
    {
        if (!$this->getFilename())
	{
	    throw new Exception('No filename supplied. Cannot write file. Please use setFilename() first.');
	}

	if (!$content = file_get_contents($this->getFilename()))
	{
	    throw new Exception('Cannot open file \'' . $this->getFilename() . '\' for reading.');
	}

	return $content;
    }

}

?>
