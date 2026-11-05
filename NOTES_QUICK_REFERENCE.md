# Notes & Documents Feature - Quick Reference

## Feature Summary

A complete notes and documents management system with:
- **WYSIWYG Editor** (Summernote) for rich text notes
- **File Attachments** (up to 10MB per file)
- **Inventory Linking** (attach notes to specific items)
- **Role-Based Access** (Admins see all, users see their own)
- **Admin Dashboard** for managing all notes

## Quick Start

### For Users
1. Click **Notes & Documents** in sidebar
2. Click **New Note** button
3. Enter title and content (use WYSIWYG editor for formatting)
4. Optionally link to inventory item
5. Upload attachments if needed
6. Click **Create Note**

### For Admins
- Same as users, but can:
  - View all users' notes
  - Edit any note
  - Delete any note
  - Share notes with other admins (checkbox option)

### For Item-Linked Notes
1. Go to inventory item detail page
2. Click **View Notes** button at bottom
3. See all notes linked to that item
4. Click "Add Note for This Item" to create new linked note

## File Structure

```
app/
├── Note.php                          # Main model
├── NoteAttachment.php                # Attachment model
├── Http/Controllers/NoteController.php
├── Policies/NotePolicy.php

database/migrations/
├── 2026_08_10_000000_create_notes_table.php
└── 2026_08_10_000001_create_note_attachments_table.php

resources/views/notes/
├── index.blade.php          # List all notes
├── create.blade.php         # Create form
├── edit.blade.php           # Edit form
├── show.blade.php           # View single note
└── inventory-notes.blade.php # Notes for item
```

## Database Tables

### notes
- id, user_id, inventory_id, title, content, is_public, timestamps

### note_attachments
- id, note_id, file_name, file_path, file_size, mime_type, timestamps

## Routes

| Method | Route | Purpose |
|--------|-------|---------|
| GET | /notes | List notes |
| GET | /notes/create | Create form |
| POST | /notes | Save new note |
| GET | /notes/{id} | View note |
| GET | /notes/{id}/edit | Edit form |
| PUT | /notes/{id} | Update note |
| DELETE | /notes/{id} | Delete note |
| GET | /notes/{note}/attachment/{attachment}/download | Download file |
| DELETE | /notes/{attachment}/attachment-delete | Delete file |
| GET | /inventory/{inventory}/notes | View item notes |

## Key Features

### WYSIWYG Editor Toolbar
- **Text**: Bold, Underline, Clear formatting
- **Font**: Family, Size selection
- **Color**: Text color picker
- **Paragraphs**: Lists, paragraph formatting
- **Table**: Insert/format tables
- **Media**: Links, images, videos
- **View**: Full screen, code view, help

### Attachment Support
- **Max Size**: 10MB per file
- **Formats**: PDF, Office docs, Images, ZIP, TXT
- **Storage**: `/storage/app/public/notes/{note_id}/`
- **Actions**: Download or delete

### Access Control
- **Public**: Admins can share notes across admin group
- **Private**: Users see only own notes
- **Admin Override**: Admins see/edit/delete all notes

## Implementation Details

### Authentication
- All routes protected with `auth` middleware
- Some routes require specific roles

### Authorization
- Uses Laravel Policy system
- `NotePolicy` file defines permissions
- Enforced in views via `@can` directives
- Enforced in controller via `authorize()` calls

### Database Optimization
- Eager loading relationships
- Indexes on user_id, inventory_id
- Pagination (15 per page)

### File Handling
- Validation on upload
- Automatic directory creation
- Old files cleaned up on replace

## Next Steps (Optional Enhancements)

1. Add note search functionality
2. Add note tags/categories
3. Add note sharing with specific users
4. Add note history/versioning
5. Add email notifications for shared notes
6. Add bulk operations (delete multiple, export)
7. Add note templates
8. Add markdown support alternative

## Troubleshooting

**Issue**: Files not uploading
- Solution: Run `php artisan storage:link` and check permissions

**Issue**: Editor not showing
- Solution: Check browser console for JavaScript errors, ensure jQuery loaded

**Issue**: Attachments not downloadable
- Solution: Check file path exists in storage, verify storage symlink created

**Issue**: Permission denied errors
- Solution: Check user role matches authorization policy

## Testing Checklist

- [ ] Create note as regular user
- [ ] Edit own note
- [ ] Cannot edit other user's note
- [ ] Admin can edit any note
- [ ] Admin can delete any note
- [ ] Upload and download attachments
- [ ] Link note to inventory item
- [ ] View notes for specific item
- [ ] WYSIWYG editor formatting works
- [ ] Delete note removes attachments
- [ ] Pagination works for >15 notes
