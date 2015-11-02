<?php
namespace Hooker;

/**
 *  Filter Abstract Class
 *
 *  This class must be extended by any objects that will be attached as filter actions to named filters
 */
abstract class Filter {
    
    /**
     *  @abstract
     *  @param mixed $content The content that will be modified, passed from the calling object's trigger
     *  @return mixed The modified content
     */
    abstract public function run($content);
    
}
