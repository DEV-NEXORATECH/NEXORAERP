<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BlogPost;
use App\Models\CmsSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CmsController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        CmsSection::ensureDefaultSections();

        $sections = CmsSection::orderBy('sort_order')->get();
        $posts = BlogPost::latest()->paginate(10);
        $staticItems = $sections->pluck('label')->all();

        return view('erp.cms.index', compact('sections', 'posts', 'staticItems'));
    }

    public function updateSection(Request $request, CmsSection $section): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'max:255'],
            'title' => ['nullable', 'max:255'],
            'subtitle' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'items_json' => ['nullable', 'json'],
            'image' => ['nullable', 'file', 'max:2048'],
            'button_label' => ['nullable', 'max:255'],
            'button_url' => ['nullable', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ]);

        $items = $data['items_json']
            ? json_decode($data['items_json'], true, flags: JSON_THROW_ON_ERROR)
            : [];

        $section->update([
            'label' => $data['label'],
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'content' => $data['content'] ?? null,
            'items' => $items,
            'image_path' => $this->storeUpload($request, 'image', 'cms') ?? $section->image_path,
            'button_label' => $data['button_label'] ?? null,
            'button_url' => $data['button_url'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_visible' => $request->boolean('is_visible'),
        ]);

        $this->audit('updated', $section, 'Section CMS diupdate');

        return back()->with('status', 'Section CMS berhasil disimpan.');
    }

    public function storePost(Request $request): RedirectResponse
    {
        $data = $this->validatePost($request);
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['cover_path'] = $this->storeUpload($request, 'cover', 'blog');
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;

        $post = BlogPost::create($data);
        $this->audit('created', $post, 'Blog post dibuat');

        return back()->with('status', 'Blog berhasil dibuat.');
    }

    public function updatePost(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $this->validatePost($request);
        $data['slug'] = $request->filled('slug') ? $this->uniqueSlug($request->input('slug'), $post->id) : $this->uniqueSlug($data['title'], $post->id);
        $data['cover_path'] = $this->storeUpload($request, 'cover', 'blog') ?? $post->cover_path;
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $post->published_at ?? now()) : null;

        $post->update($data);
        $this->audit('updated', $post, 'Blog post diupdate');

        return back()->with('status', 'Blog berhasil diupdate.');
    }

    public function destroyPost(BlogPost $post): RedirectResponse
    {
        $post->delete();
        $this->audit('deleted', $post, 'Blog post dihapus');

        return back()->with('status', 'Blog berhasil dihapus.');
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'max:255'],
            'slug' => ['nullable', 'max:255'],
            'excerpt' => ['nullable'],
            'body' => ['nullable'],
            'cover' => ['nullable', 'file', 'max:2048'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::random(8);
        $slug = $base;
        $counter = 2;

        while (BlogPost::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

}
