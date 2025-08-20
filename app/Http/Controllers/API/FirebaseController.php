<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Messaging;

class FirebaseController extends Controller
{
    //
    private $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $message = [
            'token' => $request->fcm_token,
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
            ],
        ];

        $this->messaging->send($message);

        return response()->json(['status' => 'success']);
    }
}
