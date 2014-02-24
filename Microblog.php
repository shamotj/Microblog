<?php
namespace Neonus\Microblog;

use Exception;

class Microblog
{
    private $dataDirectory = '';

    /**
    * Constructor.
    *
    * @param string $dataDirectory Path to directory with post files.
    * @return Null
    */
    public function __construct($dataDirectory)
    {
        if (empty($dataDirectory)) {
            throw new Exception('Data directory is not set');
        }

        $this->dataDirectory = rtrim($dataDirectory, '/') . '/';
    }

    /**
    * Get list of all posts ordered by date.
    *
    * @param bool $reverseSort Sort array in ASC or DSC order. Default NULL is ASC.
    * @return Array of Post class
    */
    public function getPosts($reverseSort = null)
    {
        $posts = array();
        $fileNames = $this->loadFiles($this->dataDirectory);

        foreach ($fileNames as $fileName) {
            if (preg_match('/^(\d+)_(.*)\.([a-zA-Z]+)$/', $fileName, $matches)) {
                $posts[] = new Post(strtotime($matches[1]), $matches[2], $matches[3], $this->dataDirectory . $fileName);
            }
        }

        if ($reverseSort) {
            usort($posts, array($this, "sortByDateReverse"));
        } else {
            usort($posts, array($this, "sortByDate"));
        }

        return $posts;
    }

    /**
     * Search for post by specified URL.
     *
     * @param string $url URL to look for
     * @return Post Instance of blog post object or FALSE if none found
     */
    public function getPostByUrl($url)
    {
        // first get array of URL of all posts, indexed by url
        $posts = $this->getPostsIndexedByUrl();

        // now check if such key exists in array
        if (array_key_exists($url, $posts)) {
            return $posts[$url];
        }

        return false;
    }

    /**
    * Loads all files from specified directory.
    *
    * @param string $directory Path to directory containig data files
    * @return Array of file names
    */
    private function loadFiles($directory)
    {
        $files = array();

        if ($dirHandle = opendir($directory)) {
            while (false !== ($entry = readdir($dirHandle))) {
                if ($entry !== '.' && $entry !== '..') {
                    $files[] = $entry;
                }
            }
            closedir($dirHandle);
        } else {
            throw new Exception('Cannot open dir');
        }

        return $files;
    }

    private function sortByDate($a, $b)
    {
        if ($a->getDate() == $b->getDate()) {
            return 0;
        }

        return ($a->getDate() < $b->getDate()) ? -1 : 1;
    }

    private function sortByDateReverse($a, $b)
    {
        if ($a->getDate() == $b->getDate()) {
            return 0;
        }

        return ($a->getDate() < $b->getDate()) ? 1 : -1;
    }

    /**
     * Get all posts in array indexed by URL
     *
     * @return Array of indexed posts. URL is key.
     */
    private function getPostsIndexedByUrl()
    {
        $posts = $this->getPosts();
        $indexedPosts = array();

        foreach ($posts as $post) {
            $indexedPosts[$post->getUrl()] = $post;
        }

        return $indexedPosts;
    }
}
