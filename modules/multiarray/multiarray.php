<?php
/**
 * =============================================================================
 * MultiArray
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * This class is used for working with multi-dimensional arrays specifically 
 * with parent/child associations.
 *
 * TODO Convert the rest of the methods to static
 * =============================================================================
 */
class MultiArray {
    
	private static $_data = array();

	public static function load($data) {
		self::$_data = $data;
	}

    /**
	 * -------------------------------------------------------------------------
     * Sorts a multi-dimensional array based on id and parent id associations.
     * Returns a multi-dimensional array.
     *
     * Each array must contain an 'id' and 'parent_id' key. The 'id' key
     * represents the id of that array, and 'parent_id' represents an array it
     * may be associated with. If an array does not have a parent, 'parent_id'
     * should equal '0'. 
     *
     * @return array
	 * -------------------------------------------------------------------------
     */
    public function sort($dataArr = null, $parentID = 0)
    {
        $tmpArr = array();
        if(is_null($dataArr))
            $dataArr = $this->dataArr;
            
        foreach($dataArr as $node)
        {
            if($node['parent_id'] == $parentID)
            {
                $node['children'] = $this->sort($dataArr, $node['id']);
                $tmpArr[] = $node;
            }
        }
        
        return $tmpArr;
    }

    /**
     * Returns a single dimension array of children assocated with the
     * given parent ID. Only goes one level deep. Sub-children are NOT included.
     * 
     * @param int $parentID
     * @return array
     */
    public function childrenOf($parentID)
    {
        $tmpArr = array();
        foreach($this->dataArr as $node)
            if($node['parent_id'] == $parentID)
                $tmpArr[] = $node;

        return $tmpArr;
    }

    /**
     * Returns a single dimension array of all children associated with the
     * given parent ID. Includes all sub-children to an unlimited depth.
     * 
     * @param int $parentID
     * @return array
     */
    public function allChildrenOf($parentID = 0, &$tmpArr = array())
    {
        foreach($this->dataArr as $node)
        {
            if($node['parent_id'] == $parentID)
            {
                $tmpArr[] = $node['id'];
                $this->allChildrenOf($node['id'], $tmpArr);
            }
        }

        return $tmpArr;
    }

    public static function indent($spacer = '&nbsp;&nbsp;&nbsp;&nbsp;', $parent_cid = 0, &$items = array(), $depth = 0)
    {
        foreach(self::$_data as $node)
        {
            if($node['parent_cid'] == $parent_cid)
            {
                $indent = '';
                for($i = 0; $i < $depth; $i++)
                    $indent .= $spacer;
                    
                $node['depth'] = $depth;
                $node['indent'] = $indent;

                $items[] = $node;
                self::indent($spacer, $node['cid'], $items, ($depth + 1));
            }
        }

        return $items;
    }
    
}
?>
