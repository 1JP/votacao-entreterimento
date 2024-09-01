<?php

namespace App\Enum;

class TypesOfFilmsEnum
{

    public static $data = [
        [
            'name' => 'Ação',
            'description' => 'Filmes com cenas intensas de movimento, como lutas, perseguições e explosões.',
        ],
        [
            'name' => 'Aventura',
            'description' => 'Filmes que envolvem viagens, exploração e desafios em ambientes exóticos ou perigosos.',
        ],
        [
            'name' => 'Comédia',
            'description' => 'Filmes voltados para o humor, com situações engraçadas e diálogos espirituosos.',
        ],
        [
            'name' => 'Drama',
            'description' => 'Foco em histórias sérias e emocionais, explorando a condição humana e conflitos pessoais.',
        ],
        [
            'name' => 'Terror',
            'description' => 'Criado para assustar e provocar medo, com elementos sobrenaturais ou violência intensa.',
        ],
        [
            'name' => 'Suspense',
            'description' => 'Gênero que cria tensão e expectativa, envolvendo mistério, crime ou perseguições.',
        ],
        [
            'name' => 'Ficção Científica',
            'description' => 'Explora temas como tecnologia futurista, viagens espaciais, e universos alternativos.',
        ],
        [
            'name' => 'Fantasia',
            'description' => 'Ambientado em mundos fictícios, geralmente com elementos mágicos e criaturas míticas.',
        ],
        [
            'name' => 'Romance',
            'description' => 'Centra-se em histórias de amor, explorando os relacionamentos e emoções dos personagens.',
        ],
        [
            'name' => 'Documentário',
            'description' => 'Filmes que retratam fatos reais, pessoas, eventos ou problemas sociais.',
        ],
        [
            'name' => 'Musical',
            'description' => 'Combina narrativa e música, onde personagens expressam emoções através de canções.',
        ],
        [
            'name' => 'Guerra',
            'description' => 'Focado em conflitos militares e as consequências emocionais e morais da guerra.',
        ],
        [
            'name' => 'Biografia',
            'description' => 'Filmes que contam a vida de uma pessoa real, frequentemente figuras históricas ou líderes.',
        ],
        [
            'name' => 'Animação',
            'description' => 'Utiliza técnicas de animação para contar a história, podendo ser voltado para crianças ou adultos.',
        ],
        [
            'name' => 'Western',
            'description' => 'Ambientado no Velho Oeste americano, com cowboys, pistoleiros e a luta pela sobrevivência.',
        ],
    ];

    public static function all() {
        return self::$data;
    }
}
