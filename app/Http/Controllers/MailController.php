<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    public function mail(Request $request)
    {
//       try {
//           $mail = mail($request->email, $request->subject, $request->text);
//       } catch (\Exception $exception) {
//           return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
//       }

        $mail = Mail::to($request->email->send('', ''));
        return response()->json($mail)->setStatusCode(200, 'Successful send');
    }
}
