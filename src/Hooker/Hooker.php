<?php
namespace Hooker;

/**
 *  Hooker Trait
 *
 *  This trait should be used by all classes that need to support hooks.
 */
trait Hooker {
    
    /**
     *  @var array Array of this object's named hooks
     */
    protected $hooks    = array();
    
    /**
     *  @var array Array of this object's named filters
     */
    protected $filters  = array();
    
    
    /**
     *  Runs all of the actions attached to the specified hook
     *
     *  @param string $name Name of the hook to run actions for
     *  @param array $params Array containing parameters to pass to actions
     */
    protected function runHooks($name, $params = array()) {
        if(array_key_exists($name, $this->hooks) && is_array($this->hooks[$name])) {
            foreach($this->hooks[$name] as $hook) {
                if(is_callable($hook)) {
                    call_user_func($hook, $params);
                }
                elseif(is_object($hook)) {
                    $hook->run($params);
                }
            }
            return true;
        }
        return false;
    }
    
    /**
     *  Processes specified content through all of the attached filters and returns it
     *
     *  @param string $name Name of the filter in question
     *  @content mixed $content Content that will be passed through the filter(s)
     */
    protected function runFilters($name, $content) {
        if(array_key_exists($name, $this->filters) && is_array($this->filters[$name])) {
            foreach($this->filters[$name] as $filter) {
                if(is_callable($filter)) {
                    $content = call_user_func($filter, $content);
                }
                elseif(is_object($filter)) {
                    $content = $filter->run($content);
                }
            }
        }
        return $content;
    }
    
    /**
     *  Adds a named hook to the current object
     *
     *  @param string $name Name of the hook being added
     */
    protected function addNamedHook($name) {
        if(!array_key_exists($name, $this->hooks) || !is_array($this->hooks[$name])) {
            $this->hooks[$name] = array();
        }
        return $this;
    }
    
    /**
     *  Adds a named filter to the current object
     *
     *  @param string $name Name of the filter being added
     */
    protected function addNamedFilter($name) {
        if(!array_key_exists($name, $this->filters) || !is_array($this->filters[$name])) {
            $this->filters[$name] = array();
        }
        return $this;
    }
    
    /**
     *  Attaches an action to a named hook
     *
     *  @param string $name Name of the hook this action will be added to.  This will be created via 'addNamedHook' if it doesn't exist.
     *  @param mixed $action An object or callable to be executed when triggered by this hook.  If object, must extend the Hooker\Hook abstract class
     */
    public function attachToNamedHook($hook, $action) {
        if(array_key_exists($hook, $this->hooks) && is_array($this->hooks[$hook])) {
            if(is_object($action) && ($action instanceof \Hooker\Hook) && method_exists($action, 'run')) {
                $this->hooks[$hook][] = $action;
            }
            elseif(is_callable($action)) {
                $this->hooks[$hook][] = $action;
            }
        }
        else {
            $this->addNamedHook($hook)->attachToNamedHook($hook, $action);
        }
        return $this;
    }
    
    /**
     *  Attaches a filter action to a named filter
     *
     *  @param string $name Name of the filter this action will be added to.  This will be created via 'addNamedFilter' if it doesn't exist.
     *  @param mixed $action An object or callable to be executed when triggered by this filter.  If object, must extend the Hooker\Filter abstract class
     */
    public function attachToNamedFilter($filter, $action) {
        if(array_key_exists($filter, $this->filters) && is_array($this->filters[$filter])) {
            if(is_object($action) && ($action instanceof \Hooker\Filter) && method_exists($action, 'run')) {
                $this->filters[$filter][] = $action;
            }
            elseif(is_callable($action)) {
                $this->filters[$filter][] = $action;
            }
        }
        else {
            $this->addNamedFilter($filter)->attachToNamedFilter($filter, $action);
        }
        return $this;
    }
    
}
