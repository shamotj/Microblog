<?php
namespace Neonus\Microblog;

use \Exception;
use \Michelf\MarkdownExtra;
use \Texy;


class Post
{
    private $date;
    private $title;
    private $type;
    private $filename;
    private $url;

    /**
     * Constructor.
     * @param integer $date Date in epoch format. Default null.
     * @param string $title Post title. Default null.
     * @param string $type Type of file. This is used later for parsing and templating. Default null.
     * @param string $filename Path to file storing this post.
     */
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
        return $this->title;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getUrl()
    {
        if ($this->url) {
            return $this->url;
        } else {
            $url = $this->generateUrl();
            $this->setUrl($url);

            return $url;
        }
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

    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Read post content from file and return it in RAW format.
     *
     * @return string RAW data from file.
     */
    public function getContentRaw()
    {
        if (!$this->getFilename()) {
            throw new Exception('No filename supplied. Cannot write file. Please use setFilename() first.');
        }

        if (!$content = file_get_contents($this->getFilename())) {
            throw new Exception('Cannot open file \'' . $this->getFilename() . '\' for reading.');
        }

        return $content;
    }

    /**
     * Read RAW content and process it with correspongind tool
     *
     * @return string Processed content
     */
    public function getContent()
    {
        $content = $this->getContentRaw();

        switch ($this->getType())
        {
        case 'md':
            $content = $this->processMarkdown($content);
            break;
        case 'texy':
            $content = $this->processTexy($content);
            break;
        default:
            // do nothing
            break;
        }
        return $content;
    }

    /**
     * Get excerpt from blog post.
     *
     * @param integer $sentenceLength How many sentences should be returned.
     * @return string Blog exceprt.
     */
    public function getExcerpt($sentenceLength = 4)
    {
        $content = $this->getContent();
        $content = strip_tags($content);
        $i = 0;
        $pos = 0;
        while ($i < $sentenceLength)
        {
            $pos = strpos($content, '.', $pos);
            $pos++;
            $i++;
        }

        if ($pos) {
            return substr($content, 0, $pos);
        } else {
            return $content;
        }
    }

    /**
     * Convert title to string valid for URL
     *
     * @return String URL for title
     */
    private function generateUrl()
    {
	setlocale(LC_CTYPE, 'en_US.utf-8');
        $url = $this->getTitle();
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', mb_strtolower($url, 'utf-8')); // substitutes anything but letters and numbers with separator
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url); // TRANSLIT does the whole job
        $url = preg_replace('~[^-A-Za-z0-9_]+~', '', $url); // keep only letters, numbers and separator

        return $url;
    }

    /**
     * Process Markdown format to HTML.
     *
     * @param string $content Markdown string to convert
     * @return string HTML result of conversion
     */
    private function processMarkdown($content)
    {
        return MarkdownExtra::defaultTransform($content);
    }

    /**
     * Process Texy format to HTML.
     *
     * @param string $content Texy string to convert
     * @return string HTML result of conversion
     */
    private function processTexy($content)
    {
        $texy = new Texy();
        return $texy->process($content);
    }
}
