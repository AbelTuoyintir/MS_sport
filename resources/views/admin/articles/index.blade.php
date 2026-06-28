@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">News & Articles</h2>
    <a href="{{ route('admin.articles.create') }}" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">Create New Article</a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="glass-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-white/10 bg-white/5">
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Tag</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-bold">{{ $article->title }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs bg-white/10 px-2 py-1 rounded">{{ $article->tag ?? 'General' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-bold uppercase {{ $article->is_published ? 'text-green-400' : 'text-yellow-500' }}">
                        {{ $article->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ $article->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-accent-gold hover:underline text-sm">Edit</a>
                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $articles->links() }}
</div>
@endsection
