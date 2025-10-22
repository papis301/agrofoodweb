<?php
require 'vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;

function getFirestore()
{
    return new FirestoreClient([
        'projectId' => 'ton-project-id',
        'apiEndpoint' => 'firestore.googleapis.com', // Force REST
    ]);
}
?>