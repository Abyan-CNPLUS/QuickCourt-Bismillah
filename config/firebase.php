<?php

return [
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/quickcourt-apps-firebase-adminsdk-fbsvc-38f0ecaf06.json')),
        'auto_discovery' => false,
    ],

    'default_project_id' => env('FIREBASE_PROJECT_ID'),
];
