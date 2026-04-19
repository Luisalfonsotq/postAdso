<x-layouts::app.sidebar title="Posts">
    <flux:main>
        <div class="flex justify-between items-center mb-6">
            <div>
                <flux:heading size="xl" level="1">Posts</flux:heading>
                <flux:subheading>Gestiona las publicaciones de tu blog</flux:subheading>
            </div>

            <flux:button href="{{ route('admin.posts.create') }}" variant="primary" icon="plus" wire:navigate>
                Nuevo Post
            </flux:button>
        </div>

        @if (session('info'))
            <flux:card class="bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 mb-4">
                <div class="flex items-center gap-2 text-green-800 dark:text-green-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('info') }}
                </div>
            </flux:card>
        @endif

        @if (session('success'))
            <flux:card class="bg-green-50 dark:bg-green-950 border-green-200 dark:border-green-800 mb-4">
                <div class="flex items-center gap-2 text-green-800 dark:text-green-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </flux:card>
        @endif

        <flux:card class="p-0 overflow-hidden">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Imagen</flux:table.column>
                    <flux:table.column>Título</flux:table.column>
                    <flux:table.column>Categoría</flux:table.column>
                    <flux:table.column>Tags</flux:table.column>
                    <flux:table.column>Estado</flux:table.column>
                    <flux:table.column>Fecha</flux:table.column>
                    <flux:table.column align="end">Acciones</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($posts as $post)
                        <flux:table.row :key="$post->id">
                            {{-- Miniatura --}}
                            <flux:table.cell>
                                @if($post->img_path)
                                    <img src="{{ Storage::url($post->img_path) }}" alt="{{ $post->title }}"
                                        class="h-12 w-20 rounded-lg object-cover shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-700">
                                @else
                                    <div class="h-12 w-20 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center
                                                justify-center ring-1 ring-zinc-200 dark:ring-zinc-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4-4a3 3 0 014.24 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                                   M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </flux:table.cell>

                            <flux:table.cell font="medium">{{ $post->title }}</flux:table.cell>

                            <flux:table.cell>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                             bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
                                    {{ $post->category->name ?? 'Sin categoría' }}
                                </span>
                            </flux:table.cell>

                            {{-- Tags --}}
                            <flux:table.cell>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($post->tags as $tag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                     bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300
                                                     ring-1 ring-indigo-200 dark:ring-indigo-700">
                                            #{{ $tag->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-zinc-400">—</span>
                                    @endforelse
                                </div>
                            </flux:table.cell>

                            <flux:table.cell>
                                <flux:badge :color="$post->is_published ? 'green' : 'zinc'" inset="top bottom">
                                    {{ $post->is_published ? 'Publicado' : 'Borrador' }}
                                </flux:badge>
                            </flux:table.cell>

                            <flux:table.cell>
                                {{ $post->published_at?->format('d/m/Y') ?? 'Sin fecha' }}
                            </flux:table.cell>

                            <flux:table.cell align="end">
                                <div class="flex justify-end gap-2">
                                    <flux:button href="{{ route('admin.posts.edit', $post) }}"
                                        variant="ghost" size="sm" icon="pencil-square" wire:navigate />

                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar el post «{{ addslashes($post->title) }}»?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" icon="trash" color="red" />
                                    </form>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7">
                                <div class="py-12 text-center text-zinc-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto w-10 h-10 mb-3 opacity-40"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0
                                               01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm">No hay posts todavía.</p>
                                    <a href="{{ route('admin.posts.create') }}"
                                        class="mt-2 inline-block text-sm text-indigo-500 hover:underline">
                                        Crear el primero →
                                    </a>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>

            @if ($posts->hasPages())
                <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $posts->links() }}
                </div>
            @endif
        </flux:card>
    </flux:main>
</x-layouts::app.sidebar>