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

        $sectionCount = CmsSection::count();
        $postCount = BlogPost::count();

        return view('erp.cms.index', compact('sectionCount', 'postCount'));
    }

    public function sections(): View
    {
        CmsSection::ensureDefaultSections();

        $sections = CmsSection::orderBy('sort_order')->paginate(12);

        return view('erp.cms.sections.index', compact('sections'));
    }

    public function createSection(): View
    {
        return view('erp.cms.sections.create');
    }

    public function storeSection(Request $request): RedirectResponse
    {
        $data = $this->validateSection($request);

        $section = CmsSection::create($this->sectionPayload($request, $data));
        $this->audit('created', $section, 'Section CMS dibuat');

        return redirect()->route('cms.sections.index')->with('status', 'Section CMS berhasil dibuat.');
    }

    public function editSection(CmsSection $section): View
    {
        return view('erp.cms.sections.edit', compact('section'));
    }

    public function updateSection(Request $request, CmsSection $section): RedirectResponse
    {
        $data = $this->validateSection($request, $section);

        $section->update($this->sectionPayload($request, $data, $section));

        $this->audit('updated', $section, 'Section CMS diupdate');

        return redirect()->route('cms.sections.index')->with('status', 'Section CMS berhasil disimpan.');
    }

    public function destroySection(CmsSection $section): RedirectResponse
    {
        $section->delete();
        $this->audit('deleted', $section, 'Section CMS dihapus');

        return redirect()->route('cms.sections.index')->with('status', 'Section CMS berhasil dihapus.');
    }

    public function blog(): View
    {
        $posts = BlogPost::latest()->paginate(12);

        return view('erp.cms.blog.index', compact('posts'));
    }

    public function createPost(): View
    {
        return view('erp.cms.blog.create');
    }

    public function editPost(BlogPost $post): View
    {
        return view('erp.cms.blog.edit', compact('post'));
    }

    public function storePost(Request $request): RedirectResponse
    {
        $data = $this->validatePost($request);
        $data['user_id'] = auth()->id();
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $data['title']);
        $data['cover_path'] = $this->storeUpload($request, 'cover', 'blog');
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;

        $post = BlogPost::create($data);
        $this->audit('created', $post, 'Blog post dibuat');

        return redirect()->route('cms.blog.index')->with('status', 'Blog berhasil dibuat.');
    }

    public function updatePost(Request $request, BlogPost $post): RedirectResponse
    {
        $data = $this->validatePost($request);
        $data['slug'] = $request->filled('slug') ? $this->uniqueSlug($request->input('slug'), $post->id) : $this->uniqueSlug($data['title'], $post->id);
        $data['cover_path'] = $this->storeUpload($request, 'cover', 'blog') ?? $post->cover_path;
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $post->published_at ?? now()) : null;

        $post->update($data);
        $this->audit('updated', $post, 'Blog post diupdate');

        return redirect()->route('cms.blog.index')->with('status', 'Blog berhasil diupdate.');
    }

    public function destroyPost(BlogPost $post): RedirectResponse
    {
        $post->delete();
        $this->audit('deleted', $post, 'Blog post dihapus');

        return redirect()->route('cms.blog.index')->with('status', 'Blog berhasil dihapus.');
    }

    public function apiSettings(): View
    {
        $endpoints = [
            ['method' => 'GET', 'path' => '/api/v1/cms/content', 'name' => 'Konten publik CMS'],
            ['method' => 'GET', 'path' => '/api/v1/cms/sections', 'name' => 'Daftar section CMS'],
            ['method' => 'PUT', 'path' => '/api/v1/cms/sections/{section}', 'name' => 'Update section CMS'],
            ['method' => 'GET', 'path' => '/api/v1/cms/blog', 'name' => 'Daftar blog published'],
            ['method' => 'GET', 'path' => '/api/v1/cms/blog/{slug}', 'name' => 'Detail blog published'],
            ['method' => 'POST', 'path' => '/api/v1/cms/blog', 'name' => 'Tambah blog via API'],
            ['method' => 'PUT', 'path' => '/api/v1/cms/blog/{post}', 'name' => 'Update blog via API'],
            ['method' => 'DELETE', 'path' => '/api/v1/cms/blog/{post}', 'name' => 'Hapus blog via API'],
        ];

        return view('erp.cms.api-settings', compact('endpoints'));
    }

    private function validateSection(Request $request, ?CmsSection $section = null): array
    {
        return $request->validate([
            'key' => ['required', 'max:100', 'alpha_dash', 'unique:cms_sections,key' . ($section ? ',' . $section->id : '')],
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
    }

    private function sectionPayload(Request $request, array $data, ?CmsSection $section = null): array
    {
        $items = ($data['items_json'] ?? null)
            ? json_decode($data['items_json'], true, flags: JSON_THROW_ON_ERROR)
            : [];

        return [
            'key' => $data['key'],
            'label' => $data['label'],
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
            'content' => $data['content'] ?? null,
            'items' => $items,
            'image_path' => $this->storeUpload($request, 'image', 'cms') ?? $section?->image_path,
            'button_label' => $data['button_label'] ?? null,
            'button_url' => $data['button_url'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_visible' => $request->boolean('is_visible'),
        ];
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
