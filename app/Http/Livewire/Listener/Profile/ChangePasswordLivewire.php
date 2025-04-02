<?php

namespace App\Http\Livewire\Listener\Profile;

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

            $listener = Auth::guard('listener')->user();
            if (!Hash::check($this->oldPassword, $listener->password)) {
                $this->addError('oldPassword', 'The old password is incorrect.');
                return;
            }
    
            $listener->password = Hash::make($this->newPassword);
            $listener->save();
    
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
        return view('listener.pages.profile.changePassword');
    }
}
