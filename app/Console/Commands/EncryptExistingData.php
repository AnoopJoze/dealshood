<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;

/**
 * One-time command — run ONCE after deploying the new models
 * and BEFORE running the migration, so existing plain-text values
 * are encrypted before the column types change to TEXT.
 *
 * Usage:
 *   php artisan app:encrypt-existing-data
 *
 * Order:
 *   1. php artisan app:encrypt-existing-data
 *   2. php artisan migrate
 */
class EncryptExistingData extends Command
{
    protected $signature   = 'app:encrypt-existing-data';
    protected $description = 'Encrypt existing plain-text sensitive fields in users and posts tables';

    /* Fields that will be encrypted */
    private array $userFields = [
        'phone', 'whatsapp_number', 'address',
        'latitude', 'longitude',
        'location', 'company_name', 'about_me', 'website',
    ];

    private array $postFields = [
        'latitude', 'longitude', 'google_map_url',
        'country', 'state', 'city', 'location',
        'description', 'meta_title', 'meta_description', 'keywords',
    ];

    public function handle(): int
    {
        $this->encryptTable('users', User::class,  $this->userFields);
        $this->encryptTable('posts', Post::class,  $this->postFields);
        $this->info('Done.');
        return Command::SUCCESS;
    }

    private function encryptTable(string $tableName, string $model, array $fields): void
    {
        $this->info("Encrypting {$tableName}…");
        $bar = null;

        $model::withTrashed()->chunk(200, function ($records) use ($fields, $tableName, &$bar) {
            if (!$bar) {
                $total = $records->first()?->getModel()::withTrashed()->count() ?? 0;
                $bar   = $this->output->createProgressBar($total);
                $bar->start();
            }

            foreach ($records as $record) {
                $updates = [];
                foreach ($fields as $field) {
                    $raw = $record->getRawOriginal($field); // plain text from DB
                    if ($raw === null || $raw === '') continue;

                    // Skip already-encrypted values (Crypt payloads are valid base64 JSON)
                    if ($this->isAlreadyEncrypted($raw)) continue;

                    try {
                        $updates[$field] = Crypt::encryptString($raw);
                    } catch (\Throwable $e) {
                        $this->warn("  Could not encrypt {$tableName}#{$record->id}.{$field}: {$e->getMessage()}");
                    }
                }

                if ($updates) {
                    // Use query builder to bypass model casting (avoids double-encrypt)
                    \DB::table($tableName)
                        ->where('id', $record->id)
                        ->update($updates);
                }

                $bar?->advance();
            }
        });

        $bar?->finish();
        $this->newLine();
    }

    /**
     * Crypt::encryptString() produces a base64-encoded JSON object.
     * If the stored value is already valid base64 JSON with an 'iv' key,
     * it has already been encrypted — skip it.
     */
    private function isAlreadyEncrypted(string $value): bool
    {
        try {
            $decoded = json_decode(base64_decode($value), true);
            return isset($decoded['iv'], $decoded['value'], $decoded['mac']);
        } catch (\Throwable) {
            return false;
        }
    }
}