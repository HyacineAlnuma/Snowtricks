<?php

namespace App\Service;

use App\Entity\Trick;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickManager 
{
    private const YOUTUBE_URL = 'https://www.youtube.com/embed/';

    public function __construct(private SluggerInterface $slugger) 
    {
    }

    public function manageVideoUrl(Collection $videos) : void
    {
        foreach($videos as $video) {
            $pattern = '/.*v=(.*)/';
            preg_match($pattern, $video->getVideoEmbed(), $match);
            if (isset($match[1])) {
                $videoId = $match[1];
                $videoUrl = self::YOUTUBE_URL . $videoId;
                $video->setVideoEmbed($videoUrl);
            }
        }
    }

    public function createSlug(string $name) : string
    {
        return $this->slugger->slug($name);
    }
}