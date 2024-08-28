<?php

namespace Proxy\SupportChat;

use Laravel\Nova\ResourceTool;

class SupportChat extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return __('Support Chat');
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'support-chat';
    }
}
