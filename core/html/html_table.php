<?php

class Html_Table {

    /**
     * The string of table html to be output when rendered.
     */
    private $_html = ''; // Pointer
    private $_headerHtml = '';
    private $_rowHtml = '';

    private $_pointer = null;

    /**
     * Stores the current tag we're in (th or td)
     */
    private $_tag = null;

    /**
     * Used to determine if more than one tr has been added, and if the previous tr created
     * needs to be closed.
     */
    private $_tr = null;

    /**
     * Used to determine if we need to add closing tags to tables and trs when calling render
     * for the first time.
     */
    private $_rendered = false;

    /**
     * Starts the table tag and adds any table level attributes
     */
    public function __construct($args = array())
    {
        $this->_pointer =& $this->_html;
        $this->_pointer = '<table';

        if(isset($args[0]))
            $this->_addAttr($args[0]);

        $this->_pointer .= '>';
    }

    public function addHeader($attributes = array())
    {
        $this->_pointer =& $this->_headerHtml;
        $this->_tag = 'th';

        $this->_tr($attributes);
        return $this;
    }

    public function addRow($attributes = array())
    {
        $this->_pointer =& $this->_rowHtml;
        $this->_tag = 'td';
        $this->_tr($attributes);
        return $this;
    }

    public function addCol($content, $attributes = array())
    {
        // Allow different tags to be set in header (ex: td)
        $tag = $this->_tag;
        if(isset($attributes['tag']))
        {
            $tag = $attributes['tag'];
            unset($attributes['tag']);
        }

        $this->_pointer .= sprintf('<%s', $tag);
        
        if($attributes)
            $this->_addAttr($attributes);

        $this->_pointer .= sprintf('>%s</%s>', $content, $tag);

        return $this;
    }

    public function render()
    {
        if(!$this->_rendered)
        {
            if(!is_null($this->_tr))
                $this->_tr .= '</tr>';

            $this->_html .= '<thead>' . $this->_headerHtml . '</thead>';
            $this->_html .= '<tbody>' . $this->_rowHtml . '</tbody>';
            $this->_html .= '</table>';

            $this->_rendered = true;
        }

        return $this->_html;
    }

    private function _addAttr($attributes)
    {
        foreach($attributes as $k => $v)
            $this->_pointer .= sprintf(' %s="%s"', $k, $v);
    }

    private function _tr($attributes = array())
    {
        if(!is_null($this->_tr)) // Add closing tag to last pointed html
            $this->_tr .= '</tr>';

        $this->_pointer .= '<tr';

        if($attributes)
            $this->_addAttr($attributes);

        $this->_pointer .= '>';
        $this->_tr =& $this->_pointer;
    }

    /**
     * --------------------------------------------------------------------
     * THE BELOW METHODS ARE DEPRECATED AND SHOULD NOT BE USED.
     * --------------------------------------------------------------------
     */

    /**
     * Builds a table based on given headers, rows and table attributes.
     *
     * @param array $headers An array of headers and optional attributes.
     * @param array $rows An array of rows and optional attributes.
     * @param array $attributes A key, value array of attributes to add to <table> tag.
     */
    public static function build($headers, $rows, $attributes = null)
    {
        $html = '<table';

        if(!is_null($attributes))
            foreach($attributes as $k => $v)
                $html .= sprintf(' %s="%s"', $k, $v);

        $html .= '>';

        // Create headers
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= self::_row($headers, 'th');
        $html .= '</tr>';
        $html .= '</thead>';

        // Create rows
        $count = 1;
        $html .= '<tbody>';
        foreach($rows as $row)
        {
            $class = ($count % 2) ? 'odd' : 'even';

            $html .= '<tr class="' . $class . '">';
            $html .= self::_row($row, 'td', $class);
            $html .= '</tr>';

            $count++;
        }
        $html .= '</tbody>';

        // TODO Add <tfooter>

        $html .= '</table>';
        return $html;
    }

    /**
     * TODO
     */
    private static function _row($row, $tag)
    {
        $html = '';

        foreach($row as $col)
        {
            $html .= '<' . $tag;

            if(is_array($col) && isset($col['attributes']))
            {
                foreach($col['attributes'] as $k => $v)
                    $html .= sprintf(' %s="%s"', $k, $v);
            }

            $html .= '>';
            $html .= (is_array($col)) ? $col[0] : $col;
            $html .= '</' . $tag . '>';
        }

        return $html;
    }

}
