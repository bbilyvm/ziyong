<?php

namespace Nexus\Imdb;

use Imdb\Config;
use Imdb\Title;

class Imdb
{
    private $config;

    private $movie;

    private $pages = array('Title', 'Credits', 'Amazon', 'Goofs', 'Plot', 'Comments', 'Quotes', 'Taglines', 'Plotoutline', 'Trivia', 'Directed');

    public function __construct()
    {
        $config = new Config();
        $cacheDir = ROOT_PATH . 'imdb/cache/';
        $photoRoot = 'images/';
        $photoDir = ROOT_PATH . "imdb/$photoRoot";
        $this->checkDir($cacheDir, 'imdb_cache_dir');
        $this->checkDir($photoDir, 'imdb_photo_dir');

        $config->cachedir = $cacheDir;
        $config->photodir = $photoDir;
        $config->photoroot = $photoRoot;
        $this->config = $config;
    }

    private function checkDir($dir, $langKeyPrefix)
    {
        global $lang_functions;
        if (!is_dir($dir)) {
            $mkdirResult = mkdir($dir, 0777, true);
            if ($mkdirResult !== true) {
                $msg = $lang_functions["{$langKeyPrefix}_can_not_create"];
                do_log("$msg, dir: $dir");
                throw new ImdbException($msg);
            }
        }
        if (!is_writable($dir)) {
            $msg = $lang_functions["{$langKeyPrefix}_is_not_writeable"];
            do_log("$msg, dir: $dir");
            throw new ImdbException($msg);
        }
        return true;
    }

    public function getCachedAt(int $id, string $page)
    {
        $id = parse_imdb_id($id);
        $log = "id: $id, page: $page";
        $cacheFile = $this->getCacheFilePath($id, $page);
        if (!file_exists($cacheFile)) {
            $log .= ", file: $cacheFile not exits";
        }
        $result = filemtime($cacheFile);
        $log .= ", cache at: $result";
        do_log($log);
        return $result;
    }

    /**
     * @date 2021/1/18
     * @param int $id
     * @param string $page Title, Credits, etc...
     * @return int state (0-not complete, 1-cache complete)
     */
    public function getCacheStatus(int $id, string $page)
    {
        return 1;
        $id = parse_imdb_id($id);
        $log = "id: $id, page: $page";
        $cacheFile = $this->getCacheFilePath($id, $page);
        if (!file_exists($cacheFile)) {
            $log .= ", file: $cacheFile not exits";
            do_log($log);
            return 0;
        }
        if (!fopen($cacheFile, 'r')) {
            $log .= ", file: $cacheFile can not open";
            do_log($log);
            return 0;
        }
        return 1;
    }

    public function purgeSingle($id)
    {
        foreach ($this->pages as $page) {
            $file = $this->getCacheFilePath($id, $page);
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return true;
    }

    public function getMovie($id)
    {
        if (!$this->movie) {
            $this->movie = new Title($id, $this->config);
        }
        return $this->movie;
    }

    private function getCacheFilePath($id, $page)
    {
        return sprintf('%s%s.%s', $this->config->cachedir, $id, $page);
    }

    public function renderDetailsPageDescription($torrentId, $imdbId)
    {
        global $lang_details;
        $movie = $this->getMovie($imdbId);
        $thenumbers = $imdbId;
        $country = $movie->country ();
        $director = $movie->director();
        $creator = $movie->creator(); // For TV series
        $write = $movie->writing();
        $produce = $movie->producer();
        $cast = $movie->cast();
//						$plot = $movie->plot ();
        $plot_outline = $movie->plotoutline();
        $compose = $movie->composer();
        $gen = $movie->genres();
        //$comment = $movie->comment();
//        $similiar_movies = $movie->similiar_movies();

        $autodata = '<a href="https://www.imdb.com/title/tt'.$thenumbers.'">https://www.imdb.com/title/tt'.$thenumbers."</a><br /><strong><font color=\"navy\">------------------------------------------------------------------------------------------------------------------------------------</font><br />\n";
        $autodata .= "<font color=\"darkred\" size=\"3\">".$lang_details['text_information']."</font><br />\n";
        $autodata .= "<font color=\"navy\">------------------------------------------------------------------------------------------------------------------------------------</font></strong><br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">". $lang_details['text_title']."</font></strong>" . "".$movie->title ()."<br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_also_known_as']."</font></strong>";

        $temp = "";
        foreach ($movie->alsoknow() as $ak)
        {
			$temp .= $ak["title"].$ak["year"]. ($ak["country"] != "" ? " (".$ak["country"].")" : "") . ($ak["comment"] != "" ? " (" . $ak["comment"] . ")" : "") . ", ";
        }
        $autodata .= rtrim(trim($temp), ",");
        $runtimes = str_replace(" min",$lang_details['text_mins'], $movie->runtime());
        $autodata .= "<br />\n<strong><font color=\"DarkRed\">".$lang_details['text_year']."</font></strong>" . "".$movie->year ()."<br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_runtime']."</font></strong>".$runtimes."<br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_votes']."</font></strong>" . "".$movie->votes ()."<br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_rating']."</font></strong>" . "".$movie->rating ()."<br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_language']."</font></strong>" . "".$movie->language ()."<br />\n";
        $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_country']."</font></strong>";

        $temp = "";
        for ($i = 0; $i < count ($country); $i++)
        {
            $temp .="$country[$i], ";
        }
        $autodata .= rtrim(trim($temp), ",");

        $autodata .= "<br />\n<strong><font color=\"DarkRed\">".$lang_details['text_all_genres']."</font></strong>";
        $temp = "";
        for ($i = 0; $i < count($gen); $i++)
        {
            $temp .= "$gen[$i], ";
        }
        $autodata .= rtrim(trim($temp), ",");

        $autodata .= "<br />\n<strong><font color=\"DarkRed\">".$lang_details['text_tagline']."</font></strong>" . "".$movie->tagline ()."<br />\n";
        if ($director){
            $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_director']."</font></strong>";
            $temp = "";
            for ($i = 0; $i < count ($director); $i++)
            {
                $temp .= "<a target=\"_blank\" href=\"https://www.imdb.com/" . "".$director[$i]["imdb"]."" ."\">" . $director[$i]["name"] . "</a>, ";
            }
            $autodata .= rtrim(trim($temp), ",");
        }
        elseif ($creator)
            $autodata .= "<strong><font color=\"DarkRed\">".$lang_details['text_creator']."</font></strong>".$creator;

        $autodata .= "<br />\n<strong><font color=\"DarkRed\">".$lang_details['text_written_by']."</font></strong>";
        $temp = "";
        for ($i = 0; $i < count ($write); $i++)
        {
            $temp .= "<a target=\"_blank\" href=\"https://www.imdb.com/" . "".$write[$i]["imdb"]."" ."\">" . "".$write[$i]["name"]."" . "</a>, ";
        }
        $autodata .= rtrim(trim($temp), ",");

        $autodata .= "<br />\n<strong><font color=\"DarkRed\">".$lang_details['text_produced_by']."</font></strong>";
        $temp = "";
        for ($i = 0; $i < count ($produce); $i++)
        {
            $temp .= "<a target=\"_blank\" href=\"https://www.imdb.com/" . "".$produce[$i]["imdb"]."" ." \">" . "".$produce[$i]["name"]."" . "</a>, ";
        }
        $autodata .= rtrim(trim($temp), ",");

        $autodata .= "<br />\n<strong><font color=\"DarkRed\">".$lang_details['text_music']."</font></strong>";
        $temp = "";
        for ($i = 0; $i < count($compose); $i++)
        {
            $temp .= "<a target=\"_blank\" href=\"https://www.imdb.com/" . "".$compose[$i]["imdb"]."" ." \">" . "".$compose[$i]["name"]."" . "</a>, ";
        }
        $autodata .= rtrim(trim($temp), ",");

        $autodata .= "<br /><br />\n\n<strong><font color=\"navy\">------------------------------------------------------------------------------------------------------------------------------------</font><br />\n";
        $autodata .= "<font color=\"darkred\" size=\"3\">".$lang_details['text_plot_outline']."</font><br />\n";
        $autodata .= "<font color=\"navy\">------------------------------------------------------------------------------------------------------------------------------------</font></strong>";

//						if(count($plot) == 0)
//						{
//							$autodata .= "<br />\n".$plot_outline;
//						}
//						else
//						{
//							for ($i = 0; $i < count ($plot); $i++)
//							{
//								$autodata .= "<br />\n<font color=\"DarkRed\">.</font> ";
//								$autodata .= $plot[$i];
//							}
//						}
        if (!empty($plot_outline)) {
            $autodata .= "<br />\n".$plot_outline;
        }


        $autodata .= "<br /><br />\n\n<strong><font color=\"navy\">------------------------------------------------------------------------------------------------------------------------------------</font><br />\n";
        $autodata .= "<font color=\"darkred\" size=\"3\">".$lang_details['text_cast']."</font><br />\n";
        $autodata .= "<font color=\"navy\">------------------------------------------------------------------------------------------------------------------------------------</font></strong><br />\n";

        for ($i = 0; $i < count ($cast); $i++)
        {
            if ($i > 9)
            {
                break;
            }
            $autodata .= "<font color=\"DarkRed\">.</font> " . "<a target=\"_blank\" href=\"https://www.imdb.com/" . "".$cast[$i]["imdb"]."" ."\">" . $cast[$i]["name"] . "</a> " .$lang_details['text_as']."<strong><font color=\"DarkRed\">" . "".$cast[$i]["role"]."" . " </font></strong><br />\n";
        }

       return $autodata;

    }
}