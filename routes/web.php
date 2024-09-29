<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-gcs', function () {
    try {
        $result = Storage::disk('gcs')->put('test.txt', 'This is a test content');
        dd($result);
    } catch (\Exception $e) {
        dd('Error: ' . $e->getMessage());
    }
});

Route::get('/test-env', function () {
    $projectId = env('GOOGLE_CLOUD_PROJECT_ID');
    $bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET');
    $keyFilePath = env('GOOGLE_CLOUD_KEY_FILE_PATH');

    dd(compact('projectId', 'bucketName', 'keyFilePath'));
});

Route::get('/test-gcs-upload', function () {
    try {
        $result = Storage::disk('gcs')->put('test.txt', 'This is a test content');
        if ($result === false) {
            return 'File upload failed: Storage::put returned false.';
        } else {
            return 'File uploaded successfully.';
        }
    } catch (\Exception $e) {
        return 'Exception during file upload: ' . $e->getMessage();
    }
});