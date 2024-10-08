<?php

namespace App\Interface\Repository;

interface PaymentRepositoryInterface
{
    public function findMany();

    public function findOneById(int $id);

    public function create(object $payload);

    public function update(object $payload);

    public function delete(int $id);
}
