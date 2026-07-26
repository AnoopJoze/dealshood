<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Setting;
use App\Models\ShortLink;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialShareService
{
    private const GRAPH_BASE = 'https://graph.facebook.com/v19.0';

    private function accessToken(): ?string
    {
        $encrypted = Setting::get('fb_page_access_token');
        if (!$encrypted) {
            return null;
        }

        try {
            return decrypt($encrypted);
        } catch (\Throwable $e) {
            Log::warning('SocialShareService: could not decrypt fb_page_access_token', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function firstImageUrl(Post $post): ?string
    {
        return $post->getFirstMediaUrl('posts') ?: null;
    }

    private function shortUrl(Post $post): string
    {
        try {
            return ShortLink::getOrCreateFor($post->url)->short_url;
        } catch (\Throwable $e) {
            Log::warning('SocialShareService: could not create short link, using full URL', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return $post->url;
        }
    }

    private function caption(Post $post): string
    {
        $parts = array_filter([
            $post->title,
            $post->offer_percentage ? rtrim(rtrim(number_format($post->offer_percentage, 2), '0'), '.') . '% OFF' : null,
            strip_tags($post->description ?? ''),
        ]);

        $caption = implode("\n\n", $parts) . "\n\n" . $this->shortUrl($post);

        return Str::limit($caption, 2000);
    }

    /**
     * @return array{success: bool, message: string, id?: string|null}
     */
    public function shareToFacebook(Post $post): array
    {
        $token  = $this->accessToken();
        $pageId = Setting::get('fb_page_id');

        if (!$token || !$pageId) {
            return ['success' => false, 'message' => 'Facebook Page ID / Access Token not configured in Settings.'];
        }

        $image = $this->firstImageUrl($post);

        try {
            $response = $image
                ? Http::timeout(20)->asForm()->post(self::GRAPH_BASE . "/{$pageId}/photos", [
                    'url'          => $image,
                    'caption'      => $this->caption($post),
                    'access_token' => $token,
                ])
                : Http::timeout(20)->asForm()->post(self::GRAPH_BASE . "/{$pageId}/feed", [
                    'message'      => $this->caption($post),
                    'link'         => $this->shortUrl($post),
                    'access_token' => $token,
                ]);

            $data = $response->json() ?? [];

            if ($response->failed() || isset($data['error'])) {
                $message = $data['error']['message'] ?? 'Unknown Facebook API error.';
                Log::warning('Facebook share failed', ['post_id' => $post->id, 'error' => $data['error'] ?? $data]);
                return ['success' => false, 'message' => $message];
            }

            return [
                'success' => true,
                'message' => 'Shared to Facebook.',
                'id'      => $data['post_id'] ?? $data['id'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Facebook share exception', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Facebook request failed: ' . $e->getMessage()];
        }
    }

    /**
     * @return array{success: bool, message: string, id?: string|null}
     */
    public function shareToInstagram(Post $post): array
    {
        $token = $this->accessToken();
        $igId  = Setting::get('ig_business_account_id');

        if (!$token || !$igId) {
            return ['success' => false, 'message' => 'Instagram Business Account ID / Access Token not configured in Settings.'];
        }

        $image = $this->firstImageUrl($post);
        if (!$image) {
            return ['success' => false, 'message' => 'Instagram requires at least one image on the post.'];
        }

        try {
            // Step 1 — create the media container
            $create = Http::timeout(20)->asForm()->post(self::GRAPH_BASE . "/{$igId}/media", [
                'image_url'    => $image,
                'caption'      => $this->caption($post),
                'access_token' => $token,
            ]);
            $createData = $create->json() ?? [];

            if ($create->failed() || isset($createData['error']) || empty($createData['id'])) {
                $message = $createData['error']['message'] ?? 'Could not create Instagram media container.';
                Log::warning('Instagram media create failed', ['post_id' => $post->id, 'error' => $createData['error'] ?? $createData]);
                return ['success' => false, 'message' => $message];
            }

            // Step 2 — publish the container
            $publish = Http::timeout(20)->asForm()->post(self::GRAPH_BASE . "/{$igId}/media_publish", [
                'creation_id'  => $createData['id'],
                'access_token' => $token,
            ]);
            $publishData = $publish->json() ?? [];

            if ($publish->failed() || isset($publishData['error'])) {
                $message = $publishData['error']['message'] ?? 'Could not publish Instagram media.';
                Log::warning('Instagram publish failed', ['post_id' => $post->id, 'error' => $publishData['error'] ?? $publishData]);
                return ['success' => false, 'message' => $message];
            }

            return [
                'success' => true,
                'message' => 'Shared to Instagram.',
                'id'      => $publishData['id'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('Instagram share exception', ['post_id' => $post->id, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Instagram request failed: ' . $e->getMessage()];
        }
    }
}
