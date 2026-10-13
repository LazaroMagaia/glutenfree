<?php

namespace App\Repositories\Contracts;

interface SettingRepositoryInterface
{
    /**
     * Todas as configurações guardadas, no formato ['chave' => 'valor'].
     *
     * @return array<string, string|null>
     */
    public function allKeyed(): array;

    /**
     * Guarda várias configurações de uma vez (cria ou actualiza).
     *
     * @param array<string, array{group: string, value: string|null}> $rows
     */
    public function setMany(array $rows): void;
}
