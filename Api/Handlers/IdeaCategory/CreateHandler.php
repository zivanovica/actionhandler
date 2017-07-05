<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/29/17
 * Time: 5:35 PM
 */

namespace Api\Handlers\IdeaCategory;


use Api\Models\IdeaCategory;
use Api\Models\UserRole;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Request\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class CreateHandler implements IApplicationRequestHandler, IApplicationRequestValidator
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

        $ideaCategory = IdeaCategory::getNewInstance([
            'name' => $request->get('name'),
            'active' => false,
            'updated_at' => time()
        ]);

        if ($ideaCategory->save()) {

            return $response->addData('message', "Idea Category {$request->get('name')} created.");
        }

        return $response
            ->status(IResponseStatus::INTERNAL_ERROR)->addError('message', 'Failed to create idea category');
    }

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        return $validator->validate([
            'name' => 'required|unique:idea_categories,name'
        ]);
    }
}