<?php



namespace App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\SignUpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Resources\PublicUsersResource;
class AuthApiController extends BaseApiController
{
    
    private function generateTokens($user)
    {
        $accessTokenResult = $user->createToken('Personal Access Token');
        $accessToken = $accessTokenResult->token;
        $accessToken->expires_at = Carbon::now('Asia/Dubai')->addMinutes(60); // 1 hour expiration
        $accessToken->save();
    
        $refreshTokenResult = $user->createToken('Refresh Token');
        $refreshToken = $refreshTokenResult->token;
        $refreshToken->expires_at = Carbon::now('Asia/Dubai')->addMonths(3); // 3 months expiration
        $refreshToken->save();
    
        return [
            'access_token' => $accessTokenResult->accessToken,
            'access_token_expires_at' => Carbon::parse($accessToken->expires_at)->setTimezone('Asia/Dubai')->format('Y-m-d H:i:s'),
            'refresh_token' => $refreshTokenResult->accessToken,
            'refresh_token_expires_at' => Carbon::parse($refreshToken->expires_at)->setTimezone('Asia/Dubai')->format('Y-m-d H:i:s'),
        ];
    }
    
    
        public function signUp(SignUpRequest $request)
        {
            try {
                DB::beginTransaction();
                $validated = $request->validated();
    
                $user = User::create([
                    'first_name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt($validated['password']),
                ]);
    
                $tokens = $this->generateTokens($user);
    
                DB::commit();
                return $this->sendResponse(['user' => new PublicUsersResource($user), 'tokens' => $tokens]);
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->sendError("Server Error. Please try again later.");
            }
        }
    
        public function login(LoginRequest $request)
        {
            try {
               
                $validate = $request->validated();
                $user = User::where('email', $validate['email'])->first();
    
                if (!$user || !Hash::check($request->password, $user->password)) {
                    return $this->sendError('The email or password is incorrect.');
                }
    
                $tokens = $this->generateTokens($user);
    
                return $this->sendResponse(['user' => new PublicUsersResource($user), 'tokens' => $tokens], 'Login successful');
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage());
            }
        }
    
        public function refresh(Request $request)
        {
            try {
                $user = $request->user();
    
                if (!$user) {
                    return $this->sendError('Invalid token. Please authenticate.', 401);
                }
    
                $tokens = $this->generateTokens($user);
    
                return $this->sendResponse(['tokens' => $tokens], 'Token refreshed successfully');
            } catch (\Exception $e) {
                return $this->sendError('Server Error. Please try again later.', 500);
            }
        }
    
    public function logout(Request $request)
{
    try {
        $user = $request->user();

        if ($user && $user->token()) {
            $user->token()->revoke();
            return $this->sendResponse([], 'Logout Successful.');
        } else {
            return $this->sendError('No active session or token found.', 401);
        }
    } catch (Exception $e) {
        return $this->sendError("Server Error. Please try again later.");
    }

   
}


public function testmessage(Request $request)
{
    // You can access request data if needed
    // $data = $request->input('some_key');

    return response()->json([
        'status' => true,
        'message' => 'This is a test message from POST request',
    ], 200);

}
}