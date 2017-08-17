<?php

namespace Api\Models;

use RequestHandler\Modules\Database;
use RequestHandler\Modules\Model\Model;
use RequestHandler\Utils\DataFilter\Filters\IntFilter;

abstract class Unique extends Model
{

    /** @var Model[] */
    private static $_models = [];

    /** @var int */
    private $_uniqueId;

    /**
     *
     * Retrieve entity model that matches given unique id
     *
     * @param int $uniqueId
     * @return Model|null
     */
    public static function getByUniqueId(int $uniqueId): ?Model
    {

        if (isset(Unique::$_models[$uniqueId])) {

            return Unique::$_models[$uniqueId];
        }

        $uniqueEntity = Database::getSharedInstance()->fetch('SELECT * FROM `unique` WHERE `id` = ?;', [$uniqueId]);

        if (empty($uniqueEntity)) {

            return null;
        }

        if (false === class_exists($uniqueEntity['entity_model'])) {

            throw new \RuntimeException("Invalid entity model {$uniqueEntity['entity_model']}: Class not found.");
        }

        /** @var Model $model */
        $model = new $uniqueEntity['entity_model']();

        if (false === $model instanceof Model) {

            throw new \RuntimeException("Invalid entity model {$uniqueEntity['entity_model']}: Does not inheritance Model class.");
        }

        return $model->find($uniqueEntity['entity_id']);
    }

    /**
     *
     * Execute original save, after its saved - create and store unique id entity
     *
     * @return bool
     */
    public function save(): bool
    {
        $id = parent::save();

        if (0 === $id) {

            return 0;
        }

        if ($this->getUniqueId()) {

            return $id;
        }

        $uniqueId = crc32(uniqid(json_encode($this->toArray()), true));

        $data = [$uniqueId, static::class, $id];

        $uniqueSaved = $this->_db->store('INSERT INTO `unique` (`id`, `entity_model`, `entity_id`) VALUES (?, ?, ?);', $data);

        if ($uniqueSaved) {

            Unique::$_models[$uniqueId] = $this;

            return true;
        }

        return false;
    }

    /**
     *
     * Retrieve unique id for current model
     *
     * @return int|null
     */
    public function getUniqueId(): ?int
    {

        if (null === $this->_uniqueId) {

            $this->_uniqueId = $this->_getUniqueId();
        }

        return $this->_uniqueId;
    }

    /**
     *
     * Retrieve unique id from cached content, if not found in cached then lookup in database
     *
     * @return int|null
     */
    private function _getUniqueId(): ?int
    {

        $ids = array_keys(Unique::$_models, $this);

        if (false === empty($ids)) {

            return array_pop($ids);
        }

        $bindings = [get_class($this), $this->primaryValue(IntFilter::getSharedInstance())];

        $result = $this->_db->fetch('SELECT `id` FROM `unique` WHERE `entity_model` = ? AND `entity_id` = ?;', $bindings);

        return isset($result['id']) ? $result['id'] : null;
    }
}