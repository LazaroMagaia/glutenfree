<?php

namespace App\Services;

use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    private const CACHE_KEY = 'settings.all';

    public function __construct(
        private SettingRepositoryInterface $settings
    ) {}

    /**
     * Todas as configurações já convertidas para o tipo certo
     * (bool, int, string ou null), com os valores por defeito aplicados.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $stored = Cache::rememberForever(
            self::CACHE_KEY,
            fn () => $this->settings->allKeyed()
        );

        $result = [];

        foreach (config('settings.fields') as $key => $field) {
            $raw = array_key_exists($key, $stored) ? $stored[$key] : ($field['default'] ?? null);

            $result[$key] = $this->cast($raw, $field['type']);
        }

        return $result;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    /**
     * Estrutura pronta para o formulário: grupos com os seus campos e valores.
     *
     * @return array<string, array{label: string, description: string, icon: string, fields: array<string, array>}>
     */
    public function sections(): array
    {
        $values = $this->all();
        $sections = [];

        foreach (config('settings.groups') as $groupKey => $group) {
            $sections[$groupKey] = $group + ['fields' => []];
        }

        foreach (config('settings.fields') as $key => $field) {
            $sections[$field['group']]['fields'][$key] = $field + ['value' => $values[$key]];
        }

        return $sections;
    }

    /**
     * Guarda só chaves que existem em config/settings.php (lista branca).
     *
     * @param array<string, mixed> $data
     */
    public function update(array $data): void
    {
        $rows = [];

        foreach (config('settings.fields') as $key => $field) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $rows[$key] = [
                'group' => $field['group'],
                'value' => $this->serialize($data[$key], $field['type']),
            ];
        }

        if ($rows === []) {
            return;
        }

        $this->settings->setMany($rows);

        Cache::forget(self::CACHE_KEY);
    }

    private function cast(mixed $raw, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($raw, FILTER_VALIDATE_BOOLEAN),
            'number'  => ($raw === null || $raw === '') ? null : (int) $raw,
            default   => ($raw === null || $raw === '') ? null : (string) $raw,
        };
    }

    private function serialize(mixed $value, string $type): ?string
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0',
            default   => ($value === null || $value === '') ? null : trim((string) $value),
        };
    }
}
