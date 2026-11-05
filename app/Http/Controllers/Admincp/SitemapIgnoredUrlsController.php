<?php

namespace App\Http\Controllers\Admincp;

use App\Http\Controllers\Controller;
use App\Models\SitemapIgnoredUrl;
use Illuminate\Http\Request;

class SitemapIgnoredUrlsController extends Controller
{
    public function index()
    {
        $urls = SitemapIgnoredUrl::orderBy('id', 'desc')->get();

        return view('admincp.sitemap-ignored-urls.index', [
            'urls' => $urls,
        ]);
    }

    public function updateIndex(Request $request)
    {
        if ($request->ignored_url) {
            foreach ($request->ignored_url['active'] ?? [] as $id => $active) {
                SitemapIgnoredUrl::where('id', $id)->update([
                    'active' => $active,
                ]);
            }
        }

        return redirect()
            ->route('admincp.sitemap-ignored-urls.index')
            ->with('success', 'Sitemap ignored URLs updated successfully.');
    }

    public function add()
    {
        return view('admincp.sitemap-ignored-urls.add');
    }

    public function create(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:255|unique:sitemap_ignored_urls,url',
            'active' => 'nullable|boolean',
        ]);

        SitemapIgnoredUrl::create([
            'url' => $this->normalizeUrl($request->url),
            'active' => $request->active ?? 0,
        ]);

        return redirect()
            ->route('admincp.sitemap-ignored-urls.index')
            ->with('success', 'Sitemap ignored URL added successfully.');
    }

    public function edit($id)
    {
        $url = SitemapIgnoredUrl::findOrFail($id);

        return view('admincp.sitemap-ignored-urls.edit', [
            'url' => $url,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ignoredUrl = SitemapIgnoredUrl::findOrFail($id);

        $request->validate([
            'url' => 'required|string|max:255|unique:sitemap_ignored_urls,url,' . $ignoredUrl->id,
            'active' => 'nullable|boolean',
        ]);

        $ignoredUrl->update([
            'url' => $this->normalizeUrl($request->url),
            'active' => $request->active ?? 0,
        ]);

        return redirect()
            ->route('admincp.sitemap-ignored-urls.index')
            ->with('success', 'Sitemap ignored URL edited successfully.');
    }

    public function delete($id)
    {
        SitemapIgnoredUrl::findOrFail($id)->delete();

        return redirect()
            ->route('admincp.sitemap-ignored-urls.index')
            ->with('success', 'Sitemap ignored URL deleted successfully.');
    }

    private function normalizeUrl($url)
    {
        return rtrim(trim($url), '/');
    }
}