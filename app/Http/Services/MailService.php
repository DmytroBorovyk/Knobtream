<?php

namespace App\Http\Services;


use App\Models\Email;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function send($data, $user_id): bool
    {
        $user = User::findOrFail($user_id);

        try {
            Mail::send("emails.new-review", $data, function($message) use ($user) {
                $message->to($user->email, $user->name)->subject("New review");
            });
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function check($vacancy)
    {
        $letter = Email::where('vacancy_id', $vacancy->id)->orderByDesc('created_at')->first();

        if(!$letter || $letter->created_at->lte(Carbon::now()->subHour())){
            $data = [
                'job_title' => $vacancy->title,
                'user_name' => Auth::user()->name,
                'responses_count' => $vacancy->response_count,
                'date' => Carbon::now()
            ];

            if($this->send($data, $vacancy->user_id)){
                $email = new Email();
                $email->vacancy_id = $vacancy->id;
                $email->save();
            }
        }
    }
}
