<?php

namespace App\Services;

class FirebaseService
{
    protected $databaseUrl;
    protected $databaseSecret;

    public function __construct()
    {
        $this->databaseUrl = config('firebase.database_url');
        $this->databaseSecret = config('firebase.database_secret');
    }

    /**
     * Send notification to Firebase using REST API
     * This is just for real-time alert, not for storing data
     */
    public function sendNotification(array $notification)
    {
        // If Firebase is not configured, just log it
        if (empty($this->databaseUrl) || empty($this->databaseSecret)) {
            \Log::info('Firebase not configured. Notification: ' . json_encode($notification));
            return;
        }

        try {
            $url = rtrim($this->databaseUrl, '/') . '/alerts.json?auth=' . $this->databaseSecret;
            
            // Send only notification alert, not full data
            $alertData = [
                'title' => $notification['title'] ?? 'New Notification',
                'type'  => $notification['type'] ?? "null",
                'recipient_id' => $notification['authId'] ?? '',
                'timestamp' => now()->toDateTimeString(),
            ];
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($alertData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            
            curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            \Log::error('Firebase notification error: ' . $e->getMessage());
            
        }
    }

    /**
     * Get database URL
     */
    public function getDatabaseUrl(): string
    {
        return $this->databaseUrl ?? '';
    }
}

