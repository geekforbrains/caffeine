<?php

class Media_Video extends Media_File {

        
    /**
     * --------------------------------------------------------------------------- 
     * Set the allowed image formats to be uploaded. Based on config in setup.php
     * --------------------------------------------------------------------------- 
     */
    public function __construct()
    {
        $this->_type = 'video';
        $this->_allowedExts = Config::get('media.allowed_video_formats');
    }

    
    /**
     * --------------------------------------------------------------------------- 
     * Used for saving videos from YouTube and Vimeo. Does NOT save a video file
     * from a url like file or image.
     * --------------------------------------------------------------------------- 
     */
    public function saveFromUrl($url)
    {
        if($data = $this->_getVideoData($url))
        {
            $response = Media::m('url')->insert(array(
                'url' => $url,
                'data' => serialize($data)
            ));

            if($response)
                $this->_id = $response;
            else
                $this->_error = 'Error inserting video to database.';
        }
        else
            $this->_error = 'Unable to get video data. Possible invalid url.';

        return $this;
    }


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    public function embed($id, $width = 560, $height = 345)
    {
        if($video = Media::m('url')->find($id))
        {
            $data = unserialize($video->data);

            if(stristr($video->url, 'youtube'))
            {
                return sprintf(
                    '<iframe width="%d" height="%d" src="http://www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe>',
                    $width,
                    $height,
                    $data['id']
                );
            }

            if(stristr($video->url, 'vimeo'))
            {
                return sprintf(
                    '<iframe src="http://player.vimeo.com/video/%s?title=0&amp;byline=0&amp;portrait=0" width="%d" height="%d" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>',
                    $data['id'],
                    $width,
                    $height
                );
            }
        }
    }


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    public function _getVideoData($url)
    {
        $videoId = $this->_getVideoId($url);

        if($videoId)
        {
            if(stristr($url, 'youtube') || stristr($url, 'youtu.be'))
            {
                $apiUrl = sprintf(Config::get('media.youtube_api'), $videoId);
                $xml = simplexml_load_file($apiUrl);
                    
                return array(
                    'id' => $videoId,
                    'title' => (string)$xml->title[0],
                    'description' => (string)$xml->content[0],
                    'thumbnail' => sprintf('http://i.ytimg.com/vi/%s/0.jpg', $videoId)
                );
            }

            elseif(stristr($url, 'vimeo'))
            {
                $apiUrl = sprintf(Config::get('media.vimeo_api'), $videoId);
                $xml = simplexml_load_file($apiUrl);

                return array(
                    'id' => $videoId,
                    'title' => (string)$xml->clip->title[0],
                    'description' => (string)$xml->clip->caption[0],
                    'thumbnail' => $xml->clip->thumbnail_large[0]
                );
            }
        }

        return false; // Invalid url
    }


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    private function _getVideoId($url)
    {
        if(stristr($url, 'youtu.be') || stristr($url, 'youtube.com/user/'))
        {
            $bits = explode('/', $url);
            return $bits[count($bits) - 1];
        }

        if(stristr($url, 'youtube'))
        {
            if(preg_match('/v=([A-Za-z0-9\_\.]+)/', $url, $match))
                return $match[1];
        }

        if(stristr($url, 'vimeo'))
        {
            if(preg_match('/\/([0-9]+)/', $url, $match))
                return $match[1];
        }

        return false;
    }


}
