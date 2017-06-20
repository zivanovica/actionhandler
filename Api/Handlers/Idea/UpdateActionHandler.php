<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:21 PM
 */

namespace Api\Handlers\Idea;


use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class UpdateActionHandler implements IApplicationActionHandler
{

    use Singleton;

    /**
     *
     * Executes when related action is requested
     *
     */
    public function handle(Request $request, Response $response): void
    {

        var_dump($request->parameter('id', null, IntTransformer::getNewInstance()), $request->parameter('step'));

        $response->data(['action' => 'Idea Update']);
    }
}