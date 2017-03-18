<?php

class Journal2Loader {

    public static function execute($child, $args, $reg) {
        $action = new Action($child, $args);

        if (file_exists($action->getFile())) {
            require_once($action->getFile());

            $class = $action->getClass();

            $controller = new $class($reg);

            return $controller->{$action->getMethod()}($action->getArgs());
        } else {
            trigger_error('Error: Could not load controller ' . $child . '!');
            exit();
        }

    }


}