<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;

putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/serviceAccountKey.json');

function getFirestore() {
    $firestore = new FirestoreClient([
        'projectId' => 'its2025',
        'apiEndpoint' => 'firestore.googleapis.com'
    ]);
    return $firestore;
}
