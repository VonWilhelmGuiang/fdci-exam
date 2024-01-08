<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

use App\Models\Contact;
use App\Helpers\ContactHelper;


class ContactController extends BaseController
{
    private $account_id = null;
    function __construct(){
        $this->account_id = auth('sanctum')->user()->account_id??0;
    }
    /**
     * Contacts
     * @OA\GET(
     *   path = "/api/contact/view",
     *   tags={"Contacts"},
     *   summary="Get User Contacts",
     *   security = {
     *      {"sanctum":{}}
     *   },
     *   @OA\Parameter(
     *      in="query",
     *      required=false,
     *      name="keyword",
     *      description="Keyword to be searched",
     *      @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *      in="query",
     *      required=true,
     *      name="offset",
     *      description="Offset",
     *      @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *      in="query",
     *      required=true,
     *      name="limit",
     *      description="limit",
     *      @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="200", description="Invalid credentials",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="contact_list",
     *              description="Contact List",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                  type="object",
     *                  format="id",
     *                  @OA\Property(
     *                      property="contact_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="company",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  )
     *              ),
     *          ),
     *          @OA\Property(
     *              property="count",
     *              type="integer"
     *          )
     *      )
     *   ),
     *   @OA\Response(response="401", description="Unauthenticated")
     * )
     */
    public function view(Request $request)
    {   
        $limit = $request->query('limit');
        $offset = max($request->query('offset')-1,0)* $limit;
        $keyword =  $request->query('keyword');
        $contacts=[];
        $count=0;
        $get_columns = ['contact_id','name','company','phone','email'];

        if($keyword === NULL || $keyword === ""){
            $contacts = Contact::where('account_id',$this->account_id)->where('active',1)->offset($offset )->limit($limit)->get($get_columns);
            $count = Contact::where('account_id',$this->account_id)->where('active',1)->count();
        }
        else{
            $contacts = DB::table('contacts')
                ->where('account_id','=',$this->account_id)
                ->where('active','=',1)
                ->where(function($query) use ($keyword){
                    $query->where('name','like',"%$keyword%")
                    ->orWhere('company','like',"%$keyword%")
                    ->orWhere('phone','like',"%$keyword%")
                    ->orWhere('email','like',"%$keyword%");
                })
                ->offset($offset)
                ->limit($limit)
                ->get($get_columns);
                
                //for count
                $count = DB::table('contacts')
                ->where('account_id','=',$this->account_id)
                ->where('active','=',1)
                ->where(function($query) use ($keyword){
                    $query->where('name','like',"%$keyword%")
                    ->orWhere('company','like',"%$keyword%")
                    ->orWhere('phone','like',"%$keyword%")
                    ->orWhere('email','like',"%$keyword%");
                })->count();
        }
        

        return response()->json(['contact_list' => $contacts, 'count'=> $count], 200);
    }

    /**
     * Create New Contact
     * @OA\POST(
     *   path="/api/contact/create",
     *   tags={"Contacts"},
     *   summary="Create new Contact",
     *   security={
     *       {"sanctum":{}}
     *   },
     *   @OA\MediaType(mediaType="multipart/form-data"),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Full Name of Contact"
     *                  ),
     *                  @OA\Property(
     *                      property="company",
     *                      type="string",
     *                      description="Company"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="string",
     *                      description="Phone"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="Email"
     *                  )
     *              )
     *          )
     *      ),
     *   @OA\Response(response=201,description="New Contact Created",
     *       @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean"
     *          ),
     *          @OA\Property(
     *              property="contact_id",
     *              type="integer"
     *          )
     *      )
     *   ),
     *   @OA\Response(response="400", description="Bad Request",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean",
     *              default="false"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string"
     *          )
     *      )
     *   ),
     *   @OA\Response(response="409", description="Data Conflict",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean",
     *              default="false"
     *          ),
     *          @OA\Property(
     *              property="message",
     *              type="string"
     *          )
     *      )
     *   ),
     *   @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function create(Request $request) 
    {   
        $contact_data = $request->only('name','company','phone','email');
        $validation = [
            'name' => ['required','string'],
            //'company' => ['required','string'],
            //'phone' => ['required','string'],
            //'email' => ['required','string','email','unique:contacts,email']
            //'email' => ['required','string','email']
        ];
        
        //returns errors and http code if not valid
        $contact_helper = new ContactHelper();
        $contact_validation = $contact_helper->is_valid_contact($contact_data,$validation);
        
        if($contact_validation === true){
            $new_contact = new Contact();
            $new_contact->account_id = $this->account_id;
            $new_contact->name = $request->name;
            $new_contact->company = $request->company;
            $new_contact->phone = $request->phone;
            $new_contact->email = $request->email;
            $new_contact->active = 1;
            if($new_contact->save()){
                return response()->json(['success' => true, 'contact_id'=>$new_contact->contact_id ], 200);
            }else{
                return response()->json(['success' => false], 200);
            }
            
        }else{
            return response()->json([ 'success' => false, 'message' => $contact_validation['message'] ], $contact_validation['code'] );
        }
    }

    /**
     * Update contact
     * @OA\PUT(
     *   path="/api/contact/update/{contact_id}",
     *   tags={"Contacts"},
     *   summary="Update Contact",
     *   security={
     *      {"sanctum":{}}
     *   },
     *   @OA\Parameter(
     *      in="path",
     *      name="contact_id",
     *      description="Contact Id",
     *      required=true,
     *      @OA\Schema(type="integer")
     *   ),
     *   @OA\MediaType(mediaType="multipart/form-data"),
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="name",
     *              type="string",
     *              description="Full Name of Contact"
     *          ),
     *          @OA\Property(
     *              property="company",
     *              type="string",
     *              description="Company"
     *          ),
     *          @OA\Property(
     *              property="phone",
     *              type="string",
     *              description="Phone"
     *          ),
     *          @OA\Property(
     *              property="email",
     *              type="string",
     *              description="Email"
     *          )
     *      )
     *   ),
     *   @OA\Response(response=200,description="New Contact Created",
     *       @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean"
     *          ),
     *      )
     *   ),
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
     *      )
     *   ),
     *   @OA\Response(response="401", description="Unauthorized"),
     *   @OA\Response(response="409", description="Data Conflict"),
     * )
     */

    public function update(Request $request)
    {
        $contact_id = (int)$request->route('contact_id');
        $contact_data = $request->only('name','company','phone','email');
        $contact_data = [...$contact_data, 'contact_id'=>$contact_id];

        $contact_helper = new ContactHelper();
        $contact_validation = [
            'contact_id' => 
            [
                'required',
                'integer', 
                function($attribute, $value, $fail)  {
                    $contact_exist = Contact::where('account_id', $this->account_id)->where('contact_id',$value)->where('active',1)->exists();
                    if (!$contact_exist) {
                        $fail('Contact does not exists.');
                    }
                }
            ],
        ];
        $check_contact_exist = $contact_helper->is_valid_contact($contact_data,$contact_validation);
        if($check_contact_exist === true){
            $contact_data_validation = [
                'name' => ['required','string'],
                //'company' => ['required','string'],
                //'phone' => ['required','string'],
                //'email' => ['required','string','email','unique:contacts,email']
                //'email' => ['required','string','email']
            ];
            $is_valid_contact = $contact_helper->is_valid_contact($contact_data,$contact_data_validation);
            if($is_valid_contact === true){
                $create_contact = Contact::find($contact_id);
                $create_contact->name = $contact_data['name'];
                $create_contact->company = $contact_data['company'];
                $create_contact->phone = $contact_data['phone'];
                $create_contact->email = $contact_data['email'];
                
                if($create_contact->save()){
                    return response()->json(['success' => true],200);
                }else{
                    return response()->json(['success' => false, 'message'=> 'An error has occured updating contact.'],400);
                }
            }else{
                return response()->json([ 'success' => false, 'message' => $is_valid_contact['message'] ], $is_valid_contact['code'] );
            }
        }else{
            return response()->json([ 'success' => false, 'message' => $check_contact_exist['message'] ], $check_contact_exist['code'] );
        }        
    }

    /**
     * Soft Delete a contact
     * @OA\DELETE(
     *    path="/api/contact/delete/{contact_id}",
     *    tags={"Contacts"},
     *    security = {
     *      {"sanctum":{}}
     *    },
     *    summary = "Delete a Contact",
     *   @OA\Parameter(
     *      name="contact_id",
     *      in="path",
     *      description="Contact ID",
     *      required=true,
     *      @OA\Schema(type="integer")   
     *   ),
     *   @OA\Response(response="200", description="Contact Deleted",
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(
     *              property="success",
     *              type="boolean"
     *          ) 
     *      )
     *   ),
     *   @OA\Response(response="400",description="Bad Request",
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
     *      )
     *   ),
     *   @OA\Response(response="401",description="Unauthorized"),
     * )
     */
    public function delete(Request $request){
        $contact_id = (int)$request->route('contact_id');
        
        $contact_data = ['contact_id'=>$contact_id];
        $contact_validation = [
            'contact_id' => 
            [
                'required',
                'integer', 
                function($attribute, $value, $fail)  {
                    $contact_exist = Contact::where('account_id', $this->account_id)->where('contact_id',$value)->where('active',1)->exists();
                    if (!$contact_exist) {
                        $fail('Contact does not exists.');
                    }
                }
            ],
        ];
        $verify_contact = new ContactHelper();
        $valid_contact = $verify_contact->is_valid_contact($contact_data,$contact_validation);
        if($valid_contact === true){
            $delete_contact = Contact::find($contact_id);
            $delete_contact->active = 0;
            if($delete_contact->save()){
                return response()->json(['success'=>true],200);
            }else{
                return response()->json(['success'=>false,'message'=>'An error has occured deleting contact.'],400);
            }
        }else{
            return response()->json(['success'=>false,'message'=>'Contact ID is not found'],400);
        }
    }
}
