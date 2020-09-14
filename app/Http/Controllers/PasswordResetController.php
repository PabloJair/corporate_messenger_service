<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CodeResponse;
use App\Models\ResponseModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);


        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json(new ResponseModel(CodeResponse::ERROR,"No pudimos encontrar el correo que ingresaste",null,null), 200);

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => substr(Str::uuid()->toString(),0,6)
             ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"Te enviamos un correo con un codigo","1",null), 200);

    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Tu token ya es invalido",null,null), 200);

        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Tu token vencio",null,null), 200);
        }
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"Tu password ha sido cambiado",null,null), 200);

    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {


        if(Validator::Make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string',
            'token' => 'required|string'
        ])->fails()){
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Tu informacion no es valida","",null), 200);

        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Tu token ya es invalido","2",null), 200);

        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json(new ResponseModel(CodeResponse::ERROR,"Tenemos un error al encuentrar tu información, contacta al administrador","2",null), 200);

        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json(new ResponseModel(CodeResponse::SUCCESS,"Tu password ha sido cambiado","2",null), 200);

    }
}
