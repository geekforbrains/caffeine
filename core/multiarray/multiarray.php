<?php

class MultiArray extends Module {
    
	private $_data      = array();      // Stores loaded array data to be manipulated
    private $_parentKey = 'parent_id';  // The default parent key to use when sorting
    private $_childKey  = 'children';   // The default child key to use when sorting

    /**
     * Loads the given information into a new MultiArray object and returns it.
     * This is a short-hand method for: new MultiArray(params...)
     */
    public static function load($data, $parentKey = null, $childKey = null) {
        return new MultiArray($data, $parentKey, $childKey);
    }

    /**
     * Loads given data into properties on instantiation. The given params are
     * used in all following methods.
     */
    public function __construct($data, $parentKey = null, $childKey = null)
    {
        $this->_data = $data;

        if(!is_null($parentKey))
            $this->_parentKey = $parentKey;

        if(!is_null($childKey))
            $this->_childKey = $childKey;
    }

    /**
     * Sorts a multi-dimensional array based on id and parent id associations.
     * Returns a multi-dimensional array.
     *
     * Each array must contain an 'id' and 'parent_id' key. The 'id' key
     * represents the id of that array, and 'parent_id' represents an array it
     * may be associated with. If an array does not have a parent, 'parent_id'
     * should equal '0'. 
     *
     * @return array
     */
    public function sort($data = null, $parentId = 0)
    {
        $sorted = array();

        if(is_null($data))
            $data = $this->_data;
            
        foreach($data as $node)
        {
            if($node[$this->_parentKey] == $parentId)
            {
                $node[$this->_childKey] = $this->sort($data, $node['id']);
                $sorted[] = $node;
            }
        }
        
        return $sorted;
    }

    /**
     * Returns a single dimension array of children assocated with the
     * given parent ID. Only goes one level deep. Sub-children are NOT included.
     * 
     * @param int $parentID
     * @return array
     */
    public static function childrenOf($parentId)
    {
        $children = array();

        foreach($this->_data as $node)
            if($node[$this->_parentKey] == $parentId)
                $children[] = $node;

        return $children;
    }

    /**
     * Returns a single dimension array of all children associated with the
     * given parent ID. Includes all sub-children to an unlimited depth.
     * 
     * @param int $parentID
     * @return array
     */
    public function allChildrenOf($parentId = 0, &$children = array())
    {
        foreach($this->_data as $node)
        {
            if($node[$this->_parentKey] == $parentId)
            {
                $children[] = $node['id'];
                $this->allChildrenOf($node['id'], $children);
            }
        }

        return $children;
    }

    /**
     * Indents $data based on parent ids. For each depth reached, the indent spacer is applied to that node
     * X number of times, where X is the current depth.
     *
     * Note that the data itself is not modified, but an "indent" property (for objects) or key (for arrays) 
     * is added.
     *
     * @return array
     */
    public function indent($spacer = '&nbsp;&nbsp;&nbsp;&nbsp;', $parentId = 0, &$items = array(), $depth = 0)
    {
        foreach($this->_data as $node)
        {
            $nodeParentId = (is_object($node)) ? $node->{$this->_parentKey} : $node[$this->_parentKey];

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
                $this->indent($spacer, $nodeId, $items, ($depth + 1));
            }
        }

        return $items;
    }

}
