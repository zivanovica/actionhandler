<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 3:15 PM
 */

namespace Api\Models;


use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\Singleton;
use Core\Libs\Model\Model;

class IdeaCategory extends Model
{

    use Singleton;

    /**
     * @return string Table name
     */
    public function table(): string
    {
        return 'idea_categories';
    }

    /**
     * @return string Name of primary key field
     */
    public function primary(): string
    {
        return 'id';
    }

    /**
     * @return array Fields (columns) of table
     */
    public function fields(): array
    {
        return ['id', 'name', 'active', 'updated_at'];
    }

    public function getIdeas(): array
    {

        $id = $this->getAttribute('id', IntTransformer::getSharedInstance());

    }
}