<?php

namespace CherryAudio\ApiInspector;

use Laravel\Nova\ResourceTool;

class ApiInspector extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Api Inspector';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'api-inspector';
    }
}
