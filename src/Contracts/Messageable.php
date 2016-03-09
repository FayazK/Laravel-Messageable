<?php

/*
 * This file is part of Laravel Messageable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Messageable\Contracts;

/**
 * Interface Messageable.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
interface Messageable
{
    /**
     * @return mixed
     */
    public function messages();

    /**
     * @return mixed
     */
    public function threads();

    /**
     * @return mixed
     */
    public function newMessagesCount();

    /**
     * @return mixed
     */
    public function threadsWithNewMessages();
}
