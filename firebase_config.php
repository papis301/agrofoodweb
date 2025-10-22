<?php
require 'vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;

function getFirestore()
{
    return new FirestoreClient([
        'projectId' => 'its2025',
        'apiEndpoint' => 'firestore.googleapis.com', // Force REST
    ]);
}
?>