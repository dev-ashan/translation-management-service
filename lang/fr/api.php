<?php

return [
    'success' => [
        'created' => ':resource a été créé avec succès.',
        'updated' => ':resource a été mis à jour avec succès.',
        'deleted' => ':resource a été supprimé avec succès.',
        'restored' => ':resource a été restauré avec succès.',
        'listed' => 'Liste de :resource récupérée avec succès.',
        'retrieved' => ':resource récupéré avec succès.',
    ],
    'error' => [
        'not_found' => ':resource non trouvé.',
        'already_exists' => ':resource existe déjà.',
        'validation_failed' => 'Les données fournies sont invalides.',
        'unauthorized' => 'Vous n\'êtes pas autorisé à effectuer cette action.',
        'forbidden' => 'Vous n\'avez pas la permission d\'effectuer cette action.',
        'server_error' => 'Une erreur s\'est produite lors du traitement de votre demande.',
        'invalid_locale' => 'Locale invalide fournie.',
        'invalid_translation' => 'Données de traduction invalides fournies.',
        'invalid_group' => 'Groupe de traduction invalide fourni.',
        'invalid_tags' => 'Tags de traduction invalides fournis.',
        'duplicate_key' => 'Une traduction avec cette clé existe déjà pour la locale spécifiée.',
        'default_locale_deletion' => 'Impossible de supprimer la locale par défaut.',
        'active_locale_deletion' => 'Impossible de supprimer une locale active.',
    ],
    'resources' => [
        'translation' => 'Traduction',
        'translations' => 'Traductions',
        'locale' => 'Locale',
        'locales' => 'Locales',
        'tag' => 'Tag',
        'tags' => 'Tags',
    ],
]; 