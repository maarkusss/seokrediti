@extends('layouts.app')

@if ($name = \App\Models\Admincp\Settings::where('name', 'name')->first())
    @section('title', 'Images - ' . $name->value)
@else
    @section('title', 'Images - ' . env('APP_NAME'))
@endif

@section('content')
    <div class="flex flex-col w-full min-h-screen pt-16 bg-slate-50 lg:pl-56">
        <!-- Header -->
        @include('admincp.components.header')
        <!-- Info box -->
        @include('admincp.components.infobox')

        <div class="flex flex-row flex-1 h-full">
            <!-- Sidebar -->
            @include('admincp.components.sidebar')

            <!-- Container -->
            <div class="flex flex-col items-start justify-start w-full min-h-full p-4 sm:p-6 bg-transparent">
                <!-- Header card with Upload form -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between w-full p-4 sm:p-5 mb-5 bg-white rounded border border-slate-200/80 shadow-xs gap-4">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-800">Media Library</h1>
                        <p class="text-xs text-slate-500 mt-0.5">Upload, browse and copy URLs for blog posts and lender content</p>
                    </div>

                    <form class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto"
                        action="{{ route('admincp.images.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input class="px-3 py-1.5 text-xs text-slate-700 bg-slate-50 border border-slate-300 rounded file:mr-3 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            name="image" type="file" accept="image/*" required />
                        <button class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-blue-600 rounded shadow-xs hover:bg-blue-700 transition-colors whitespace-nowrap"
                            type="submit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Upload image
                        </button>
                    </form>
                </div>

                <!-- Grid of Images -->
                <div class="grid w-full grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mb-6">
                    @forelse ($images as $image)
                        <div class="relative flex flex-col justify-between p-2 bg-white rounded border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow group">
                            <!-- Delete action button -->
                            <form class="absolute top-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-opacity"
                                action="{{ route('admincp.images.destroy', ['image' => $image]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="p-1.5 text-red-600 bg-white/95 backdrop-blur-xs rounded shadow-md border border-slate-200 hover:bg-red-50 hover:text-red-700 transition-colors"
                                    type="submit" title="Delete image"
                                    onclick="return confirm('Are you sure you want to delete this image?');">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                            <!-- Image Thumbnail -->
                            <div class="w-full h-36 mb-2 overflow-hidden rounded bg-slate-100 flex items-center justify-center">
                                <img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-200"
                                    src="{{ asset($image->path) }}" alt="Image" loading="lazy" />
                            </div>

                            <!-- Copy URL Button -->
                            <button type="button"
                                class="copy-image-btn w-full inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded transition-colors"
                                data-url="{{ asset($image->path) }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span class="btn-label">Copy URL</span>
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full p-8 text-center bg-white rounded border border-slate-200/80 shadow-xs text-sm text-slate-500">
                            No images uploaded yet. Use the upload bar above to add images.
                        </div>
                    @endforelse
                </div>

                {{ $images->links('admincp.components.pagination') }}
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        document.querySelectorAll(".copy-image-btn").forEach(button => {
            button.addEventListener("click", () => {
                const url = button.dataset.url;
                const label = button.querySelector(".btn-label");

                navigator.clipboard.writeText(url).then(() => {
                    label.textContent = "Copied!";
                    button.classList.add("text-emerald-700", "bg-emerald-50", "border-emerald-300");
                    setTimeout(() => {
                        label.textContent = "Copy URL";
                        button.classList.remove("text-emerald-700", "bg-emerald-50", "border-emerald-300");
                    }, 1500);
                });
            });
        });
    </script>
@endsection
