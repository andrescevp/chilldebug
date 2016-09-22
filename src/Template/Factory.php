<?php
namespace ChillDebug\Template;

/**
 * Class Factory
 * @package ChillDebug\Template
 */
class Factory
{
    /**
     * @param $template
     *
     * @return Abstracted
     */
    public function getTemplate($template)
    {
        $class = __NAMESPACE__ . '\\' . ucfirst($template) . 'Template';

        if (!class_exists($class)) {
            throw new \LogicException($class . ' do not exist');
        }

        return new $class();
    }
}