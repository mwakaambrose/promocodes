<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface Repositories
{
    public function all(): Collection;
    public function find(int $id): ?Model;
    public function create(array $data): Model|bool;
    public function update(int $id, array $data): bool;
}
