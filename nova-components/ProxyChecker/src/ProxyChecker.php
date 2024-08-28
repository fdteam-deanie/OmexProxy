<?php

namespace Proxy\ProxyChecker;

use Laravel\Nova\ResourceTool;

class ProxyChecker extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Proxy Checker';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'proxy-checker';
    }
}
