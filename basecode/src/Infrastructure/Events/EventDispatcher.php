<?php

namespace Src\Infrastructure\Events;

class EventDispatcher
{
    private array $listeners = [];

    public function listen(
        string $event,
        callable $listener
    ): void {

        $this->listeners[$event][] =
            $listener;
    }

    public function dispatch(
        object $event
    ): void {

        $eventClass =
            get_class($event);

        if (
            !isset(
                $this->listeners[$eventClass]
            )
        ) {

            return;
        }

        foreach (
            $this->listeners[$eventClass]
            as $listener
        ) {

            $listener($event);
        }
    }
}