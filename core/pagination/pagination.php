<?php

class Pagination extends Module {

    /**
     * Builds an HTML list of page links based on the given params.
     *
     * @param int $current The current page being displayed, if < 0 defaults to 1
     * @param int $perPage The number of items to display per page
     * @param int $total The total number of possible items
     * @param string $url The url string used to create pagination links
     *
     * The $url string should contain a :num tag which will be replaced with the
     * page number for that link.
     *
     * Example for $url:
     *      'blog/posts/:num'
     *
     * TODO Add configs for spread
     */
    public static function build($current, $perPage, $total, $url)
    {
        if($perPage > $total)
            return null; // No point in paginating if we dont need it

        $total = ceil($total / $perPage);

        $spread = 5; // The number of links in center
        $spreadOffset = floor($spread / 2);

        $current = ($current < 1) ? 1 : $current; // Force anything less than 1 to equal 1
        $start = $current - $spreadOffset;
        $end = $current + $spreadOffset;

        if($start < 1)
        {
            $start = 1;
            $end = $spread;
        }

        if($end > $total)
        {
            $start = $total - ($spread - 1);
            $end = $total;
        }

        $html = '<ul class="nav pagination">';

        if($start > 1)
            $html .= '<li>' . Html::a()->get('Prev', self::_url(($current - 1), $url)) . '</li>';

        if($start > 2)
        {
            $html .= '<li>' . Html::a()->get(1, self::_url(1, $url)) . '</li>';
            $html .= '<li><a href="#">...</a<></li>';
        }

        for($i = $start; $i <= $end; $i++)
            $html .= '<li>' . Html::a()->get($i, self::_url($i, $url)) . '</li>';

        if($end < ($total - 1))
        {
            $html .= '<li><a href="#">...</a<></li>';
            $html .= '<li>' . Html::a()->get($total, self::_url($total, $url)) . '</li>';
        }

        if($end < $total)
            $html .= '<li>' . Html::a()->get('Next', self::_url(($current + 1), $url)) . '</li>';

        $html .= '</ul>';

        return $html;
    }

    /**
     * Returns the given url with the tag :num replaced with the given page number.
     */
    private static function _url($page, $url) {
        return str_replace(':num', $page, $url);
    }

}
