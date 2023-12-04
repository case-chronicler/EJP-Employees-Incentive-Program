<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\User;
use App\Models\Invite;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\EmployeeController;
use Error;
use Exception;


class RegisteredUserController extends Controller
{
    private function getInvitedUserByInviteLink ($invite_link) {
        $invitedUser = Invite::where('invite_link_ref', $invite_link)->limit(1)->get();

        return $invitedUser;
    }

    private function registerInvitedUser (Request $request, $invitedUser_positions) {    

        try {
            $invitedUser_invite_link_ref = htmlspecialchars($request->input('invite_link'));

            //code...
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);


            $newEmployee = $user->employee()->create([
                'balance' => 0.00,
                'has_elevated_permission' => 0,
                "employee_public_ref" => $invitedUser_invite_link_ref
            ]);

            foreach ($invitedUser_positions as $key => $value) {
                $position = Position::find($invitedUser_positions[$key]);

                $employeeController = new EmployeeController();
                
                $employeeController->addEmployeeRole($newEmployee, $position);
            } 

            $user->update(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                ],
                [
                    'is_employee' => 1
                ]
                );

            // DB::rollBack();
            // die();

            DB::table('invites')
              ->where('invite_link_ref', $invitedUser_invite_link_ref)
              ->update(
                [
                    'invite_status' => 'accepted',
                ]
            );                                  

            DB::commit(); 

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        } catch (Exception $th) {
            DB::rollBack();
            throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => $th->getMessage()
                ]);
        }
    }


    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $invite_link = $request->query('invite_link');
        $invite_link_is_valid = ($invite_link) ? true : false;

        $invitedUser = $this->getInvitedUserByInviteLink ($invite_link) ;
        $invitedUser_email = '';
        $invitedUser_invite_already_processed = '';

        if(count($invitedUser)){

            foreach ($invitedUser as $user) {
                $invitedUser_email = $user->invite_email;   
                
                if($user->invite_status !== 'pending'){                
                    $invitedUser_invite_already_processed = 'This invite has already being processed!';
                }                 
            }            
                    
            $invite_link_is_valid = true;
        }else{
            $invite_link_is_valid = false;
        } 

        if($invitedUser_invite_already_processed === 'This invite has already being processed!'){
            $invite_link_is_valid = false;
        }
        
        return Inertia::render('Auth/Register', [
            'invite_link_is_valid' => $invite_link_is_valid,
            'invite_link' => $invite_link,
            'invitedUser_email' => $invitedUser_email,
            'invitedUser_invite_already_processed' => $invitedUser_invite_already_processed
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $elevatedUsers = config('elevated_user.emails');
        
        $userIsElevated = false;

        for ($i=0; $i < count($elevatedUsers) ; $i++) { 
            if($elevatedUsers[$i] === $request->input('email')){
                $userIsElevated = true;
                break;
            }
        }

        if($userIsElevated){
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_employee' => 1
            ]);

            $newEmployee = $user->employee()->create([
                'balance' => 0.00,
                'has_elevated_permission' => 1
            ]);
            
            $position = Position::where("position_name", "Attorney")->first(); // Attorney

            $newEmployee->role()->create([
                "position_id" => $position->position_id,
                'start_date' => now(),
                'is_active' => true,
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        }else{
            $request->validate([
                'name' => 'required|string|max:255',
                'invite_link' => 'required|string|exists:invites,invite_link_ref',
                'email' => 'required|string|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $invitedUser = Invite::where('invite_link_ref', htmlspecialchars($request->input('invite_link')))->limit(1)->first();

            if(!$invitedUser){                
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => 'invalid invite link!'
                ]);
            }

            if($invitedUser->invite_status !== 'pending'){                
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'general' => 'This invite has already being processed!'
                ]);
            }
            
            $invitedUser_email = $invitedUser->invite_email;
            $invitedUser_positions = $invitedUser->positions_assigned;
            $invitedUser_invite_link_ref = $invitedUser->invite_link_ref;

            $invitedUser_email = trim($invitedUser_email) ?? '';
            $invitedUser_positions = json_decode($invitedUser_positions, JSON_OBJECT_AS_ARRAY) ?? [];

            if($request->input('email') !== $invitedUser_email){
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => 'This email was not invited with the invite link used'
                ]);
            }

            

            return $this->registerInvitedUser($request, $invitedUser_positions);
        }


        
    }
}
