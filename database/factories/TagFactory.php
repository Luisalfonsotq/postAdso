<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /** Tags temáticos predefinidos para un blog */
    private static array $pool = [
        'Laravel', 'PHP', 'JavaScript', 'Vue.js', 'React', 'CSS', 'HTML',
        'UX Design', 'Backend', 'Frontend', 'API', 'Docker', 'Git',
        'Tutorial', 'Noticias', 'Open Source', 'Base de datos', 'SQL',
        'Seguridad', 'Rendimiento', 'Tailwind', 'Node.js', 'Testing',
        'Arquitectura', 'Patrones', 'DevOps', 'Linux', 'Cloud',
    ];

    private static int $index = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = self::$pool[self::$index % count(self::$pool)];
        self::$index++;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
