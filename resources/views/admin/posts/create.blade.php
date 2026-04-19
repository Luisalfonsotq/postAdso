<x-layouts::app.sidebar title="Crear Nuevo Post">
    <flux:main>
        <div class="mb-6">
            <flux:heading size="xl" level="1">Crear Nuevo Post</flux:heading>
            <flux:subheading>Completa todos los campos requeridos para el blog.</flux:subheading>
        </div>

        <flux:card>
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                {{-- ─── Imagen con previsualización ─────────────────────────── --}}
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        Imagen del post
                    </label>

                    {{-- Zona de previsualización --}}
                    <div id="image-preview-wrapper-create"
                        class="relative w-full h-56 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600
                               bg-zinc-50 dark:bg-zinc-900 overflow-hidden flex items-center justify-center
                               transition-all duration-300 cursor-pointer group"
                        onclick="document.getElementById('img-input-create').click()">

                        {{-- Placeholder cuando no hay imagen --}}
                        <div id="placeholder-create" class="flex flex-col items-center gap-2 text-zinc-400 select-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 opacity-50" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4-4a3 3 0 014.24 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm font-medium">Haz clic para subir una imagen</span>
                            <span class="text-xs opacity-70">JPG, PNG, GIF, WEBP · máx. 2 MB</span>
                        </div>

                        {{-- Preview --}}
                        <img id="img-preview-create" src="#" alt="Preview"
                            class="hidden absolute inset-0 w-full h-full object-cover rounded-xl transition-opacity duration-300" />

                        {{-- Overlay hover --}}
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity
                                    duration-200 flex items-center justify-center rounded-xl pointer-events-none">
                            <span class="text-white text-sm font-semibold">Cambiar imagen</span>
                        </div>
                    </div>

                    <input id="img-input-create" type="file" name="img_path" accept="image/*" class="hidden"
                        onchange="previewImage(this, 'img-preview-create', 'placeholder-create')">

                    @error('img_path')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ─── Título / Slug ─────────────────────────────────────────── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:input label="Título" name="title" :value="old('title')" required />
                    <flux:input label="Slug" name="slug" :value="old('slug')" required />
                </div>

                <flux:textarea label="Resumen (Excerpt)" name="excerpt" :value="old('excerpt')" rows="2" required />

                <flux:textarea label="Contenido del Post" name="content" rows="10" :value="old('content')" required />

                {{-- ─── Categoría | Fecha | Estado | Tags ────────────────────── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:select label="Categoría" name="category_id">
                        <option value="" disabled selected>Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </flux:select>

                    <flux:input type="datetime-local" label="Fecha de Publicación" name="published_at"
                        :value="old('published_at')" />
                </div>

                {{-- Tags --}}
                @if($tags->isNotEmpty())
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">Etiquetas (Tags)</label>
                    <div class="flex flex-wrap gap-2 p-3 rounded-lg border border-zinc-200 dark:border-zinc-700
                                bg-zinc-50 dark:bg-zinc-900">
                        @foreach($tags as $tag)
                            <label
                                class="tag-pill flex items-center gap-1.5 px-3 py-1.5 rounded-full border cursor-pointer
                                       text-sm font-medium select-none transition-all duration-150
                                       border-zinc-300 dark:border-zinc-600 text-zinc-600 dark:text-zinc-400
                                       hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400
                                       has-[:checked]:bg-indigo-500 has-[:checked]:border-indigo-500 has-[:checked]:text-white">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                    class="sr-only"
                                    {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                <span>#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_published" value="0">
                    <flux:checkbox label="¿Publicar inmediatamente?" name="is_published" value="1"
                        :checked="old('is_published')" />
                </div>

                {{-- ─── Acciones ───────────────────────────────────────────────── --}}
                <div class="flex gap-2 justify-end pt-2 border-t border-zinc-100 dark:border-zinc-800">
                    <flux:button as="a" :href="route('admin.posts.index')" variant="ghost" wire:navigate>
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="check">
                        Guardar Post
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </flux:main>
</x-layouts::app.sidebar>

<script>
    // Auto-slug desde título
    document.querySelector('input[name="title"]').addEventListener('input', function () {
        let slug = this.value.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.querySelector('input[name="slug"]').value = slug;
    });

    // Previsualización de imagen compartida
    function previewImage(input, previewId, placeholderId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>