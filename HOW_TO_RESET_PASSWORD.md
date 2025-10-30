# How to Reset a User's Forgotten Password

## Quick Steps:

1. Go to **Admin → Manage Users**
2. Find the user (use search if needed)
3. Click the **Key Icon** 🔑 (or **Edit** then scroll down)
4. Enter new password (8+ characters)
5. Confirm the password
6. Click **Update User**

## What Happens Behind the Scenes:

### Controller Logic (`app/Http/Controllers/Admin/UserController.php`):

```php
// Step 1: Check if admin has permission
if(Gate::denies('admin-access')) {
    return redirect('errors.403');
}

// Step 2: Validate the password if provided
if ($request->filled('password')) {
    $rules['password'] = 'required|string|min:8|confirmed';
}

// Step 3: Hash and save new password
if ($request->filled('password')) {
    $user->password = bcrypt($request->password); // Encrypts the password
}

// Step 4: Save changes
$user->save();
```

### Database Process:

```
Old Password: encrypted hash (cannot be retrieved)
    ↓
User enters new password
    ↓
Controller hashes it with bcrypt
    ↓
Stores encrypted hash in database
    ↓
User can now login with new password
```

## Important Notes:

- ✅ Passwords are encrypted (bcrypt hashing)
- ✅ Old password is never shown (for security)
- ✅ Minimum 8 characters required
- ✅ Password confirmation required to prevent typos
- ✅ Leaving password blank = no change to current password
- ✅ Eye icon to show/hide password while typing

## Troubleshooting:

**Problem:** "User still can't login after reset"
- ✅ Check password meets 8 character minimum
- ✅ Verify password confirmation matches
- ✅ Make sure "Update User" button was clicked
- ✅ Check for validation errors on the page

**Problem:** "I need to reset my own password"
- Admins have same process - another admin can reset it
- Or use password reset via email link (if implemented)

