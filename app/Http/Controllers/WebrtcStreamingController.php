<?php

namespace App\Http\Controllers;

use App\Events\StreamAnswer;
use App\Events\StreamOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebrtcStreamingController extends Controller
{

    /**
    *   This returns the view for the broadcaster. 
    *   We pass a 'type': broadcaster and the user's ID 
    *   into the view to help identify who the user is.  
    */ 
    public function index()
    {
        return view('video-broadcast', ['type' => 'broadcaster', 'id' => Auth::id()]);
    }

    /**
    *   It returns the view for a new user who wants to join 
    *   the live stream. We pass a 'type': consumer, the streamId 
    *   we extract from the broadcasting link and the user's ID.  
    */ 
    public function consumer(Request $request, $streamId)
    {
        return view('video-broadcast', ['type' => 'consumer', 'streamId' => $streamId, 'id' => Auth::id()]);
    }

    /**
    *   It broadcasts an offer signal sent by the broadcaster to a 
    *   specific user who just joined. The following data is sent:
    *   broadcaster: The user ID of the one who initiated the live stream i.e the broadcaster
    *   receiver: The ID of the user to whom the signaling offer is being sent.
    *   offer: This is the WebRTC offer from the broadcaster.   
    */ 
    public function makeStreamOffer(Request $request)
    {
        $data['broadcaster'] = $request->broadcaster;
        $data['receiver'] = $request->receiver;
        $data['offer'] = $request->offer;

        event(new StreamOffer($data));
    }

    /**
    *   It sends an answer signal to the broadcaster to fully 
    *   establish the peer connection.
    *   broadcaster: The user ID of the one who initiated the live stream i.e the broadcaster.
    *   answer: This is the WebRTC answer from the viewer after, sent after receiving an offer from the broadcaster.  
    */ 
    public function makeStreamAnswer(Request $request)
    {
        $data['broadcaster'] = $request->broadcaster;
        $data['answer'] = $request->answer;
        event(new StreamAnswer($data));
    }
}
