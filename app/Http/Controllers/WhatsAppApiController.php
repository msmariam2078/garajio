<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

class WhatsAppApiController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index(){
        if(!Auth::user()->can('manage whatsapp chat')){
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $clients = User::where('type','client')->where('parent_id',parentId())->get();
        return view('whatsapp.index',compact('clients'));
    }
    public function sendMessage(Request $request,$id)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $message = $request->input('message');
        $client = User::where('parent_id',parentId())->find($id);
        $response = $this->whatsappService->sendMessage($client->phone_number, $message);

        return response()->json($response);
    }

    public function searchClient(Request $request){
        $query = $request->input('query');
        $users = User::where('parent_id', parentId())->where('type', 'client')->where('name', 'LIKE', "%{$query}%")->orWhere('phone_number', 'LIKE', "%{$query}%")->get();
        $html = '';

        foreach($users as $user){
            $html .= '<a data-id="'. $user->id .'" class="client-detail">
                    <li>
                        <div class="media">
                            <div class="media-body">
                                <h6>'.$user->name.'</h6>
                                <p> '.$user->phone_number.'</p>
                            </div>
                        </div>
                    </li>
                </a>';
        }

        return response()->json(['status'=>1,'html'=>$html]);
    }
}
