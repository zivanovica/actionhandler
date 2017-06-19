<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:21 PM
 */

namespace Api\Handlers\Idea;


use Api\Middlewares\AuthenticateMiddleware;
use Api\Middlewares\AuthorizeMiddleware;
use Core\CoreUtils\DataTransformer\Transformers\BoolTransformer;
use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\DataTransformer\Transformers\StringTransformer;
use Core\CoreUtils\DataTransformer\Transformers\WaterfallTransformer;
use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Database;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class CreateActionHandler implements IApplicationActionHandler, IApplicationActionMiddleware, IApplicationActionValidator
{

    use Singleton;

    private const MINIMUM_IDEA_LENGTH = 150;

    public function methods(): array
    {
        return [
            IApplicationHandlerMethod::GET,
            IApplicationHandlerMethod::DELETE
        ];
    }

    /**
     *
     * Validate input before handling it
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function before(Request $request, Response $response): bool
    {

        var_dump('handling before');

        /** @var \PDO $db */
        $db = Database::getSharedInstance()->connection;

        $category = $request->query('category', null, IntTransformer::getSharedInstance());

        if (0 === strlen($category)) {

            $response->setError('category', 'field category is required');
        }

        $statement = $db->prepare('SELECT `id` FROM `ideas` WHERE `id` = ?;');

        if (false === $response->hasErrors() && false === $statement) {

            $response->setError('error', 'Database error occurred')->status(500);
        }

        if (false === $response->hasErrors() && false === $statement->execute([$category])) {

            $response->setError('error', 'Database error occurred')->status(500);
        }

        if (false === $response->hasErrors() && empty($statement->fetch(\PDO::FETCH_ASSOC)['id'])) {

            $response->setError('category', 'Invalid category')->status(400);
        }

        $idea = $request->data('idea', null, StringTransformer::getSharedInstance());

        if (CreateActionHandler::MINIMUM_IDEA_LENGTH > strlen($idea)) {

            $response->setError('idea', 'field idea must have length of minimum ' . CreateActionHandler::MINIMUM_IDEA_LENGTH);
        }

        return false === $response->hasErrors();
    }

    /**
     *
     * Executes after "handle" method, doesn't affect on "handle" execution nor request
     *
     */
    public function after(): void
    {
        var_dump('handling after');
    }

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {

        /** @var Database $db */
        $db = Database::getSharedInstance();

        $val = $request->query(
            'category',
            null,
            new WaterfallTransformer([
                StringTransformer::getSharedInstance(),// transforms value to string
                IntTransformer::getSharedInstance(),// transforms string transformed value to integer
                BoolTransformer::getSharedInstance(),// transforms integer value to boolean
            ])
        );


        $response->data(['name' => 'coa']);

//        $db->connection->prepare('INSERT INTO `ideas` (`user_id`, `idea`, `type`)');

    }

    public function middleware(Middleware $middleware): Middleware
    {
        return $middleware->add(new AuthenticateMiddleware())->add(new AuthorizeMiddleware());
    }

    /**
     *
     * Validates should current action be handled or not.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        return true;
    }
}