<?php

namespace Proxy\GenerateProxy;

use Laravel\Nova\ResourceTool;

class GenerateProxy extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Generate Proxy';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'generate-proxy';
    }
}
