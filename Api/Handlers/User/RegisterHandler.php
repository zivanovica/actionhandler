<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 12:56 PM
 */

namespace Api\Handlers\User;


use Api\Models\User;
use Core\CoreUtils\DataTransformer\Transformers\EmailTransformer;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class RegisterHandler implements IApplicationActionHandler, IApplicationActionValidator
{

    private $_email;

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {
        // TODO: Implement handle() method.
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @return int If everything is good this MUST return 200 or IResponseStatus::OK
     */
    public function validate(): int
    {

        $request = Request::getSharedInstance();
        $response = Response::getSharedInstance();

        $validator = InputValidator::getSharedInstance();

        $validator->validate([
            'email' => 'required|email|unique:users,email'
        ], $request->allData());

        var_dump($validator->getErrors());

        return IResponseStatus::OK;

    }
}