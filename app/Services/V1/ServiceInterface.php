<?php

namespace App\Services\V1;

use App\Common\SharedMessage;

interface ServiceInterface
{
    public function index(): SharedMessage;
    public function store($data): SharedMessage;
    public function show($data): SharedMessage;
    public function update(array $data, $model): SharedMessage;
    public function destroy($model): SharedMessage;
}
