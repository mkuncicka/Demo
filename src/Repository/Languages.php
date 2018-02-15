<?php

namespace Demo\Repository;

use Demo\Model\Language;

/**
 * Describes Languages Repository
 *
 * @author Magdalena Kuncicka <mkuncicka@gmail.com>
 */
interface Languages
{
    /**
     * Adds language to repository
     *
     * @param Language $language
     * @return void
     */
    public function add(Language $language);

    /**
     * Removes language from repository
     *
     * @param Language $language
     * @return void
     */
    public function remove(Language $language);

    /**
     * Returns language identified by given name
     *
     * @param string $name
     * @return Language
     */
    public function getByName(string $name);

}