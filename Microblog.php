<?php
namespace Neonus\Microblog;

use Exception;
use Neonus\Microblog\Post;


class Microblog 
{
    var $dataDirectory;

    public function __construct($dataDirectory)
    {
        if (empty($dataDirectory))
        {
            throw new Exception('Data directory is not set');
        }

        $this->dataDirectory = $dataDirectory;
    }

    public function getPosts($reverseSort = null)
    {
        $posts = array();
        $fileNames = $this->loadFiles($this->dataDirectory);

        foreach ($fileNames as $fileName)
        {
            if (preg_match('/^(\d+)_(.*)\.([a-zA-Z]+)$/', $fileName, $matches))
            {
                $posts[] = new Post(strtotime($matches[1]), $matches[2], $matches[3], $fileName);
            }
        }

        if ($reverseSort)
        {
            usort($posts, array($this, "sortByDateReverse"));
        }
        else
        {
            usort($posts, array($this, "sortByDate"));
        }

        return $posts;
    }

    private function loadFiles($directory)
    {
        $files = array();

        if ($dirHandle = opendir($directory))
        {
            while (false !== ($entry = readdir($dirHandle)))
            {
                if ($entry !== '.' && $entry !== '..')
                {
                    $files[] = $entry;
                }
            }
            closedir($dirHandle);
        }
        else
        {
            throw new Exception('Cannot open dir');
        }

        return $files;
    }

    private function sortByDate($a, $b)
    {
        if ($a->getDate() == $b->getDate())
        {
            return 0;
        }

        return ($a->getDate() < $b->getDate()) ? -1 : 1;
    }

    private function sortByDateReverse($a, $b)
    {
        if ($a->getDate() == $b->getDate())
        {
            return 0;
        }

        return ($a->getDate() < $b->getDate()) ? 1 : -1;
    }
}
?>
