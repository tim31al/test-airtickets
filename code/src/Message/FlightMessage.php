<?php


namespace App\Message;


class FlightMessage
{
    private int $id;
    private array $context;

    /**
     * FlightMessage constructor.
     * @param int $id
     * @param array $context
     */
    public function __construct(int $id, array $context)
    {
        $this->id = $id;
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
