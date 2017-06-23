<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 12:41 PM
 */

namespace Api\Handlers\Idea;


use Api\Models\Idea;
use Api\Models\IdeaCategory;
use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\DataTransformer\Transformers\ModelTransformer;
use Core\CoreUtils\DataTransformer\Transformers\StringTransformer;
use Core\CoreUtils\DataTransformer\Transformers\WaterfallTransformer;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class CreateHandler implements IApplicationActionHandler, IApplicationActionValidator
{

    const MIN_IDEA_LENGTH = 20;

    const MAX_IDEA_LENGTH = 400;

    /** @var IdeaCategory */
    private $_ideaCategory;

    private $_ideaDescription;

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
            'idea_category' => $this->_ideaCategory,
            'description' => $this->_ideaDescription,
            'status' => Idea::STATUS_OPEN
        ]);

        if ($idea->save()) {

            $response->data([
                'message' => 'Idea successfully created.',
                'idea' => $idea->toArray()
            ]);

            return;
        }

        $response->status(500)->addError('message', 'Failed to create idea, please try again.');
    }

    /**
     *
     * Validates should current action be handled or not.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @return int
     */
    public function validate(): int
    {

        $request = Request::getSharedInstance();
        $response = Response::getSharedInstance();

        $this->_ideaCategory = $request->data('category', null, new WaterfallTransformer([
            IntTransformer::getSharedInstance(),
            ModelTransformer::getNewInstance(IdeaCategory::class)
        ]));

        if (false === $this->_ideaCategory instanceof IdeaCategory) {

            $response->addError('category', 'Idea category not found.');
        }

        $this->_ideaDescription = $request->data('idea', null, StringTransformer::getSharedInstance());

        $length = strlen($this->_ideaDescription);

        if (CreateHandler::MIN_IDEA_LENGTH > $length || CreateHandler::MAX_IDEA_LENGTH < $length) {

            $response->addError(
                'idea',
                'Idea length must be between ' . self::MIN_IDEA_LENGTH . ' and ' . self::MAX_IDEA_LENGTH . ' chars long.'
            );
        }

        return $response->hasErrors() ? IResponseStatus::BAD_REQUEST : IResponseStatus::OK;
    }
}