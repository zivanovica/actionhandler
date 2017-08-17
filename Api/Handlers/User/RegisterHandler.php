<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 12:56 PM
 */

namespace Api\Handlers\User;

use Api\Models\User;
use RequestHandler\Utils\InputValidator\InputValidator;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestHandler;
use RequestHandler\Modules\Application\ApplicationRequest\IApplicationRequestValidator;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Response\IResponseStatus;
use RequestHandler\Modules\Response\Response;

class RegisterHandler implements IApplicationRequestHandler, IApplicationRequestValidator
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
        $user = User::getNewInstance()->createProfile(
            $request->data('email'), $request->data('password'),
            $request->data('first_name'), $request->data('last_name')
        );

        if (false === $user instanceof User) {

            return $response
                ->status(IResponseStatus::INTERNAL_ERROR)->addError('user.create', 'Failed to create user.');
        }

        return $response->status(IResponseStatus::CREATED)->addData('message', 'User successfully created');
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'first_name' => 'required|min:2|max:64',
            'last_name' => 'required|min:2|max:64'
        ]);
    }
}