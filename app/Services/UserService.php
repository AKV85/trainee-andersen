<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AccountDeletionNotification;
use Barryvdh\DomPDF\PDF;

class UserService
{
    protected PDF $pdf;

    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * Store a new user.
     *
     * @param array $data The user data.
     * @return User The created user.
     */
    public function store(array $data): User
    {
        // Check if email and password are present in the input data
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new \InvalidArgumentException('Email and password are required');
        }
        // Create a new user using the provided data
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->fill($data);

        $user->save();

        return $user;
    }

    public function destroy(User $user)
    {
        $user->status = User::INACTIVE;
        $user->save();

        $data = ['user' => $user];
        $pdf =$this->pdf->loadView('emails.account_deletion', $data);
        $dompdf = $pdf->getDomPDF();

        $filename = "{$user->name}_delete.pdf";
        $path = storage_path("app/pdf/{$filename}");
        $pdf->save($path);

        $user->notify(new AccountDeletionNotification($dompdf));
    }
}
