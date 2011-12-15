<?php

class MultiArray extends Module {
    

    /**
     * TODO
     */
	private static $_data = array();


    /**
     * TODO
     */
    private static $_parentKey = 'parent_id';


    /**
     * TODO
     */
    private static $_childKey = 'children';


    /**
	 * --------------------------------------------------------------------------- 
     * TODO
	 * --------------------------------------------------------------------------- 
     */
    public static function load($data, $parentKey = 'parent_id', $childKey = 'children')
    {
        self::$_data = $data;
        self::$_parentKey = $parentKey;
        self::$_childKey = $childKey;
    }


    /**
	 * --------------------------------------------------------------------------- 
     * Sorts a multi-dimensional array based on id and parent id associations.
     * Returns a multi-dimensional array.
     *
     * Each array must contain an 'id' and 'parent_id' key. The 'id' key
     * represents the id of that array, and 'parent_id' represents an array it
     * may be associated with. If an array does not have a parent, 'parent_id'
     * should equal '0'. 
     *
     * @return array
	 * --------------------------------------------------------------------------- 
     */
    public static function sort($dataArr = null, $parentId = 0)
    {
        $tmpArr = array();

        if(is_null($dataArr))
            $dataArr = self::$_data;
            
        foreach($dataArr as $node)
        {
            if($node[self::$_parentKey] == $parentId)
            {
                $node[self::$_childKey] = self::sort($dataArr, $node['id']);
                $tmpArr[] = $node;
            }
        }
        
        return $tmpArr;
    }


    /**
	 * --------------------------------------------------------------------------- 
     * Returns a single dimension array of children assocated with the
     * given parent ID. Only goes one level deep. Sub-children are NOT included.
     * 
     * @param int $parentID
     * @return array
	 * --------------------------------------------------------------------------- 
     */
    public static function childrenOf($parentId)
    {
        $tmpArr = array();

        foreach(self::$_data as $node)
            if($node[self::$_parentKey] == $parentId)
                $tmpArr[] = $node;

        return $tmpArr;
    }


    /**
	 * --------------------------------------------------------------------------- 
     * Returns a single dimension array of all children associated with the
     * given parent ID. Includes all sub-children to an unlimited depth.
     * 
     * @param int $parentID
     * @return array
	 * --------------------------------------------------------------------------- 
     */
    public static function allChildrenOf($parentId = 0, &$tmpArr = array())
    {
        foreach(self::$_data as $node)
        {
            if($node[self::$_parentKey] == $parentId)
            {
                $tmpArr[] = $node['id'];
                self::allChildrenOf($node['id'], $tmpArr);
            }
        }

        return $tmpArr;
    }


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    public static function indent($spacer = '&nbsp;&nbsp;&nbsp;&nbsp;', $parentId = 0, &$items = array(), $depth = 0)
    {
        foreach(self::$_data as $node)
        {
            $nodeParentId = (is_object($node)) ? $node->{self::$_parentKey} : $node[self::$_parentKey];

            if($nodeParentId == $parentId)
            {
                $indent = '';

                for($i = 0; $i < $depth; $i++)
                    $indent .= $spacer;
                    
                if(is_object($node))
                {
                    $node->depth = $depth;
                    $node->indent = $indent;
                }
                else
                {
                    $node['depth'] = $depth;
                    $node['indent'] = $indent;
                }

                $items[] = $node;

                $nodeId = (is_object($node)) ? $node->id : $node['id'];
                self::indent($spacer, $nodeId, $items, ($depth + 1));
            }
        }

        return $items;
    }
    

}
