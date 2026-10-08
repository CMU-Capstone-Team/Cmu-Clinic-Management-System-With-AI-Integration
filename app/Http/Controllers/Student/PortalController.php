<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class PortalController extends Controller
{
    public function checkStatus()
    {
        return redirect()->route('student.profile');
    }

    public function profile(Request $request)
    {
        $student = $request->user('student');
        return view('students.profile', compact('student'));
    }

    public function updateAcademic(Request $request)
    {
        $student = $request->user('student');
        abort_unless($student, 401);

        // Only accept these personal fields; never accept a student ID or account fields.
        foreach (['year_section', 'contact_number', 'address', 'sex', 'birth_date'] as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $value = trim($value);
                $request->merge([$field => $value === '' ? null : $value]);
            }
        }

        $yearSectionPattern = '/^(?:(?<course>[A-Za-z][A-Za-z .&()]{0,49}?)(?:\s*-\s*|\s+))?(?<year>[1-6])\s*[-\/]?\s*(?<section>[A-Za-z0-9][A-Za-z0-9 ._-]{0,19})$/u';

        $validated = $request->validateWithBag('academicUpdate', [
            'year_section' => ['required', 'string', 'max:80', 'regex:' . $yearSectionPattern],
            'sex' => ['nullable', Rule::in(['male', 'female'])],
            'birth_date' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'contact_number' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+().\s-]+$/'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'year_section.required' => 'Please enter your year and section.',
            'year_section.regex' => 'Use a format like BSIT - 3D, 3D, or 3-D (year 1 to 6).',
            'year_section.max' => 'Year and section must be 80 characters or fewer.',
            'birth_date.before_or_equal' => 'Birthday cannot be in the future.',
            'contact_number.regex' => 'Please enter a valid contact number.',
        ]);

        preg_match($yearSectionPattern, $validated['year_section'], $academicParts);
        $student->year_level = (int) $academicParts['year'];
        $student->section = trim($academicParts['section']);
        if (!empty($academicParts['course'])) {
            $student->course = trim($academicParts['course']);
        }
        foreach (['sex', 'birth_date', 'contact_number', 'address'] as $field) {
            if (array_key_exists($field, $validated)) {
                $student->{$field} = $validated[$field];
            }
        }
        $student->save();

        return redirect()->route('student.profile')
            ->with('success', 'Personal information updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $student = $request->user('student');
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $validated = $request->validateWithBag('emailUpdate', [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student)],
            'current_password' => ['required', 'string', 'current_password:student'],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
            'email.unique' => 'This email address is already registered.',
        ]);
        if (strtolower($student->email) === $validated['email']) {
            return back()->with('success', 'Your email is unchanged.');
        }
        $student->email = $validated['email'];
        if ($student->isDirty('email') && array_key_exists('email_verified_at', $student->getAttributes())) {
            $student->email_verified_at = null;
        }
        $student->save();
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('status', 'Email updated. Sign in to verify your new email.');
    }

    public function updatePassword(Request $request)
    {
        $student = $request->user('student');
        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'string', 'current_password:student'],
            'password' => ['required', 'string', 'confirmed', 'different:current_password', Password::min(8), 'max:72'],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
            'password.different' => 'Choose a password different from your current password.',
            'password.confirmed' => 'The new passwords do not match.',
        ]);
        $student->password = Hash::make($validated['password']);
        $student->save();
        $request->session()->regenerate();
        return redirect()->route('student.profile')->with('success', 'Password updated successfully.');
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
            'profile_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ], [
            'profile_photo.required' => 'Please choose a photo first.',
            'profile_photo.image' => 'Please choose a valid image.',
            'profile_photo.mimes' => 'Use a JPG, PNG, or WebP photo.',
            'profile_photo.max' => 'The photo must be 2 MB or smaller.',
            'profile_photo.dimensions' => 'Maximum image size is 4096 × 4096 pixels.',
        ]);
        $directory = 'student-photos/' . $student->getKey();
        $oldPath = $student->profile_photo_path;
        $newPath = $request->file('profile_photo')->store($directory, 'public');
        if (!$newPath) {
            return redirect()->route('student.profile')->withErrors([
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
        if ($oldPath && $oldPath !== $newPath && str_starts_with($oldPath, $directory . '/')) {
            Storage::disk('public')->delete($oldPath);
        }
        return redirect()->route('student.profile')->with('success', 'Your profile photo has been updated.');
    }

    public function photo(Request $request)
    {
        $student = $request->user('student');
        $path = $student->profile_photo_path;
        $directory = 'student-photos/' . $student->getKey() . '/';
        abort_unless($path && str_starts_with($path, $directory), 404);
        abort_unless(Storage::disk('public')->exists($path), 404);
        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
