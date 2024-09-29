<?php

require 'vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Set the values directly or read them from the environment.
$keyFilePath = '/Users/overfrenzy/Documents/Code/GitHub/naletu_backend/frenzycorptm-ad6c913b9ff5.json';
$projectId = 'frenzycorptm'; // Replace with your actual Google Cloud project ID
$bucketName = 'naletu_bucket'; // Replace with your actual bucket name

// Debugging information
echo "Project ID: $projectId" . PHP_EOL;
echo "Bucket Name: $bucketName" . PHP_EOL;
echo "Key File Path: $keyFilePath" . PHP_EOL;

try {
    // Initialize Google Cloud Storage client
    $storage = new StorageClient([
        'projectId' => $projectId,
        'keyFilePath' => $keyFilePath,
    ]);

    // Check if the bucket exists
    $bucket = $storage->bucket($bucketName);
    if (!$bucket->exists()) {
        throw new Exception("Bucket not found: $bucketName");
    }

    // Upload a test file
    $object = $bucket->upload(
        'This is a test file from standalone PHP script',
        [
            'name' => 'test-folder/test-file.txt'
        ]
    );
    echo "File uploaded successfully." . PHP_EOL;

} catch (Exception $e) {
    echo "Failed to upload file: " . $e->getMessage() . PHP_EOL;
}
