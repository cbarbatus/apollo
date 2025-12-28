<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Element;
use App\Models\Member;
use App\Models\Ritual;
use App\Models\User;
use Config;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\View\View; // Use Contracts\View\View for strict analysis
use Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GroveController extends Controller
{
    public function bylaws(): BinaryFileResponse|RedirectResponse
    {
        if (Auth::check()) {
            $fileName = 'bylaws.pdf';
            $location = storage_path('app/grove');
            $fullName = $location.'/'.$fileName;

            return response()->file($fullName);
        }

        return redirect('/');
    }

    public function pay(): View|RedirectResponse
    {
        if (Auth::check()) {
            return view('grove.pay');
        }

        return redirect('/');

    }

    public function contact(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            // FIX: Check for user instance to inform static analysis about hasRole()
            if ($user && $user instanceof User) {
                if ($user->hasRole('admin')) {
                    return view('grove.contact');
                }
            }
        }

        return redirect('/');
    }

    public function contactus(): View
    {
        return view('grove.contactus');
    }

    public function thanks(): View
    {
        return view('grove.thanks');
    }



    public function upload(): View|RedirectResponse
    {

        if (Auth::check()) {
            $user = Auth::user();
            // FIX: Check for user instance to inform static analysis about hasRole()
            if ($user && $user instanceof User) {
                if ($user->hasRole('admin')) {
                    return view('grove.upload');
                }
            }
        }

        return redirect('/');

    }


    public function uploadFile(Request $request): RedirectResponse
    {
        $file = $request->file('file');
        if (is_null($file)) {
            Session::flash('warning', 'No File Selected.');

            return redirect('grove/upload');
        }
        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();

        // FIX: Store visibility from input() and use this variable
        $visibility = $request->input('visibility');

        // Valid File Extensions
        if ($visibility == 'liturgy') {
            $valid_extension = ['htm'];
        } elseif ($visibility == 'images') {
            $valid_extension = ['jpg'];
        } else {
            $valid_extension = ['pdf', 'docx'];
        }

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {
            // Check file size
            if ($fileSize <= $maxFileSize) {
                // File upload location
                if ($visibility == 'public') {
                    $location = 'contents';
                } elseif ($visibility == 'liturgy') {
                    $location = 'liturgy';
                } elseif ($visibility == 'images') {
                    $location = 'img';
                } else {
                    $location = storage_path('app/grove');
                }

                // Upload file
                $file->move($location, $filename);

                if ($location == 'liturgy') {
                    $shortname = substr($filename, 0, strlen($filename) - 4);
                    rename(public_path('/liturgy/'.$filename), public_path('/liturgy/'.$shortname));
                }

                Session::flash('message', 'Upload Successful.');
            } else {
                Session::flash('warning', 'File too large. File must be less than 2MB.');
            }
        } else {
            Session::flash('warning', 'Invalid File Extension.');
        }

        // Redirect to index
        return redirect('grove/upload');
    }

        /* public allowed to donate */
    public function donate(): View
    {
        return view('grove.donate');
    }


}
