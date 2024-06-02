<?php

namespace Database\Seeders;

use App\Models\RequestCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Сервисные центры',
                'id' => 1,
            ],
            [
                'name' => 'Поиск оборудования',
                'id' => 2,
            ],
            [
                'name' => 'Помощь эксперта',
                'id' => 3,
            ],
            [
                'name' => 'Где найти ближайший СЦ',
                'id' => 4,
                'parent_id' => 1,
            ],
            [
                'name' => 'В какое время работает СЦ в моем городе',
                'id' => 5,
                'parent_id' => 1,
            ],
            [
                'name' => 'Хочу найти определенное оборудование по штрих коду',
                'id' => 6,
                'parent_id' => 2,
            ],
            [
                'name' => 'Поиск оборудование по образцу',
                'id' => 7,
                'parent_id' => 2,
            ],
            [
                'name' => 'У меня есть фотография, но не знаю модель',
                'id' => 8,
                'parent_id' => 3,
            ],
            [
                'name' => 'Помощь в подборе оборудования исходя из потребностей',
                'id' => 9,
                'parent_id' => 3,
            ],
        ];

        collect($categories)->each(function ($category) { RequestCategory::create($category); });
    }
}
