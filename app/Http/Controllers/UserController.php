<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SkillGroup;
use App\Models\ShiftMaster;
use App\Traits\UploadTrait;
use App\Models\ClientDetail;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\LoggedHistory;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
	use UploadTrait;


	public function index()
	{
		if (\Auth::user()->can('user sidebar')) {

			$userRoles = Role::all();
			$ReportingManagers = User::whereIn('type', ['supervisor','admin','manager'])->get()->pluck('first_name', 'id');
		
			$country_code = User::$country_code;

			if (\Auth::user()->type == 'super admin' || \Auth::user()->type == 'owner' || \Auth::user()->type == 'admin') {
				$users = User::whereNotIn('type', ['client', 'technician'])->get();
				//where('parent_id', parentId())->where('type', 'owner')->get();
				return view('user.index', compact('users', 'userRoles', 'ReportingManagers', 'country_code'));
			}
			// else {
			//     $users = User::where('parent_id', '=', parentId())->whereNotIn('type', ['client', 'technician'])->get();
			//     return view('user.index', compact('users','userRoles','ReportingManagers', 'country_code'));
			// }
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}


	public function create()
	{

		$userRoles = Role::where('parent_id', parentId())->whereNotIn('name', ['client'])->get()->pluck('name', 'id', 'ccm');
		$ReportingManagers = User::whereIn('type', ['supervisor','admin','manager'])->get()->pluck('first_name', 'id');
		
		$country_code = User::$country_code;

		return view('user.create', compact('userRoles', 'ReportingManagers', 'country_code'));
	}


	public function store(Request $request)
	{

		if (\Auth::user()->can('create user')) {

			$path = "assets/image/";
			if ($request->hasFile('profile')) {
				$fullName = time() . '_' . uniqid() . '.' . $request->file('profile')->getClientOriginalExtension();
				$request->file('profile')->move(public_path($path), $fullName);
				$fileLink = $path . $fullName;
				// ($user->profile != null) ? (file_exists($user->profile) ? unlink($user->profile) : '') : '';
			} else {
				$fileLink = '';
			}

			if ($request->type == 'super admin') {
				
				$validator = \Validator::make(
					$request->all(),
					[
						'name' => 'required',
						'email' => 'required|email|unique:users',
						'password' => 'required|min:6',
					]
				);
				if ($validator->fails()) {
					$messages = $validator->getMessageBag();

					return redirect()->back()->with('error', $messages->first());
				}

				$user = new User();
				$user->first_name = $request->name;
				$user->email = $request->email;
				$user->password = \Hash::make($request->password);
				$user->phone_number = $request->phone_number;
				$user->ccm = $request->ccm;
				//  $user->whatsapp_number = $request->whatsapp_number;              
				$user->type = 'super admin';
				$user->lang = 'english';
				$user->subscription = 1;
				$user->parent_id = parentId();
				// $user->profile = $this->verifyAndStoreFile( $request, 'profile' ,  'owner', 'upload_image') ;
				$user->profile = $fileLink;
				$user->save();
				$userRole = Role::findByName('super admin');
				$user->assignRole($userRole);
				// Default Client Role
				//defaultClientCreate($user->id);
				//defaultTechnicianCreate($user->id);
				return redirect()->route('users.index')->with('success', __('User successfully created.'));
			} else {
        
				$validator = \Validator::make(
					$request->all(),
					[
						'name' => 'required',
						'email' => 'required|email|unique:users',
						'password' => 'required|min:6',
						'role' => 'required',
					]
				);
				if ($validator->fails()) {
					$messages = $validator->getMessageBag();

					return redirect()->back()->with('error', $messages->first());
				}



				$userRole = Role::findById($request->role);
				$user = new User();
				$user->first_name = $request->name;
				$user->email = $request->email;
				$user->ccm = $request->ccm;
				$user->phone_number = $request->phone_number;
				//  $user->whatsapp_number = $request->whatsapp_number;
				$user->password = \Hash::make($request->password);
				$user->type = $userRole->name;
				// $user->profile =$this->verifyAndStoreFile( $request, 'profile' ,  $userRole->name, 'upload_image') ;
				$user->profile = $fileLink;
				$user->lang = 'english';
				$user->parent_id = parentId();
				$user->save();

				$user->assignRole($userRole);

				return redirect()->route('users.index')->with('success', __('User successfully created.'));
			}
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function show()
	{
		$data['country_code'] = User::$country_code;
		$data['user'] = Auth::user();

		$skills = is_array(Auth::user()->skills)
			? Auth::user()->skills
			: json_decode(Auth::user()->skills, true);

		$skills = is_array($skills) ? $skills : (is_null($skills) ? [] : [(int)$skills]);

		$shift = is_array(Auth::user()->shift)
			? Auth::user()->shift
			: json_decode(Auth::user()->shift, true);

		$shift = is_array($shift) ? $shift : (is_null($shift) ? [] : [(int)$shift]);

		$data['skills'] = SkillGroup::whereIn('id', $skills)->get();
		$data['shifts'] = ShiftMaster::whereIn('id', $shift)->get();

		return view('dashboard.personal-info', $data);
	}

	public function editUser(Request $request)
	{
		$request->validate([
			'profile.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
		]);

		$user = User::find($request->id);
		$password = $request->filled('password') ? Hash::make($request->password) : $user->password;

		$path = "assets/image/";
		if ($request->hasFile('profile')) {
			$fullName = time() . '_' . uniqid() . '.' . $request->file('profile')->getClientOriginalExtension();
			$request->file('profile')->move(public_path($path), $fullName);
			$fileLink = $path . $fullName;

			($user->profile != null) ? (file_exists($user->profile) ? unlink($user->profile) : '') : '';
		} else {
			$fileLink = $user->profile;
		}

		User::where('id', $request->id)->update([
			'first_name' =>  $request->first_name,
			'last_name' =>  $request->last_name,
			'password' =>  $password,
			'profile' => $fileLink,
			'ccm' =>  $request->ccm,
			'phone_number' =>  $request->phone_number
		]);

		$clientDetail = ClientDetail::where('user_id', $request->id)->first();

		if ($clientDetail) {
			$clientDetail->update([
				'service_address' => $request->service_address,
			]);
		} else {
			if ($request->filled('service_address')) {
				ClientDetail::create([
					'user_id' => $request->id,
					'service_address' => $request->service_address,
				]);
			}
		}

		return redirect()->back()->with('success', 'User edit successfully');
	}

	public function edit($id)
	{
		$user = User::findOrFail($id);
		$userRoles = Role::where('parent_id', '=', parentId())->whereNotIn('name', ['client'])->get()->pluck('name', 'id');
		$ReportingManagers = User::whereIn('type', ['supervisor','admin','manager'])->get()->pluck('first_name', 'id');
		return view('user.edit', compact('user', 'userRoles', 'ReportingManagers'));
	}

	public function update(Request $request, $id)
	{




		if (\Auth::user()->can('edit user')) {
			if (\Auth::user()->type == 'super admin') {
				$user = User::findOrFail($id);

				$validator = \Validator::make(
					$request->all(),
					[
						'first_name' => 'required',
						'email' => 'required|email|unique:users,email,' . $id,

					]
				);
				if ($validator->fails()) {
					$messages = $validator->getMessageBag();

					return redirect()->back()->with('error', $messages->first());
				}

				$userData = $request->all();
				$user->fill($userData)->save();
				
				$userRole = Role::findById($request->role);
				//dd($user);
				//$user->roles()->detach();
				$user->syncRoles($userRole);
				//dd($user->type);
				$user->update(['type'=>$userRole->name]);
				//$user->save();
				
                // $user->assignRole($userRole);
				// update photo
				if ($request->has('profile')) {
					// Delete old photo
					if ($user->profile != '')
						$this->Delete_attachment('upload_image', $user->profile);


					$path = "assets/image/";
					if ($request->hasFile('profile')) {
						$fullName = time() . '_' . uniqid() . '.' . $request->file('profile')->getClientOriginalExtension();
						$request->file('profile')->move(public_path($path), $fullName);
						$fileLink = $path . $fullName;

						($user->profile != null) ? (file_exists($user->profile) ? unlink($user->profile) : '') : '';
					} else {
						$fileLink = $user->profile;
					}

					//Upload img
					$user->profile = $fileLink;
					$user->save();
				}

				return redirect()->route('users.index')->with('success', 'User successfully updated.');
			} else {


				$validator = \Validator::make(
					$request->all(),
					[
						'name' => 'required',
						'email' => 'required|email|unique:users,email,' . $id,
						'role' => 'required',
					]
				);
				if ($validator->fails()) {
					$messages = $validator->getMessageBag();

					return redirect()->back()->with('error', $messages->first());
				}

				$userRole = Role::findById($request->role);
				$user = User::findOrFail($id);
				$user->first_name = $request->name;
				$user->email = $request->email;
				$user->phone_number = $request->phone_number;
				$user->type = $userRole->name;
				// update photo
				if ($request->has('profile')) {
					// Delete old photo
					if ($user->profile != '')
						$this->Delete_attachment('upload_image', $user->profile);

					$path = "assets/image/";
					if ($request->hasFile('profile')) {
						$fullName = time() . '_' . uniqid() . '.' . $request->file('profile')->getClientOriginalExtension();
						$request->file('profile')->move(public_path($path), $fullName);
						$fileLink = $path . $fullName;

						($user->profile != null) ? (file_exists($user->profile) ? unlink($user->profile) : '') : '';
					} else {
						$fileLink = $user->profile;
					}


					//Upload img
					$user->profile = $fileLink;
				}
				$user->save();
				$user->assignRole($userRole);
				return redirect()->route('users.index')->with('success', 'User successfully updated.');
			}
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}


	public function destroy($id)
	{

		if (\Auth::user()->can('delete user')) {
			$user = User::findOrFail($id);
			$user->delete();

			return redirect()->route('users.index')->with('success', __('User successfully deleted.'));
		} else {
			return redirect()->back()->with('error', __('Permission denied.'));
		}
	}

	public function loggedHistory()
	{
		$ids = parentId();
		$authUser = \App\Models\User::find($ids);
		$subscription = \App\Models\Subscription::find($authUser->subscription);

		if (\Auth::user()->can('manage logged history') && $subscription->enabled_logged_history == 1) {
			$histories = LoggedHistory::where('parent_id', parentId())->get();
			return view('logged_history.index', compact('histories'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function loggedHistoryShow($id)
	{
		if (\Auth::user()->can('manage logged history')) {
			$histories = LoggedHistory::find($id);
			return view('logged_history.show', compact('histories'));
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function loggedHistoryDestroy($id)
	{
		if (\Auth::user()->can('delete logged history')) {
			$histories = LoggedHistory::find($id);
			$histories->delete();
			return redirect()->back()->with('success', 'Logged history succefully deleted.');
		} else {
			return redirect()->back()->with('error', __('Permission Denied.'));
		}
	}

	public function getCountry(Request $request)
	{
		$user = User::find($request->id);


		return response()->json(['data' => $user->ccm]);
	}
}
