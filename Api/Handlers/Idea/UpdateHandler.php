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
use Api\Models\IdeaCategory;
use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\DataTransformer\Transformers\ModelTransformer;
use Core\CoreUtils\DataTransformer\Transformers\WaterfallTransformer;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class UpdateHandler implements IApplicationActionHandler, IApplicationActionMiddleware, IApplicationActionValidator
{

    /** @var Idea */
    private $_idea;

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {

        $data = [];

        $category = $request->data('category', null, IntTransformer::getSharedInstance());

        if ($this->_idea->getAttribute('idea_category', IntTransformer::getSharedInstance()) !== $category) {

            $data['idea_category'] = $category;
        }

        $description = $request->data('description');

        if ($this->_idea->getAttribute('description') !== $description) {

            $data['description'] = $description;
        }

        if (empty($data)) {

            $response->status(IResponseStatus::OK)->addData('message', 'Nothing to update');

            return;
        }

        $this->_idea->setAttributes($data);

        if ($this->_idea->save()) {

            $response->data(['message', 'Idea successfully updated', 'idea' => $this->_idea->toArray()]);

            return;
        }

        $response->status(IResponseStatus::INTERNAL_ERROR)->addError('update', 'Failed to update idea');
    }

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
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

        /** @var Idea $idea */
        $this->_idea = $request->parameter('id', null, ModelTransformer::getNewInstance(Idea::class));

        if (null === $this->_idea) {

            $response->status(IResponseStatus::NOT_FOUND)->addError('idea', 'Idea not found');

            return false;
        }

        $rules = [
            'description' => 'min:' . Idea::MIN_DESCRIPTION_LENGTH . '|max:' . Idea::MAX_DESCRIPTION_LENGTH,
            'category' => 'exists:idea_categories,id', 'creator' => 'same:user_id', 'status' => 'equal:' . Idea::STATUS_OPEN
        ];

        $inputs = [
            'description' => $request->data('description'), 'status' => $this->_idea->getAttribute('status'),
            'category' => $request->data('category', null, IntTransformer::getSharedInstance()),
            'user_id' => $request->token()->user()->getAttribute('id', IntTransformer::getSharedInstance()),
            'creator' => $this->_idea->user()->getAttribute('id', IntTransformer::getSharedInstance()),
        ];

        if (false === InputValidator::getSharedInstance()->validate($rules, $inputs)) {

            $response->status(IResponseStatus::BAD_REQUEST)->errors(InputValidator::getSharedInstance()->getErrors());

            return false;
        }

        return true;
    }
}