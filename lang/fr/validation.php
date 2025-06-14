<?php

return [
    'attributes' => [
        'locale_id' => 'locale',
        'key' => 'clé de traduction',
        'value' => 'valeur de traduction',
        'group' => 'groupe de traduction',
        'code' => 'code de langue',
        'name' => 'nom de langue',
        'is_active' => 'statut actif',
        'tags' => 'étiquettes',
    ],

    'custom' => [
        'locale_id' => [
            'required' => 'Veuillez sélectionner une langue.',
            'exists' => 'La langue sélectionnée est invalide.',
        ],
        'key' => [
            'required' => 'Veuillez fournir une clé de traduction.',
            'max' => 'La clé de traduction ne peut pas dépasser :max caractères.',
        ],
        'value' => [
            'required' => 'Veuillez fournir une valeur de traduction.',
        ],
        'group' => [
            'required' => 'Veuillez fournir un groupe de traduction.',
            'max' => 'Le groupe de traduction ne peut pas dépasser :max caractères.',
        ],
        'code' => [
            'required' => 'Veuillez fournir un code de langue.',
            'max' => 'Le code de langue ne peut pas dépasser :max caractères.',
            'unique' => 'Ce code de langue est déjà utilisé.',
        ],
        'name' => [
            'required' => 'Veuillez fournir un nom de langue.',
            'max' => 'Le nom de langue ne peut pas dépasser :max caractères.',
        ],
        'is_active' => [
            'boolean' => 'Le statut actif doit être vrai ou faux.',
        ],
        'tags' => [
            'array' => 'Les étiquettes doivent être fournies sous forme de liste.',
            'exists' => 'Une ou plusieurs étiquettes sélectionnées sont invalides.',
        ],
    ],
]; 