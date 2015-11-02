<?php
namespace Hooker;

/**
 *  Hook Abstract Class
 *
 *  This class must be extended by all objects that will be attached to named hooks
 */
abstract class Hook {
    
    /**
     *  @abstract
     *  @param mixed $params Argument(s) passed in by the calling object's trigger
     */
    abstract public function run($params = null);
    
}

