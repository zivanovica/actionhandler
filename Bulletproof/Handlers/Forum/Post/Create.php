<?php

namespace Bulletproof\Handlers\Forum\Post;

use RequestHandler\Modules\Application\{
    IApplication,
    IApplicationAware,
    ApplicationRequest\IHandle,
    ApplicationRequest\IMiddleware,
    ApplicationRequest\IValidate
};


use RequestHandler\Modules\Middleware\{
    IMiddlewareContainer, IMiddlewareHandler
};

use RequestHandler\Modules\Response\{
    IResponse, IResponseStatus
};

use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\InputValidator;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;


class Create implements IApplicationAware, IHandle, IValidate, IMiddleware
{

    const DEFAULT_TITLE_MIN_LENGTH = 5;
    const DEFAULT_TITLE_MAX_LENGTH = 64;
    const DEFAULT_CONTENT_MIN_LENGTH = 20;
    const DEFAULT_CONTENT_MAX_LENGTH = 2048;

    private $_titleMin;
    private $_titleMax;

    private $_contentMin;
    private $_contentMax;

    public function onApplication(IApplication $application)
    {

        $this->_titleMin = $application->getAttribute('min_title_length', Create::DEFAULT_TITLE_MIN_LENGTH);
        $this->_titleMax = $application->getAttribute('max_title_length', Create::DEFAULT_TITLE_MAX_LENGTH);

        $this->_contentMin = $application->getAttribute('min_content_length', Create::DEFAULT_CONTENT_MIN_LENGTH);
        $this->_contentMax = $application->getAttribute('max_content_length', Create::DEFAULT_CONTENT_MAX_LENGTH);
    }

    /**
     *
     * Executes when related action is requested
     *
     * @param IRequest $request
     * @param IResponse $response
     * @return IResponse
     */
    public function handle(IRequest $request, IResponse $response): IResponse
    {

        return $response->data(['message' => 'all good :)'])->status(IResponseStatus::OK);
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     * @param IInputValidator $validator
     * @return IInputValidator
     */
    public function validate(IInputValidator $validator): IInputValidator
    {

        return $validator->validate([
            'title' => "required|min:{$this->_titleMin}|max:{$this->_titleMax}",
            'content' => "required|min:{$this->_contentMin}|max:{$this->_contentMax}",
            'category' => 'required'
        ]);
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param IMiddlewareContainer $middleware
     * @return IMiddlewareContainer
     */
    public function middleware(IMiddlewareContainer $middleware): IMiddlewareContainer
    {

        return $middleware->add(new class implements IMiddlewareHandler
        {
            /**
             *
             *
             * THIS SHOULD BE MIDDLEWARE USED TO LOAD REQUIRED SETTINGS FOR CURRENT REQUEST
             *
             * Execute method for current middleware
             *
             * @param IRequest $request
             * @param IResponse $response
             * @param IMiddlewareContainer $middleware Used to call "next" method
             */
            public function handle(IRequest $request, IResponse $response, IMiddlewareContainer $middleware): void
            {

                SingletonFactory::getSharedInstance(IApplication::class)->setAttribute('max_content_length', 'coa');

                $middleware->next();
            }
        });
    }
}