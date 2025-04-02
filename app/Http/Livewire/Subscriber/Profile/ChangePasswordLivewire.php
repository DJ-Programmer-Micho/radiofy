<?php

namespace App\Http\Livewire\Subscriber\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordLivewire extends Component
{
    public $oldPassword;
    public $newPassword;
    public $newPassword_confirmation;

    protected $rules = [
        'oldPassword' => 'required',
        'newPassword' => [
            'required',
            'string',
            'min:10',
            'regex:/^(?=.*[A-Za-z])(?=.*[\d\W]).+$/',
            'confirmed'
        ],
    ];

    public function updated($propertyName)
    {
        // Validate only the updated field for real-time feedback.
        $this->validateOnly($propertyName);
    }

    public function updatePassword()
    {
        try {
            $this->validate();

            $subscriber = Auth::guard('subscriber')->user();
            if (!Hash::check($this->oldPassword, $subscriber->password)) {
                $this->addError('oldPassword', 'The old password is incorrect.');
                return;
            }
    
            $subscriber->password = Hash::make($this->newPassword);
            $subscriber->save();
    
            $this->dispatchBrowserEvent('alert', [
                'type' => 'success',
                'message' => __('Password changed successfully.')
            ]);
    
            // Reset fields after successful update.
            $this->reset(['oldPassword', 'newPassword', 'newPassword_confirmation']);
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'Error',
                'message' => __('Something went wrong')
            ]);
        }
    }

    public function render()
    {
        return view('subscriber.pages.profile.changePassword');
    }
}
