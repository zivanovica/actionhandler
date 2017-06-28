<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/23/17
 * Time: 4:03 PM
 */

namespace Api\Handlers\Idea;

use Api\Middlewares\AuthenticateMiddleware;
use Api\Models\Idea;
use Core\CoreUtils\DataFilter\Filters\IntFilter;
use Core\CoreUtils\DataFilter\Filters\ModelFilter;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestMiddleware;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Middleware\IMiddleware;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class UpdateHandler implements IApplicationRequestHandler, IApplicationRequestMiddleware, IApplicationRequestValidator
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handle(Request $request, Response $response): Response
    {

        /** @var Idea $idea */
        $idea = Middleware::getSharedInstance()->get('idea');

        $data = $this->_getUpdateData($idea, $request);

        if (empty($data)) {

            return $response->status(IResponseStatus::OK)->addData('message', 'Nothing to update');
        }

        $idea->setAttributes($data);

        if ($idea->getAttribute('id', IntFilter::getSharedInstance()) === $idea->save()) {

            return $response->data(['message', 'Idea successfully updated', 'idea' => $idea->toArray()]);
        }

        return $response->status(IResponseStatus::INTERNAL_ERROR)->addError('update', 'Failed to update idea');
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     * @param Middleware $middleware
     * @return Middleware
     */
    public function middleware(Middleware $middleware): Middleware
    {

        return $middleware
            ->add(new AuthenticateMiddleware())
            ->add(new class implements IMiddleware
            {

                public function run(Request $request, Response $response, Middleware $middleware): void
                {

                    /** @var Idea $idea */
                    $idea = $request->parameter('id', null, ModelFilter::getNewInstance(Idea::class));

                    $creatorId = $idea->user()->getAttribute('id', IntFilter::getSharedInstance());

                    $tokenUserId = $request->token()->user()->getAttribute('id', IntFilter::getSharedInstance());

                    if ($creatorId === $tokenUserId) {

                        $middleware->put('idea', $idea);

                        $middleware->next();

                        return;
                    }

                    $response
                        ->status(IResponseStatus::FORBIDDEN)
                        ->addError('owner', 'You are not owner of this idea.');

                    return;
                }
            });
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     *
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate([
            'id' => 'required|exists:ideas,id',
            'description' => 'min:' . Idea::MIN_DESCRIPTION_LENGTH . '|max:' . Idea::MAX_DESCRIPTION_LENGTH,
            'idea_category' => 'exists:idea_categories,id'
        ]);
    }

    private function _getUpdateData(Idea $idea, Request $request): array
    {

        $data = [];

        $ideaCategoryId = $idea->getAttribute('idea_category', IntFilter::getSharedInstance());

        $data['idea_category'] = $request->data('idea_category', $ideaCategoryId);

        if ($data['idea_category'] === $ideaCategoryId) {

            unset($data['idea_category']);
        }

        $data['description'] = $request->data('description', $idea->getAttribute('description'));

        if ($idea->getAttribute('description') === $data['description']) {

            unset($data['description']);
        }

        return $data;
    }
}