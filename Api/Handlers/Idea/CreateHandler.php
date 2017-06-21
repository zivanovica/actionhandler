<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 12:41 PM
 */

namespace Api\Handlers\Idea;


use Api\Models\IdeaCategory;
use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\DataTransformer\Transformers\ModelTransformer;
use Core\CoreUtils\DataTransformer\Transformers\WaterfallTransformer;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class CreateHandler implements IApplicationActionHandler, IApplicationActionValidator
{

    /** @var IdeaCategory */
    private $_ideaCategory;

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {
        var_dump(IdeaCategory::getSharedInstance()->all());
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

        $this->_ideaCategory = $request->query('category', null, new WaterfallTransformer([
            IntTransformer::getSharedInstance(),
            ModelTransformer::getNewInstance(IdeaCategory::class)
        ]));

        if (false === $this->_ideaCategory instanceof IdeaCategory) {

            $response->setError('category', 'Idea category not found.');

            return IResponseStatus::OK;
        }

        return IResponseStatus::OK;
    }
}