<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Comment;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AcnooBlogController extends Controller
{
    use HasUploader;

    public function index(Request $request)
    {
        $blogs = Blog::when($request->has('search'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('slug', 'like', '%' . $request->search . '%')
                    ->orWhere('descriptions', 'like', '%' . $request->search . '%');
            });
            })
            ->latest();

        if ($request->ajax()) {
            $blogs = $blogs->get();

            return response()->json([
                'data' => view('admin.website-setting.blogs.datas', compact('blogs'))->render()
            ]);
        }

        $blogs = $blogs->paginate(10);
        return view('admin.website-setting.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.website-setting.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|unique:blogs,title',
            'image'             => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'status'            => 'boolean',
            'descriptions'      => 'nullable|string',
            'tags'              => 'nullable|array',
            'meta.title'        => 'nullable|string',
            'meta.description'  => 'nullable|string',
        ]);

        Blog::create($request->except('image') + [
            'user_id' => Auth::id(),
            'image' => $request->image ? $this->upload($request, 'image') : null,
        ]);

        return response()->json([
            'message'   => __('BLog created successfully'),
            'redirect'  => route('admin.blogs.index')
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('admin.website-setting.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {

        $request->validate([
            'title'             => 'required|unique:blogs,title,' . $blog->id,
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'status'            => 'boolean',
            'descriptions'      => 'nullable|string',
            'tags'              => 'nullable|array',
            'meta.title'        => 'nullable|string',
            'meta.description'  => 'nullable|string',
        ]);

        $blog->update($request->except('image') + [
            'user_id' => Auth::id(),
            'image' => $request->image ? $this->upload($request, 'image', $blog->image) : $blog->image,
        ]);

        return response()->json([
            'message'   => __('BLog updated successfully'),
            'redirect'  => route('admin.blogs.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if (file_exists($blog->image)) {
            Storage::delete($blog->image);
        }

        $blog->delete();

        return response()->json([
            'message' => __('Blog deleted successfully'),
            'redirect' => route('admin.blogs.index')
        ]);
    }

    public function status(Request $request, $id)
    {
        $blog_status = Blog::findOrFail($id);
        $blog_status->update(['status' => $request->status]);
        return response()->json(['message' => 'Blog']);
    }


    public function deleteAll(Request $request)
    {
        Blog::whereIn('id', $request->ids)->delete();
        return response()->json([
            'message' => __('Selected Blog deleted successfully'),
            'redirect' => route('admin.blogs.index')
        ]);
    }

    public function filterComment($id)
    {
        $blog = Blog::findOrFail($id);
        $comments = Comment::with('blog:id')->whereStatus(1)->where('blog_id', $blog->id)->latest()->paginate(10);
        return view('admin.website-setting.blogs.comments.index',compact('comments'));
    }
}
