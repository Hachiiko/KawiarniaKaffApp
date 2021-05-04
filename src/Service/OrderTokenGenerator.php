<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\OrderRepository;

class OrderTokenGenerator
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Generates new order token of given length (guarants uniqueness).
     *
     * @param  int $length
     *
     * @return string
     */
    public function generate(int $length = 4): string
    {
        $token = strtoupper(
            substr(md5((string) rand()), 0, $length)
        );

        // Check if order with given token already exists.
        if (null === $this->orderRepository->findOneByToken($token)) {
            return $token;
        }

        return $this->generate($length);
    }
}