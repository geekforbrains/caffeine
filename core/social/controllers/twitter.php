<?php

class Social_TwitterController extends Controller {

    /**
     * TODO
     */
    public static function oauth()
    {
        Social::twitter()->sendToTwitterForAuth();
    }

}
