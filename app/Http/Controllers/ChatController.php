<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Mail\SmartEmail;
use App\Models\EmailMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
	public function index()
	{
		$authUserId = Auth::id();
		$messageUserIds = Message::where('sender_id', $authUserId)
			->orWhere('receiver_id', $authUserId)
			->get()
			->flatMap(function ($message) use ($authUserId) {
				return [$message->sender_id, $message->receiver_id];
			})
			->unique()
			->filter(fn($id) => $id != $authUserId)
			->values();

		$users = User::whereIn('id', $messageUserIds)
			->select('users.*')
			->get()
			->map(function ($user) use ($authUserId) {
				$lastMsg = Message::where(function ($query) use ($authUserId, $user) {
					$query->where(function ($q) use ($authUserId, $user) {
						$q->where('sender_id', $authUserId)->where('receiver_id', $user->id);
					})->orWhere(function ($q) use ($authUserId, $user) {
						$q->where('sender_id', $user->id)->where('receiver_id', $authUserId);
					});
				})->latest()->first();

				$user->last_time = $lastMsg?->created_at;
				$user->last_message = $lastMsg?->content;
				$user->last_sender_id = $lastMsg?->sender_id;

				return $user;
			})
			->sortByDesc('created_at')
			->values();

		$lastChatUser = Message::where('sender_id', $authUserId)
			->orWhere('receiver_id', $authUserId)
			->orderBy('created_at', 'desc')
			->first();

		$lastUser = null;
		if ($lastChatUser) {
			$lastUserId = $lastChatUser->sender_id == $authUserId ? $lastChatUser->receiver_id : $lastChatUser->sender_id;
			$lastUser = User::find($lastUserId);
		}

		return view('chat.index', compact('users', 'lastUser'));
	}

	public function fetchUsers($userId)
	{
		$user = User::with('clients')->where('id', $userId)->first();
		return response()->json($user);
	}

	public function sendMessage(Request $request)
	{
		$request->validate([
			'receiver_id' => 'required|exists:users,id',
			'content' => 'required|string'
		]);

		// Create new message
		$message = Message::create([
			'sender_id' => Auth::id(),
			'receiver_id' => $request->receiver_id,
			'content' => $request->content,
		]);

		return response()->json(['success' => true, 'message' => 'Message sent successfully']);
	}

	public function fetchMessages($userId)
	{
		$authUserId = Auth::id();
		$messages = Message::where(function ($query) use ($userId, $authUserId) {
			$query->where('sender_id', $authUserId)
				->where('receiver_id', $userId);
		})->orWhere(function ($query) use ($userId, $authUserId) {
			$query->where('sender_id', $userId)
				->where('receiver_id', $authUserId);
		})
			->orderBy('created_at', 'asc')
			->get()
			->map(function ($message) use ($authUserId) {
				return [
					'id' => $message->id,
					'sender_id' => $message->sender_id,
					'receiver_id' => $message->receiver_id,
					'content' => $message->content,
					'time' => $message->created_at->format('g:i a'),
					'date' => $message->created_at->format('Y-m-d'),
					'sender' => $message->sender_id == $authUserId ? 'self' : 'other',
				];
			});

		if ($messages->isEmpty()) {
			return response()->json(['html' => '']);
		}

		$receiver = User::find($userId);
		$html = view('chat.chat-full', compact('messages', 'receiver'))->render();

		return response()->json(['html' => $html]);
	}

	public function searchUser(Request $request)
	{
		$search = $request->get('name');

		$customers = User::where(function ($query) use ($search) {
			$query->where('first_name', 'LIKE', '%' . $search . '%')
				->orWhere('email', 'LIKE', '%' . $search . '%')
				->orWhere('mobile', 'LIKE', '%' . $search . '%');
		})
			->get(['id', 'first_name', 'email']);

		return response()->json($customers);
	}

	// Email section
	public function mail()
	{
		$data['emails'] = EmailMessage::orderBy('updated_at', 'desc')->get();
		$data['emailStatus'] = EmailMessage::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status')->toArray();
		return view('email.mail', $data);
	}

	public function makeEmail(Request $request)
	{
		$request->validate([
			'recipient' => 'required|email',
			'subject' => 'required|string',
			'body' => 'required|string'
		]);

		if ($request->has('draft')) {
			$status = 'draft';
		} elseif ($request->has('send')) {
			$status = 'send';

			$details = [
				'to' => $request->recipient,
				'from' => Auth::user()->email,
				'from_name' => 'Company Admin',
				'subject' => $request->subject,
				'heading' => 'Email heading',
				'body' => $request->body,
				'button' => [
					'url' => url('/policy-update'),
					'label' => 'View Policy',
				],
				'footer' => 'If you have any questions, feel free to contact our support team.',
			];

			Mail::send(new SmartEmail($details));
		}

		if (is_null($request->input('draftId'))) {
			EmailMessage::create([
				'sender' => Auth::user()->email,
				'recipient' => $request->recipient,
				'subject' => $request->subject,
				'body' => $request->body,
				'status' => $status,
			]);
		} else {
			EmailMessage::where('id', $request->draftId)->update([
				'status' => $status
			]);
		}

		return redirect()->back()->with('success', 'Email ' . $status . ' successfully!');
	}
}
