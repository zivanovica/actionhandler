<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/27/17
 * Time: 3:54 PM
 */

namespace Api\Handlers\Idea;


use Api\Models\Idea;
use Core\CoreUtils\DataTransformer\Transformers\ModelTransformer;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class GetHandler implements IApplicationRequestHandler, IApplicationRequestValidator
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
        $idea = $request->get('id', null, ModelTransformer::getNewInstance(Idea::class));

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
}