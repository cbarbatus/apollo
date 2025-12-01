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

    public function schedule(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            // FIX: Check for user instance to inform static analysis about hasRole()
            if ($user && $user instanceof User) {
                if ($user->hasRole(['admin', 'SeniorDruid'])) {
                    /* get id of "Schedule" element */
                    $element = Element::where('name', '=', 'Schedule')
                        ->first();

                    return view('grove.schedule', ['element' => $element]);
                }
            }
        }

        return redirect('/');
    }

    public function schedupdt(int $id, Request $request): RedirectResponse
    {
        $element = Element::find($id);
        /** @var \App\Models\Element $element */

        // FIX: Replaced request('item') with $request->input() for type safety
        $item = $request->input('item');
        $element->item = ($item === null) ? '' : (string) $item;
        $element->save();

        return redirect('/');
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

    public function uploadlit(int $id): View|RedirectResponse
    {
        $ritual = Ritual::findOrFail($id);
        /** @var \App\Models\Ritual $ritual */

        $litname = $ritual->year.'_'.$ritual->name;
        if (Auth::check()) {
            $user = Auth::user();
            // FIX: Check for user instance to inform static analysis about hasRole()
            if ($user && $user instanceof User) {
                if ($user->hasRole(['admin', 'SeniorDruid'])) {
                    $ritual->liturgy_base = $litname;
                    $ritual->save();
                }
            }

            return view('grove.uploadlit', compact(['litname', 'id']));
        }

        return redirect('/');

    }

    /* upload announcement picture file */
    public function uploadpic(int $id): View|RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);
        /** @var \App\Models\Announcement $announcement */

        $picname = $announcement->year.'_'.$announcement->name.'.jpg';

        $announcement->picture_file = $picname;
        $announcement->save();

        if (Auth::check()) {
            $user = Auth::user();
            // FIX: Check for user instance to inform static analysis about hasRole()
            if ($user && $user instanceof User) {
                if ($user->hasRole(['admin', 'SeniorDruid'])) {
                    return view('grove.uploadpic', compact('picname'));
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

    public function litfile(Request $request): RedirectResponse
    {
        $id = (int) $request->input('ritid', 0);
        $file = $request->file('file');
        if (is_null($file)) {
            Session::flash('warning', 'No File Selected.');

            return redirect("/rituals/$id");
        }

        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        // FIX: Ensure litfile is retrieved via input()
        $litfile = $request->input('litfile').'.'.$extension;

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if ($extension == 'htm') {
            // Check file size
            if ($fileSize <= $maxFileSize) {

                // Upload file
                $file->move('liturgy', $filename);
                rename(public_path('/liturgy/' . $filename), public_path('/liturgy/' . $litfile));
                Session::flash('message', 'Upload "'. $litfile .'" Successful.');

            } else {
                Session::flash('warning', 'File too large. File must be less than 2MB.');
            }
        } elseif ($extension == 'docx') {
            // Check file size
            if ($fileSize <= $maxFileSize) {

                // Upload file
                $location = storage_path('app/grove');
                $file->move($location, $filename);
//   var_dump($location, $filename, $extension, $litfile, $location);   exit();
                rename($location . '/'. $filename, $location . '/'.  $litfile);
                Session::flash('message', 'Upload "'. $litfile .'" Successful.');

            } else {
                Session::flash('warning', 'File too large. File must be less than 2MB.');
            }

        } else {
            Session::flash('warning', 'Invalid File Extension.');
        }

        // Redirect to index
        return redirect("/rituals/$id");
    }

    public function picfile(Request $request): RedirectResponse
    {
        $file = $request->file('file');
        if (is_null($file)) {
            Session::flash('warning', 'No File Selected.');

            return redirect('/announcements');
        }

        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        // FIX: Ensure picfile is retrieved via input()
        $picfile = $request->input('picfile');

        // 2MB in Bytes
        $maxFileSize = 2097152;

        // Check file extension
        if ($extension == 'jpg') {
            // Check file size
            if ($fileSize <= $maxFileSize) {

                // Upload file

                if (file_exists (public_path('/img/'.$picfile)))
                    rename(public_path('/img/'.$picfile), public_path('/img/'.$picfile.'.old'));

                $file->move('img', $filename);
                rename(public_path('/img/'.$filename), public_path('/img/'.$picfile));

                Session::flash('message', 'Upload Successful.');

            } else {
                Session::flash('warning', 'File too large. File must be less than 2MB.');
            }
        } else {
            Session::flash('warning', 'Invalid File Extension.');
        }

        // Redirect to index
        return redirect('/announcements');
    }

    /* public allowed to donate */
    public function donate(): View
    {
        return view('grove.donate');
    }

    /* ************************************************************** */

    /* hacks for specific internal testing or setup */

    /**
     * Setup user roles and permissions.
     * @return mixed
     */
    public function setup(): mixed
    {
        // FIX: Explicitly call query() to satisfy static analysis for Eloquent methods
        $roles = Role::query()->count();
        if ($roles == 0) {
            $role = Role::create(['name' => 'member']);
        }
        if ($roles == 1) {
            $role = Role::create(['name' => 'admin']);
        }

        $permissions = Permission::all();
        $users = Auth::user();

        $members = Member::whereIn('category', ['Elder', 'Member', 'Elder*', 'Member*'])
            ->where('status', '=', 'current')
            ->orderBy('first_name')->orderBy('last_name')
            ->get();
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $members */

        foreach ($members as $member) {
            /** @var \App\Models\Member $member */
            if ($member->user_id == 0) {
                $user = new User;
                /** @var \App\Models\User $user */
                $user->name = $member->first_name.' '.$member->last_name;
                $user->email = $member->email;
                $user->password = '';
                $user->assignRole('member');
                $user->save();
                $member->user_id = $user->id;
                $member->save();
            }
        }

        $mike = Member::find(59);
        /** @var \App\Models\Member $mike */
        $user_id = $mike->user_id;
        $user = User::find($user_id);
        /** @var \App\Models\User $user */
        $user->assignRole('admin');
        dd('it is mikie!', $user);
    }

    public function old_hack1(): RedirectResponse
    {
        /**
         * Do some more foolery with things.
         */
        $permission = Permission::create(['name' => 'change all']);
        $permission = Permission::create(['name' => 'see all']);

        $role = Role::findByName('member');
        /** @var \Spatie\Permission\Contracts\Role $role */
        $role->givePermissionTo('see private');
        $role->givePermissionTo('see personal');
        $role->givePermissionTo('change own');

        $role = Role::findByName('admin');
        /** @var \Spatie\Permission\Contracts\Role $role */
        $role->givePermissionTo('see private');
        $role->givePermissionTo('see personal');
        $role->givePermissionTo('change any');
        $role->givePermissionTo('change own');
        $role->givePermissionTo('see all');
        $role->givePermissionTo('change all');

        return redirect('/members')->with('success', 'Hack1 completed.');
    }

    /**
     * @return mixed
     */
    public function newer_hack1(): mixed
    {
        /**
         * Do some more foolery with things.
         */
        $user = Auth::user();
        /** @var \App\Models\User $user */ // Simpler cast to help static analysis
        $isrole = $user->hasRole('admin');

        $ispermitted = [];
        $ispermitted[] = $user->can('see private');
        $ispermitted[] = $user->can('see personal');
        $ispermitted[] = $user->can('see all');

        dd($ispermitted, $isrole, $user->name, $user->id);
    }

    /**
     * @return mixed
     */
    public function hack2(): mixed
    {
        /**
         * Do some more foolery with things.
         */
        $theFile = $_SERVER['DOCUMENT_ROOT'].'/contents/'.'bylaws 2011-11-10.pdf';
        $fp = @fopen($theFile, 'rb');
        if ($fp) {
            $bylaws = fread($fp, (int) filesize($theFile)); // Cast filesize result to int
            fclose($fp);
        } else {
            dd($theFile, 'Ritual text missing');
        }

        // Crypt::encrypt expects a string
        $encrypted = Crypt::encrypt((string) $bylaws);
        $theCryptFile = $theFile.'Crypt';
        $myfile = fopen($theCryptFile, 'w');
        fwrite($myfile, $encrypted);
        fclose($myfile);

        $fp = @fopen($theCryptFile, 'rb');
        if ($fp) {
            $bylawsCrypt = fread($fp, (int) filesize($theCryptFile)); // Cast filesize result to int
            fclose($fp);
        } else {
            dd($theFile, 'Encrypted text missing');
        }

        $message = Crypt::decrypt((string) $bylawsCrypt);

        dd('It works!', strlen((string) $bylaws), strlen($encrypted), strlen($message));
    }

    public function hack3(): RedirectResponse
    {
        /**
         * Do some more foolery with things.
         */
        $role = Role::findByName('admin');
        /** @var \Spatie\Permission\Contracts\Role $role */
        $role->givePermissionTo('change all');

        return redirect('/members')->with('success', 'Hack completed.');
    }

    /**
     * @return mixed
     */
    public function hack(): mixed
    {
        $aryCultures = ['Welsh', 'Irish', 'Roman', 'Greek', 'Norse', 'Slavic', 'Other'];
        $aryNames = ['Samhain', 'Yule', 'Imbolc', 'Spring', 'Beltaine', 'Summer', 'Lughnasadh', 'Fall', 'PaganPride', 'Private'];

        $strCultures = implode(',', $aryCultures);
        $strNames = implode(',', $aryNames);


        $elements = Element::where('name', '=', 'cultures')->first();
        /** @var \App\Models\Element|null $elements */ // Explicit cast
        var_dump($elements?->item);
        // Ensure item is a string before exploding
        $cultures = explode(',', (string) $elements?->item);
        var_dump('cultures', $cultures);

        $elements = Element::where('name', '=', 'names')->first();
        /** @var \App\Models\Element|null $elements */ // Explicit cast
        var_dump($elements?->item);
        // Ensure item is a string before exploding
        $cultures = explode(',', (string) $elements?->item);
        var_dump('names', $cultures);


        dd("hackstrary done");
    }
}
