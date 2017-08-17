<?php

namespace RequestHandler\Modules\Application;

interface IApplicationAware
{

    /**
     * IApplicationRequestHandler constructor.
     * @param IApplication $application
     */
    public function onApplication(IApplication $application);
}