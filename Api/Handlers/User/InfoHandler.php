<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/28/17
 * Time: 3:12 PM
 */

namespace Api\Handlers\User;


use Api\Models\User;
use Core\CoreUtils\DataFilter\Filters\ModelFilter;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationRequestFilter;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Request\IRequestFilter;
use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

class InfoHandler implements IApplicationRequestHandler, IApplicationRequestFilter, IApplicationRequestValidator
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

        return $response->addData('user', $request->get('id'));
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

        return $validator->validate(['id' => 'required|exists:users,id']);
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

        return $filter->add('id', ModelFilter::getNewInstance(User::class));
    }
}