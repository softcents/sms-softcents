<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:services-read')->only('index');
        $this->middleware('permission:services-create')->only('create', 'store');
        $this->middleware('permission:services-update')->only('edit', 'update','status');
        $this->middleware('permission:services-delete')->only('destroy','deleteAll');
    }

    public function index(Request $request)
    {
        $services = Service::when($request->has('search'), function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        })
            ->latest();

        if ($request->ajax()) {
            $services = $services->get();

            return response()->json([
                'data' => view('admin.website-setting.services.datas', compact('services'))->render()
            ]);
        }

        $services = $services->paginate(10);
        return view('admin.website-setting.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.website-setting.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        Service::create($request->except('image') + [
            'image' => $request->image ? $this->upload($request, 'image') : NULL
        ]);

        return response()->json([
            'message' => __('Service created successfully'),
            'redirect' => route('admin.services.index')
        ]);
    }

    public function edit(Service $service)
    {
        return view('admin.website-setting.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required',
            'title' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $service->update($request->except('image') + [
            'image' => $request->image ? $this->upload($request, 'image', $service->image) : $service->image,
        ]);

        return response()->json([
            'message' => __('Service updated successfully'),
            'redirect' => route('admin.services.index')
        ]);
    }

    public function destroy(Service $service)
    {
        if (file_exists($service->image)) {
            Storage::delete($service->image);
        }
        $service->delete();

        return response()->json([
            'message'   => __('Services deleted successfully'),
            'redirect'  => route('admin.services.index')
        ]);
    }

    public function status($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['status' => !$service->status]);
        return response()->json(['message' => 'Service ']);
    }

    public function deleteAll(Request $request)
    {
        $services = Service::whereIn('id', $request->ids)->get();
        foreach ($services as $service) {
            if (file_exists($service->image)) {
                Storage::delete($service->image);
            }
        }

        $services->each->delete();

        return response()->json([
            'message' => __('Services deleted successfully'),
            'redirect' => route('admin.services.index')
        ]);
    }
}
