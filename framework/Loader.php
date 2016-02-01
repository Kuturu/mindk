<?php

class Loader {
    private static $instance;
    private $namespaces = array();

    private function __construct() {
        spl_autoload_register(array(__CLASS__, 'loadUserNamespaces'));
        spl_autoload_register(array(__CLASS__, 'loadFramework'));
    }
    
    private function loadFramework($class) {
        $path = str_ireplace('Framework', '', $class);
        $path = __DIR__ . str_replace('\\', DIRECTORY_SEPARATOR, $path) . '.php';
        if(file_exists($path)){
            include_once($path);
        }
    }
    
    private function loadUserNamespaces($class) {
        foreach ($this->namespaces as $name => $dir_path){
            $pos = strpos($class, $name);
            if($pos === 0){
                $class_path = str_ireplace($name, '', $class);
                $path = $dir_path . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_path) . '.php';
                if(file_exists($path)){
                    include_once($path);
                }
            }
        }
    }
    
    public static function addNamespacePath($name, $path) {
        $ldr = self::getLoader();
        $ldr->namespaces[$name] = $path;
    }
    
    public static function getLoader() {
        if(empty(self::$instance)){
            self::$instance = new Loader();
        }
        return self::$instance;
    }
    
    private function __clone() {
        
    }
}
