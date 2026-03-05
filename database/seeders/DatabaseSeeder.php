<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user Guru
        $guru = User::create([
            'name' => 'Pak Budi (Guru)',
            'email' => 'guru@ujian.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Buat user Murid
        User::create([
            'name' => 'Ani (Murid)',
            'email' => 'murid@ujian.com',
            'password' => Hash::make('password'),
            'role' => 'murid',
        ]);

        // Buat Ujian Contoh
        $exam = Exam::create([
            'title' => 'Ujian Matematika Dasar',
            'description' => 'Ujian untuk mengukur pemahaman matematika dasar kelas 7.',
            'duration' => 30,
            'is_active' => true,
            'created_by' => $guru->id,
        ]);

        // Soal-soal Pilihan Ganda
        $soalData = [
            [
                'text' => 'Berapa hasil dari 25 + 37?',
                'options' => ['50', '62', '72', '60'],
                'correct' => 1, // index 1 = '62'
                'points' => 20,
            ],
            [
                'text' => 'Berapakah 8 × 9?',
                'options' => ['63', '72', '81', '64'],
                'correct' => 1, // '72'
                'points' => 20,
            ],
            [
                'text' => 'Akar kuadrat dari 144 adalah...',
                'options' => ['10', '11', '12', '13'],
                'correct' => 2, // '12'
                'points' => 20,
            ],
            [
                'text' => 'Berapakah nilai dari 5³ (5 pangkat 3)?',
                'options' => ['15', '125', '25', '625'],
                'correct' => 1, // '125'
                'points' => 20,
            ],
            [
                'text' => 'Jika x + 15 = 40, maka nilai x adalah...',
                'options' => ['20', '25', '30', '55'],
                'correct' => 1, // '25'
                'points' => 20,
            ],
        ];

        foreach ($soalData as $i => $soal) {
            $question = Question::create([
                'exam_id' => $exam->id,
                'question_text' => $soal['text'],
                'question_type' => 'multiple_choice',
                'points' => $soal['points'],
                'order' => $i + 1,
            ]);

            foreach ($soal['options'] as $j => $optText) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optText,
                    'is_correct' => ($j === $soal['correct']),
                    'order' => $j + 1,
                ]);
            }
        }
    }
}
