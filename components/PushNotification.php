<?php

namespace app\components;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Yii;
use yii\base\Component;

class PushNotification extends Component
{
    private $serviceAccountPath;
    private $projectId;
    private $scopes;

    public function __construct($config = [])
    {
        $this->serviceAccountPath = Yii::getAlias('@app/config/service-account.json'); // Path to the service account
        $this->projectId = 'axis-seed'; // Replace with your Firebase project ID
        $this->scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        parent::__construct($config);
    }

    /**
     * Get OAuth 2.0 Access Token
     */
    private function getAccessToken()
    {
        $credentials = new ServiceAccountCredentials($this->scopes, $this->serviceAccountPath);
        if ($credentials->fetchAuthToken()) {
            return $credentials->fetchAuthToken()['access_token'];
        }
        throw new \Exception('Unable to fetch access token');
    }

    /**
     * Send Notification via FCM HTTP v1 API
     */
    public function sendNotification($payload)
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $accessToken = $this->getAccessToken();

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('CURL Error: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }
}
