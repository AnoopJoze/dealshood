<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ShortLink;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    /**
     * Create (or reuse) a short link for a page on this site.
     * Only ever shortens our own URLs — never an open redirector.
     */
    public function shorten(Request $request)
    {
        $request->validate([
            'url' => ['required', 'string', 'max:2048'],
        ]);

        $url = $request->input('url');

        if (!str_starts_with($url, url('/'))) {
            return response()->json(['success' => false, 'message' => 'Only links on this site can be shortened.'], 422);
        }

        $shortLink = ShortLink::getOrCreateFor($url);

        return response()->json(['success' => true, 'short_url' => $shortLink->short_url]);
    }

    /**
     * Resolve a short code back to its full URL.
     */
    public function redirect(string $code)
    {
        $shortLink = ShortLink::where('code', $code)->firstOrFail();
        $shortLink->increment('clicks');

        return redirect($shortLink->url);
    }
}
