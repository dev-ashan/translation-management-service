<?php

return [
    'success' => [
        'created' => ':resource has been created successfully.',
        'updated' => ':resource has been updated successfully.',
        'deleted' => ':resource has been deleted successfully.',
        'restored' => ':resource has been restored successfully.',
        'list' => ':resource list retrieved successfully.',
        'retrieved' => ':resource retrieved successfully.',
        'search' => ':resource search completed successfully.',
    ],
    'error' => [
        'not_found' => ':resource not found.',
        'already_exists' => ':resource already exists.',
        'validation_failed' => 'The given data was invalid.',
        'unauthorized' => 'You are not authorized to perform this action.',
        'forbidden' => 'You do not have permission to perform this action.',
        'server_error' => 'An error occurred while processing your request.',
        'invalid_locale' => 'Invalid locale provided.',
        'invalid_translation' => 'Invalid translation data provided.',
        'invalid_group' => 'Invalid translation group provided.',
        'invalid_tags' => 'Invalid translation tags provided.',
        'duplicate_key' => 'A translation with this key already exists for the specified locale.',
        'default_locale_deletion' => 'Cannot delete the default locale.',
        'active_locale_deletion' => 'Cannot delete an active locale.',
    ],
    'resources' => [
        'translation' => 'Translation',
        'translations' => 'Translations',
        'locale' => 'Locale',
        'locales' => 'Locales',
        'tag' => 'Tag',
        'tags' => 'Tags',
    ],
]; 