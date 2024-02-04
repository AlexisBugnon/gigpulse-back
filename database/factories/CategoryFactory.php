<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{

    // Need to pass in the object property so that each time the seeder creates the categories it can remove an element from the array with the array pop
    protected $categories = ['Développement', 'Rédaction', 'Services Quotidiens', 'Production Audiovisuelle', 'Design & Graphisme', 'Stratégie d\'Entreprise', 'Formation & Education', 'Marketing & Publicité'];

    // Generation of the description icons
    protected $descriptions = [' Codez vos idées en réalité', 'Des mots pour captiver l\'audience', 'Solutions pour la vie de tous les jours', 'Conceptions multimédia captivantes', 'Créativité visuelle impactante', 'Développez votre vision business', 'Apprendre, enseigner, évoluer', 'Maximisez impact et visibilité'];

    // Generation of the categories icons
    protected $icons = ['CodeBracketIcon', 'PencilIcon', 'SquaresPlusIcon', 'PlayCircleIcon', 'PaintBrushIcon', 'BriefcaseIcon', 'AcademicCapIcon', 'MegaphoneIcon'];

    protected $pictures = ['development-banner.jpg','writing-banner.jpg','everyday-life-banner.jpg','audio-video-banner.jpg','graphism-banner.jpg', 'business-banner.jpg','training-banner.jpg','marketing-banner.jpg'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = array_pop($this->categories);
        $description = array_pop($this->descriptions);
        $icon = array_pop($this->icons);
        $picture = array_pop($this->pictures);
        // Replace the '/' in $name with '-' and create the names in slug
        $slug = Str::slug(str_replace('/', '-', $name));


        return [
            'name' => $name,
            'picture' => $picture,
            'description'  => $description,
            'slug'  => $slug,
            'react_icon'  => $icon,
            'created_at' => now(),
        ];
    }
}
