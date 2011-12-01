<?php

class Html_Table {

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
