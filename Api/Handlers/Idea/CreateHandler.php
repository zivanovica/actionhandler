<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 12:41 PM
 */

namespace Api\Handlers\Idea;


use Api\Middlewares\AuthenticateMiddleware;
use Api\Models\Idea;
use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class CreateHandler implements IApplicationActionHandler, IApplicationActionValidator, IApplicationActionMiddleware
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {

        $idea = Idea::getNewInstance([
            'idea_category' => $request->data('idea_category', null, IntTransformer::getSharedInstance()),
            'creator_id' => $request->token()->user(),
            'description' => $request->data('description'),
            'status' => Idea::STATUS_OPEN
        ]);

        if ($idea->save()) {

            $response
                ->status(IResponseStatus::CREATED)
                ->data([
                    'message' => 'Idea successfully created.',
                    'idea' => $idea->toArray()
                ]);

            return;
        }

        $response->status(500)->addError('message', 'Failed to create idea, please try again.');
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param Middleware $middleware
     * @return Middleware
     */
    public function middleware(Middleware $middleware): Middleware
    {

        return $middleware->add(new AuthenticateMiddleware());
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function validate(Request $request, Response $response): bool
    {
        $idea = Idea::getSharedInstance()->findOneWhere([
            'creator_id' => $request->token()->user()->getAttribute('id'),
            'status' => Idea::STATUS_OPEN
        ]);

        if (null !== $idea) {

            $response->status(IResponseStatus::CONFLICT)->addError('idea.exists', 'Open idea already pending');

            return false;
        }

        $validator = InputValidator::getSharedInstance();

        $validator->validate([
            'description' => 'required|min:' . Idea::MIN_DESCRIPTION_LENGTH . '|max:' . Idea::MAX_DESCRIPTION_LENGTH,
            'idea_category' => 'required|exists:idea_categories,id'
        ], $request->allData());

        if ($validator->hasErrors()) {

            $response->status(IResponseStatus::BAD_REQUEST)->errors($validator->getErrors());

            return false;
        }

        return true;
    }
}