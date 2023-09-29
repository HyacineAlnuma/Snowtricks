<?php

namespace App\Service;

use App\Entity\Trick;

class TrickManager 
{
    private const YOUTUBE_URL = 'https://www.youtube.com/embed/';

    public function createVideoUrl(Trick $trick) : Trick
    {
        $videos = $trick->getVideos();
        foreach($videos as $video) {
            $pattern = '/.*v=(.*)/';
            preg_match($pattern, $video->getVideoEmbed(), $match);
            $videoId = $match[1];
            $videoUrl = self::YOUTUBE_URL . $videoId;
            $video->setVideoEmbed($videoUrl);
        }
        return $trick;
    }

    public function createSlug(Trick $trick) : Trick
    {
        $name = $trick->getName();
        $pattern = '/[^\w\-]+|-|_/';
        $slug = preg_replace($pattern, '', $name);
        $slug = strtolower($slug);
        $trick->setSlug($slug);
        return $trick;
    }
}