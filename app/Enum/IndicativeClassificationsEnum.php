<?php

namespace App\Enum;

class IndicativeClassificationsEnum
{

    public static $data = [
        [
            'indicative' => 'Livre',
            'description' => 'Conteúdo apropriado para todas as idades, sem cenas de violência, uso de drogas ou linguagem inapropriada.',
        ],
        [
            'indicative' => '10 anos',
            'description' => 'Pode conter violência leve, temas ligeiramente complexos e linguagens mais adequadas para pré-adolescentes.',
        ],
        [
            'indicative' => '12 anos',
            'description' => 'Pode incluir violência moderada, temas mais complexos, insinuações sexuais leves e algumas palavras de baixo calão.',
        ],
        [
            'indicative' => '14 anos',
            'description' => 'Pode conter violência intensa, linguagem inapropriada, temas sexuais e uso de drogas, mas de forma moderada.',
        ],
        [
            'indicative' => '16 anos',
            'description' => 'Conteúdo mais adulto, incluindo violência explícita, temas sexuais mais presentes, uso de drogas e linguagem pesada.',
        ],
        [
            'indicative' => '18 anos',
            'description' => 'Conteúdo adulto, com cenas explícitas de violência, sexo, uso de drogas e linguagem altamente ofensiva.',
        ],
    ];

    public static function all() {
        return self::$data;
    }
}