<?php
namespace App\Http\Controllers;
use App\Models\Queue;
use App\Models\Session;
use Illuminate\Support\Facades\Session as SessionFacade;
class WaitingController extends Controller
{
    public function index()
    {
        $sessionId = SessionFacade::getId();
        $session = Session::firstOrCreate(['id' => $sessionId]);
        Queue::firstOrCreate(
            ['session_id' => $session->id],
            ['entry_time' => now()]
        );

        $queueUsers = Queue::with('session')->get();
        return view('waiting', compact('queueUsers'));
    }
}
