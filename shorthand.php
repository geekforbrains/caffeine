<?php

/**
 * This file contains "short-hand" functions for common module methods. These are 
 * primarly meant to be used within HTML views to reduce the PHP footprint.
 *
 * Short-hand functions should always be lowercase, single or abbreviated words that
 * start with an underscore.
 */


// --------------------------------------------------------
// Custom functions, add your own short-hand functions here.
// --------------------------------------------------------


// --------------------------------------------------------
// Core functions, dont change these.
// --------------------------------------------------------

/**
 * Short-hand for Url::to()
 */
function _to($path, $fullUrl = false, $includeLang = true) {
    return Url::to($path, $fullUrl, $includeLang);
}

/**
 * Short-hand for Url::current()
 */
function _current($includeBase = true) {
    return Url::current($includeBase);
}

/**
 * Short-hand for Html::a()
 */
function _a($title, $url, $attr = array()) {
    return Html::a($title, $url, $attr);
}

/**
 * Short-hand for Html::img()
 */
function _img($urlOrId, $attrOrRotation = null, $width = null, $height = null, $attr = array()) {
    return Html::img($urlOrId, $attrOrRotation, $width, $height, $attr);
}

/**
 * Short-hand for Input::post()
 */
function _p($field, $defaultValue = null, $raw = false) {
    return Input::post($field, $defaultValue, $raw);
}

/**
 * Short-hand for Validate::error()
 */
function _e($field) {
    return Validate::error($field);
}
