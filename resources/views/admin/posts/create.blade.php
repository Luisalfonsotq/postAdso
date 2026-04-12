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
                <flux:input label="Imagen del post" name="img_path" type="file" accept="image/*" />
                @error('img_path')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:input label="Título" name="title" :value="old('title')" required />
                    <flux:input label="Slug" name="slug" :value="old('slug')" required />
                </div>

                {{-- Excerpt es 'required' en tu controlador, así que debe ir visible o con valor --}}
                <flux:textarea label="Resumen (Excerpt)" name="excerpt" :value="old('excerpt')" rows="2" required />

                <flux:textarea label="Contenido del Post" name="content" rows="10" :value="old('content')" required />

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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

                    <div class="flex items-center md:pt-8">
                        {{-- Laravel recibirá 0 si no se marca, o 1 si se marca --}}
                        <input type="hidden" name="is_published" value="0">
                        <flux:checkbox label="¿Publicar inmediatamente?" name="is_published" value="1"
                            :checked="old('is_published')" />
                    </div>
                </div>

                <div class="flex gap-2 justify-end">
                    <flux:button as="a" :href="route('admin.posts.index')" variant="ghost" wire:navigate>
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Guardar Post
                    </flux:button>
                </div>
            </form>
        </flux:card>
    </flux:main>
</x-layouts::app.sidebar>
<script>
    document.querySelector('input[name="title"]').addEventListener('input', function () {
        let slug = this.value.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.querySelector('input[name="slug"]').value = slug;
    });
</script>