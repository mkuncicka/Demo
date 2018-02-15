<?php

namespace Demo\Repository;

use Demo\Database\DatabaseManager;
use Demo\Model\Language;

/**
 * Languages repository
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
class LanguagesRepository implements Languages
{

    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Adds language to repository
     *
     * @param Language $language
     * @return void
     */
    public function add(Language $language)
    {
        $this->databaseManager->persist($language);
    }

    /**
     * Removes language from repository
     *
     * @param Language $language
     * @return void
     */
    public function remove(Language $language)
    {
        $this->databaseManager->remove($language);
    }

    /**
     * @inheritdoc
     */
    public function getByName(string $name)
    {
        $result = $this->databaseManager->getAll(Language::class, ['name' => $name], false);

        if (count($result) > 1) {
            throw new \PDOException("More then one record identified by given name");
        }

        return count($result) === 1 ? $result[0] : null;
    }
}