<?php

class Social extends Module {

    /**
     * Helper method for getting Social_Twitter instance.
     *
     * Example:: Social::twitter()->getRecent('myaccount'); 
     */
    public static function twitter() {
        return new Social_Twitter();
    }

}
