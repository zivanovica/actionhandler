<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:21 PM
 */

namespace Api\Handlers\Idea;


use Core\CoreUtils\Singleton;
use Core\CoreUtils\ValueTransformer\Transformers\IntTransformer;
use Core\CoreUtils\ValueTransformer\Transformers\StringTransformer;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Database;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class CreateActionHandler implements IApplicationActionHandler
{

    use Singleton;

    private const MINIMUM_IDEA_LENGTH = 150;

    public function methods(): array
    {
        return [
            IApplicationHandlerMethod::POST,
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
        // TODO: Implement after() method.
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


//        $db->connection->prepare('INSERT INTO `ideas` (`user_id`, `idea`, `type`)');

    }
}