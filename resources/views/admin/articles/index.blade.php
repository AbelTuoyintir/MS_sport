@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 p-6 rounded-2xl border border-border-dark shadow-xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-custom-green"></span>
                <span class="text-xs font-heading font-bold uppercase tracking-widest text-custom-green">Editorial Desk</span>
            </div>
            <h1 class="text-3xl font-display tracking-tight text-white">League News & Press Releases</h1>
            <p class="text-xs text-muted mt-1">Publish news articles, match reports, transfer gossip, and league announcements.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="px-5 py-2.5 bg-custom-green hover:bg-emerald-400 text-black font-heading font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
            <i data-lucide="pen-tool" class="w-4 h-4"></i> Create New Article
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-custom-green/10 border border-custom-green/30 text-custom-green text-xs font-semibold rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-custom-green flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Articles Table Card -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-dark text-[10px] font-heading font-bold uppercase tracking-widest text-muted bg-bg-dark3/60">
                        <th class="py-3.5 px-6">Headline</th>
                        <th class="py-3.5 px-6">Category Tag</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6">Published Date</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark/50 text-xs">
                    @forelse($articles as $article)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 font-bold text-text-light max-w-md">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-bg-dark3 border border-border-dark flex items-center justify-center text-custom-green flex-shrink-0">
                                        <i data-lucide="newspaper" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm line-clamp-1">{{ $article->title }}</div>
                                        <div class="text-[10px] text-muted line-clamp-1 mt-0.5">{{ Str::limit(strip_tags($article->content), 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-heading font-bold uppercase tracking-wider bg-bg-dark4 text-gold border border-gold/20">
                                    {{ $article->tag ?? 'General' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($article->is_published)
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-heading font-bold uppercase tracking-wider bg-custom-green/15 text-custom-green border border-custom-green/30">
                                        Published
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-heading font-bold uppercase tracking-wider bg-gold/15 text-gold border border-gold/30">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-muted">
                                {{ optional($article->created_at)->format('M d, Y · H:i') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="p-2 bg-bg-dark3 hover:bg-gold hover:text-black border border-border-dark text-muted rounded-lg transition-all" title="Edit Article">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-bg-dark3 hover:bg-custom-red/20 hover:text-custom-red border border-border-dark text-muted rounded-lg transition-all" title="Delete Article">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-muted">
                                No news articles created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($articles, 'links'))
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
