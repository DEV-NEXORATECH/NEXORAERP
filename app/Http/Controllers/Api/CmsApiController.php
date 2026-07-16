<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\LoadsErpData;
use App\Models\BlogPost;
use App\Models\CmsSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CmsApiController extends Controller
{
    use LoadsErpData;

    public function content(): JsonResponse
    {
        CmsSection::ensureDefaultSections();

        return $this->respond([
            'sections' => CmsSection::query()
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('key'),
            'latest_posts' => $this->publishedPostsQuery()->take(3)->get(),
        ]);
    }

    public function sections(): JsonResponse
    {
        CmsSection::ensureDefaultSections();

        return $this->respond(CmsSection::orderBy('sort_order')->get());
    }

    public function updateSection(Request $request, CmsSection $section): JsonResponse
    {
        $data = $request->validate([
            'label' => ['sometimes', 'required', 'max:255'],
            'title' => ['nullable', 'max:255'],
            'subtitle' => ['nullable', 'max:255'],
            'content' => ['nullable'],
            'items' => ['nullable', 'array'],
            'image' => ['nullable', 'file', 'max:2048'],
            'image_path' => ['nullable', 'max:255'],
            'button_label' => ['nullable', 'max:255'],
            'button_url' => ['nullable', 'max:255'],
            'is_visible' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storeUpload($request, 'image', 'cms');
        }

        $section->update($data);
        $this->audit('updated', $section, 'Section CMS diupdate via API');

        return $this->respond($section->fresh(), 'Section CMS berhasil disimpan.');
    }

    public function blog(Request $request): JsonResponse
    {
        $query = $request->boolean('include_drafts')
            ? BlogPost::query()->latest()
            : $this->publishedPostsQuery();

        return $this->respond($query->paginate(min((int) $request->get('per_page', 9), 100)));
    }

    public function showBlog(string $slug): JsonResponse
    {
        return $this->respond($this->publishedPostsQuery()->where('slug', $slug)->firstOrFail());
    }

    public function storeBlog(Request $request): JsonResponse
    {
        $data = $this->validatePost($request);
        $data['user_id'] = $request->user()?->id;
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $data['title']);
        $data['cover_path'] = $this->storeUpload($request, 'cover', 'blog');
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;

        $post = BlogPost::create($data);
        $this->audit('created', $post, 'Blog post dibuat via API');

        return $this->respond($post, 'Blog berhasil dibuat.', 201);
    }

    public function updateBlog(Request $request, BlogPost $post): JsonResponse
    {
        $data = $this->validatePost($request, true);

        if ($request->filled('slug') || $request->filled('title')) {
            $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('title'), $post->id);
        }

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $this->storeUpload($request, 'cover', 'blog');
        }

        if (($data['status'] ?? $post->status) === 'published') {
            $data['published_at'] = $data['published_at'] ?? $post->published_at ?? now();
        } elseif (array_key_exists('status', $data)) {
            $data['published_at'] = null;
        }

        $post->update($data);
        $this->audit('updated', $post, 'Blog post diupdate via API');

        return $this->respond($post->fresh(), 'Blog berhasil diupdate.');
    }

    public function destroyBlog(BlogPost $post): JsonResponse
    {
        $post->delete();
        $this->audit('deleted', $post, 'Blog post dihapus via API');

        return $this->respondDeleted('Blog berhasil dihapus.');
    }

    private function publishedPostsQuery()
    {
        return BlogPost::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at');
    }

    private function validatePost(Request $request, bool $isUpdate = false): array
    {
        $required = $isUpdate ? 'sometimes' : 'required';

        return $request->validate([
            'title' => [$required, 'max:255'],
            'slug' => ['nullable', 'max:255'],
            'excerpt' => ['nullable'],
            'body' => ['nullable'],
            'cover' => ['nullable', 'file', 'max:2048'],
            'status' => [$required, 'in:draft,published'],
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
