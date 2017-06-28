<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/27/17
 * Time: 3:54 PM
 */

namespace Api\Handlers\Idea;


use Api\Models\Idea;
use Core\CoreUtils\DataFilter\Filters\IntFilter;
use Core\CoreUtils\DataFilter\Filters\ModelFilter;
use Core\CoreUtils\DataFilter\Filters\WaterfallFilter;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationRequestFilter;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Request\IRequestFilter;
use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

class GetHandler implements IApplicationRequestHandler, IApplicationRequestValidator, IApplicationRequestFilter
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
        $idea = $request->get('id');

        return $response->data([
            'idea' => $idea->toArray()
        ]);
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate([
            'id' => 'required|exists:ideas,id'
        ]);
    }

    /**
     *
     * Request filter used to transform given fields to specified types
     *
     * @param IRequestFilter $filter
     * @return IRequestFilter
     */
    public function filter(IRequestFilter $filter): IRequestFilter
    {
        return $filter->add('id', new WaterfallFilter([
            IntFilter::getSharedInstance(),
            ModelFilter::getNewInstance(Idea::class)
        ]));
    }
}