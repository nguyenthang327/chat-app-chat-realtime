<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class MessageController extends Controller
{
    //
    public function index(){
        $user = Auth::user();
        $conversations = ConversationParticipant::join('users', 'users.id', 'conversation_participants.user_id')
            ->join('conversations', 'conversation_participants.conversation_id', 'conversations.id')
            ->whereIn('conversation_participants.conversation_id', function($query) use($user){
                $query->select('conversation_id')
                    ->from('conversation_participants')
                    ->where('conversation_participants.user_id', $user->id);
            })
            ->where('conversation_participants.user_id', '<>', $user->id)
            ->select([
                'conversation_participants.conversation_id',
                'users.*'
            ])
            ->groupBy('conversation_participants.conversation_id')
            ->get();

        return view('chat.chat', compact('conversations'));
    }

    public function detail(Request $request){
        $user = Auth::user();

        $messages = Message::where('messages.conversation_id', $request->get('conversation_id'))
            ->select([
                'messages.*',
                DB::raw("IF(messages.user_id = $user->id, 1, 0) as repaly")
            ])
            ->get();
        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => $messages
        ], Response::HTTP_OK);
    }

    public function store(StoreMessageRequest $request){
        try{
            DB::beginTransaction();

            $message = Message::create([
                'user_id' => Auth::user()->id,
                'conversation_id' => $request->input('conversation_id'),
                'content' => $request->input('content'),
            ]);
            Redis::connection();
            Redis::publish('new-message', json_encode($message));

            DB::commit();
            return response()->json([
                'status' => Response::HTTP_OK,
                'msg' => 'Send success',
                'data' => $message,
            ], Response::HTTP_OK);
        }catch(Exception $e){
            Log::error('[MessageController] error. Message: '. $e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'msg' => 'Server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
