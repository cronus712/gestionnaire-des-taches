<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SampleMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function index(User $user)
    {   $user = User::find($user->id);

        $content = [
            'subject' => 'This is the mail subject',
            'body' => 'This is the email body of how to send email from laravel 10 with mailtrap.'
        ];

        Mail::to($user->email)->send(new SampleMail($content));
        // Mail::to($content->user->email)->send(new SampleMail($content));
        return "Email has been sent.";

    }
}
