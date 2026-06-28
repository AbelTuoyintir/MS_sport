@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Create New Article</h2>

    <div class="glass-card p-6">
        <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Title</label>
                <input type="text" name="title" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Tag</label>
                    <input type="text" name="tag" placeholder="e.g. Injury, Transfer, Stats" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        <span class="ml-3 text-sm font-bold text-gray-400 uppercase">Publish Immediately</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Content</label>
                <textarea name="content" rows="10" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">Create Article</button>
                <a href="{{ route('admin.articles.index') }}" class="flex-1 bg-white/5 text-center font-bold py-3 rounded-lg border border-white/10">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
