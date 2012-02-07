<?php 

/**
-----------------------------------------------------
NOTE: THIS IS IN DEVELOPMENT, DO NOT USE IT RIGHT NOW
-----------------------------------------------------

The template module can be used in place of PHP in html views.

SYNTAX IDEAS:

// block method
{module:method[param,param]}
    {object.property}
{/module}

// string param
{module:method["some param", "another spacer"]}

// return method
{module:method/}

// logical block
{if value1 is value2} // if value1 == value2
    // Code to execute
{/if}

{if value1 is-not value2} // If value1 !== value2
    // Code to execute
{/if}

// Method chaining
{module:method[module:method[param1, param2]]}

// If more than one result is returned, the html between the tags is ouput multiple times

{blog:recentposts[3]} // get 3 of the most recent blog posts
    {post.title}
    {post.body}

    {blog:postimages[post.id]}
        {html:image[image.id, 500, 500]} // Direct all methods just return data, no end tag
    {/blog}
{/blog}
*/
class Template extends Module {

    private static $_currentTag = null;
    private static $_inTag = false;

    public static function parse($data)
    {
        for($i = 0; $i < strlen($data); $i++)
        {
            if($data{$i} == '{')
                self::$_inTag = true;

            elseif($data{$i} == '}')
            {
                self::$_inTag = false;
                self::_determineTag(self::$_currentTag);
                self::$_currentTag = null;
            }

            elseif(self::$_inTag)
                self::$_currentTag .= $data{$i};
        }
    }

    private static function _determineTag($tag)
    {
        $tag = trim($tag);

        if(preg_match('/([\w]+):([\w]+)[\[]?([^\]]*)[\]]?/', $tag, $match))
        {
            echo "starting method<br />";
            print_r($match);
        }

        elseif(preg_match('/if\s+([\w\.]+)\s+([a-z]+)\s+([\w]+)/', $tag, $match))
        {
            echo "starting logical<br />";
            print_r($match);
        }

        elseif(preg_match('/([\w]+)\.([\w]+)/', $tag, $match))
        {
            echo "getting property<br />";
            print_r($match);
        }

        elseif(preg_match('/\/([\w]+)/', $tag, $match))
        {
            echo "ending block<br />";
            print_r($match);
        }

        else
            echo "unkown<br />";
    }

}
