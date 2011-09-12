<?php 

class Video {

    public static function albums()
    {
        View::load('Video', 'albums', array(
            'albums' => Video_Model_Albums::get_all()
        ));
    }

    public static function videos($album_cid)
    {
        View::load('Video', 'videos', array(
            'album' => Video_Model_Albums::get_by_cid($album_cid),
            'videos' => Video_Model::get_by_album_cid($album_cid)
        ));
    }

    public static function get_album_title($cid)
    {
        $album = Video_Model_Albums::get_by_cid($cid);
        return $album['name'];
    }

    /**
     * Returns the embed code for the given video cid
     */
    public static function embed($cid, $width = 560, $height = 345)
    {
        if(!$video = Video_Model::get_by_cid($cid))
            return null;

        if(stristr($video['url'], 'youtube'))
        {
            return sprintf(
                '<iframe width="%d" height="%d" src="http://www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe>',
                $width,
                $height,
                $video['video_id']
            );
        }

        if(stristr($video['url'], 'vimeo'))
        {
            return sprintf(
                '<iframe src="http://player.vimeo.com/video/%s?title=0&amp;byline=0&amp;portrait=0" width="%d" height="%d" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>',
                $video['video_id'],
                $width,
                $height
            );
        }
    }

    /**
     * Returns a videos data base on the URL. Only works with Youtube and Vimeo.
     */
    public static function get_data($url)
    {
        // Youtube
        if(stristr($url, 'youtube'))
        {
    	    // http://gdata.youtube.com/feeds/api/videos/%s
            $youtube_id = self::_get_video_id($url);

            if($youtube_id)
            {
                $api_url = sprintf(VIDEO_YOUTUBE_API, $youtube_id);
                $xml = simplexml_load_file($api_url);
                    
                return array(
                    'id' => $youtube_id,
                    'title' => $xml->title[0],
                    'description' => $xml->content[0],
                    'thumbnail' => sprintf('http://i.ytimg.com/vi/%s/0.jpg', $youtube_id)
                );
            }
        }

        // Vimeo
        if(stristr($url, 'vimeo'))
        {
            $vimeo_id = self::_get_video_id($url);

            if($vimeo_id)
            {
                $api_url = sprintf(VIDEO_VIMEO_API, $vimeo_id);
                $xml = simplexml_load_file($api_url);

                return array(
                    'id' => $vimeo_id,
                    'title' => $xml->clip->title[0],
                    'description' => $xml->clip->caption[0],
                    'thumbnail' => $xml->clip->thumbnail_large[0]
                );
            }
        }

        return false; // Invalid url
    }

    private static function _get_video_id($url)
    {
        if(stristr($url, 'youtube'))
        {
            if(preg_match('/v=([A-Za-z0-9]+)/', $url, $match))
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
