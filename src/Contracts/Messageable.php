<?php

namespace DraperStudio\Messageable\Contracts;

interface Messageable
{
    public function messages();

    public function threads();

    public function newMessagesCount();

    public function threadsWithNewMessages();
}
