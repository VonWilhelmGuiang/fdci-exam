<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

//models
use App\Models\Account;
use App\Helpers\AuthHelper;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
        *Login 
        *@OA\POST(
        *   path="/api/auth/login",
        *   tags={"Auth"},
        *   summary="Authenticate user and generate JWT token",
        *   @OA\MediaType(mediaType="multipart/form-data"),
        *      @OA\RequestBody(
        *          required=true,
        *          @OA\MediaType(
        *              mediaType="multipart/form-data",
        *              @OA\Schema(
        *                  required={"email","password"},
        *                  @OA\Property(
        *                      property="email",
        *                      type="string",
        *                      description="Email"
        *                  ),
        *                  @OA\Property(
        *                      property="password",
        *                      type="string",
        *                      description="Password"
        *                  ),
        *             )
        *         ),
        *   ),  
        *   @OA\Response(response="200", description="Login successful", 
        *       @OA\JsonContent(
        *           type="object",
        *           @OA\Property(
        *               type="string",
        *               description="token",
        *               property="token",
        *           ),
        *           @OA\Property(
        *               type="string",
        *               description="message",
        *               property="message",
        *           ),
        *           @OA\Property(
        *               type="boolean",
        *               description="success",
        *               property="success",
        *           )
        *       )
        *   ),
        *   @OA\Response(response="401", description="Invalid credentials"),
        *   @OA\Response(response="422", description="Required Fields are Empty")
        *)
    */
    public function login(Request $request)
    {
        
        $valid_data = $request->validate([
            'email' => ['required','string','email','exists:accounts,email'],
            'password' => ['required','string']
        ],[
            'email.exists' => 'Account Email address does not exist'
        ]); //returns errors if not valid

        $credentials = $request->only('email', 'password');
        $login = new AuthHelper();
        $login_user_token = $login->create_token($credentials);
        if($login_user_token){
            return response()->json(['message' => 'Login successful' , 'token'=> $login_user_token, 'success'=> true],200);
        }else{
            return response()->json(['message' => 'Invalid Credentials', 'success'=>false], 401);
        }
    }

    /**
     * Verify
     * @OA\POST(
     *      path="/api/auth/verify",
     *      tags={"Auth"},
     *      summary="Check if user is logged in",
     *      security = {
     *          {"sanctum":{}}
     *      },
     * @OA\Response(response="200", description="Logged In"),
     * @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function verify(Request $request)
    {
        if(auth('sanctum')->check()){
            return response()->json(['message' => 'Logged In','user_data' => auth('sanctum')->user()], 200);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    /**
     * Register an Account
     * @OA\POST(
     *    path="/api/auth/register",
     *    tags={"Auth"},
     *    summary="Register an Account",
     *    @OA\MediaType(mediaType="multipart/form-data"),
     *    @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              required={"email","password","password_confirmation","name"},
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  description="Account Email",
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  description="Account Password"
     *              ),
     *              @OA\Property(
     *                  property="password_confirmation",
     *                  type="string",
     *                  description="Confirm Password"
     *              ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  description="User's Full Name"
     *              ),
     *          )
     *       )
     *    ),
     *    @OA\Response(response="201", description="Account Created",
     *       @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean"
     *          ),
     *          @OA\Property(
     *               type="string",
     *               description="token",
     *               property="token",
     *           ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          )
     *       )
     *    ),
     *   @OA\Response(response="400", description="Bad Request",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean",
     *              default=false
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string",
     *          )
     *       )
     *   ),
     *   @OA\Response(response="422", description="Unprocessable Content")
     * )
     */
    public function register(Request $request){
        //returns error if data is not valid
        $validate = $request->validate([
            'email'=> ['required','string','email','unique:accounts,email'],
            'name' => ['required','string'],
            'password' => ['required','confirmed','min:8']
        ]);
        $account_data = $request->only("email","password","name");
        $credentials = $request->only("email","password");

        //create data
        $create_account = new Account();
        $create_account->email = $account_data['email']; 
        $create_account->password = Hash::make($account_data['password']); 
        $create_account->name = $account_data['name']; 
        //login user
        if($create_account->save()){
            $login = new AuthHelper();
            $login_user_token = $login->create_token($credentials);
            if($login_user_token){
                return response()->json(['success'=>true, 'token' => $login_user_token, 'message' => 'User Created.'],200);
            }else{
                return  response()->json(['success'=>true, 'token'=>'','message' => 'User Created. Please proceed to login Page.'],200);
            }
        }else{
            return response()->json(['success'=>false,'message'=>'An error has occured creating new account.'],200);
        }
    }

    /**
     * User Logout
     * @OA\DELETE(
     *   path="/api/auth/logout",
     *   tags={"Auth"},
     *   summary="Check if user is logged in",
     *   security = {
     *       {"sanctum":{}}
     *   },
     *   @OA\Response(response="200", description="Logout successful", 
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property(
     *               type="boolean",
     *               description="success",
     *               property="success",
     *           )
     *       )
     *   ),
     *   @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function logout(Request $request){
        if($request->user()->currentAccessToken()->delete()){
            return response()->json(['success'=>true], 200);
        }else{
            return response()->json(['success'=>false], 200);
        }
    }

}