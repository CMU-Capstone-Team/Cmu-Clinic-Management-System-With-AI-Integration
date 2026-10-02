<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class PortalController extends Controller
{
    public function checkStatus()
    {
        return view('students.status');
    }

    public function profile(Request $request)
    {
        $student = $request->user('student');

        return view('students.profile', compact('student'));
    }

    public function updateEmail(Request $request)
    {
        $student = $request->user('student');

        $request->merge([
            'email' => trim((string) $request->input('email')),
        ]);

        $validated = $request->validateWithBag('emailUpdate', [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($student),
            ],
            'current_password' => [
                'required',
                'string',
                'current_password:student',
            ],
        ], [
            'current_password.current_password' =>
                'The current password is incorrect.',
            'email.unique' =>
                'This email address is already registered.',
        ]);

        $student->email = $validated['email'];

        // Reset verification only if this column exists on the record.
        if (
            $student->isDirty('email') &&
            array_key_exists('email_verified_at', $student->getAttributes())
        ) {
            $student->email_verified_at = null;
        }

        $student->save();

        return redirect()
            ->route('student.profile')
            ->with('success', 'Email updated. Use this email the next time you log in.');
    }

    public function updatePassword(Request $request)
    {
        $student = $request->user('student');

        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => [
                'required',
                'string',
                'current_password:student',
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                'different:current_password',
                Password::min(8),
                'max:72',
            ],
        ], [
            'current_password.current_password' =>
                'The current password is incorrect.',
            'password.different' =>
                'Choose a password different from your current password.',
            'password.confirmed' =>
                'The new passwords do not match.',
        ]);

        $student->password = Hash::make($validated['password']);
        $student->save();

        $request->session()->regenerate();

        return redirect()
            ->route('student.profile')
            ->with('success', 'Password updated successfully.');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function visits(Request $request)
    {
        $student = $request->user('student');
        $visits = collect([]);

        return view('students.visits', compact('student', 'visits'));
    }

    public function records(Request $request)
    {
        $student = $request->user('student');

        return view('students.records', compact('student'));
    }

    public function updatePhoto(Request $request)
{
    $student = $request->user('student');

    $request->validateWithBag('photoUpdate', [
        'profile_photo' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
            'dimensions:max_width=4096,max_height=4096',
        ],
    ], [
        'profile_photo.required' => 'Please choose a photo first.',
        'profile_photo.image' => 'Please choose a valid image.',
        'profile_photo.mimes' => 'Use a JPG, PNG, or WebP photo.',
        'profile_photo.max' => 'The photo must be 2 MB or smaller.',
        'profile_photo.dimensions' => 'Maximum image size is 4096 × 4096 pixels.',
    ]);

    $directory = 'student-photos/' . $student->getKey();
    $oldPath = $student->profile_photo_path;

    $newPath = $request->file('profile_photo')->store(
        $directory,
        'public'
    );

    if (!$newPath) {
        return redirect()->route('student.profile')
            ->withErrors([
                'profile_photo' => 'Upload failed. Please try again.',
            ], 'photoUpdate');
    }

    try {
        $student->profile_photo_path = $newPath;
        $student->save();
    } catch (\Throwable $exception) {
        Storage::disk('public')->delete($newPath);

        throw $exception;
    }

    if (
        $oldPath &&
        $oldPath !== $newPath &&
        str_starts_with($oldPath, $directory . '/')
    ) {
        Storage::disk('public')->delete($oldPath);
    }

    return redirect()->route('student.profile')
        ->with('success', 'Your profile photo has been updated.');
}

public function photo(Request $request)
{
    $student = $request->user('student');
    $path = $student->profile_photo_path;
    $directory = 'student-photos/' . $student->getKey() . '/';

    abort_unless(
        $path && str_starts_with($path, $directory),
        404
        );

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}