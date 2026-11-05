# Notes & Documents Feature - Setup & Usage Guide

## Feature Overview

The Notes & Documents feature allows admin users and regular users to create and manage notes with the following capabilities:

### Core Features
- **WYSIWYG Editor**: Rich text editing using Summernote
- **File Attachments**: Upload and manage attachments (max 10MB per file)
- **Inventory Linking**: Link notes to specific inventory items
- **Role-Based Access Control**:
  - **Admin/Super Users**: Can view and manage all notes
  - **Regular Users**: Can only view and manage their own notes
- **Organized Storage**: Files stored in `storage/app/public/notes/`

## Database Setup

### 1. Run Migrations

```bash
php artisan migrate
```

This will create two tables:
- `notes` - Main notes table
- `note_attachments` - File attachments table

### 2. Table Structure

**notes table:**
- `id` (primary key)
- `user_id` (foreign key to users)
- `inventory_id` (nullable, foreign key to inventory)
- `title` (string)
- `content` (longText - stores HTML)
- `is_public` (boolean - for sharing between admins)
- `created_at`, `updated_at` (timestamps)

**note_attachments table:**
- `id` (primary key)
- `note_id` (foreign key to notes)
- `file_name` (string)
- `file_path` (string)
- `file_size` (bigInteger)
- `mime_type` (string)
- `created_at`, `updated_at` (timestamps)

## Accessing the Feature

### Navigation
- Click **Notes & Documents** in the sidebar menu (available to all authenticated users)
- Admins see all notes
- Users see only their own notes

### Routes

| Route | Method | Description |
|-------|--------|-------------|
| `/notes` | GET | List notes |
| `/notes/create` | GET | Create note form |
| `/notes` | POST | Store new note |
| `/notes/{id}` | GET | View single note |
| `/notes/{id}/edit` | GET | Edit note form |
| `/notes/{id}` | PUT | Update note |
| `/notes/{id}` | DELETE | Delete note |
| `/notes/{note}/attachment/{attachment}/download` | GET | Download attachment |
| `/notes/{attachment}/attachment-delete` | DELETE | Delete attachment |
| `/inventory/{inventory}/notes` | GET | View notes for specific item |

## Using the Feature

### Creating a Note

1. Click **Notes & Documents** in the sidebar
2. Click **New Note** button
3. Fill in the form:
   - **Title**: Required, max 255 characters
   - **Content**: Use the WYSIWYG editor to format your note
   - **Link to Item**: (Optional) Select an inventory item to link the note
   - **Attachments**: Upload files (multiple files supported)
   - **Share with Other Admins**: Checkbox to share with other admin users

### Editing a Note

1. Go to Notes & Documents
2. Find the note you want to edit
3. Click the **Edit** button (only visible to note owner or admins)
4. Make changes and click **Update Note**

### Managing Attachments

- **Add Attachments**: In create/edit form, use the file upload field
- **Download Attachments**: In the note view, click the download icon
- **Delete Attachments**: In the edit form, click the delete button next to the attachment

### Viewing Notes for an Item

1. On an inventory item's detail page, click **View Notes** or navigate to `/inventory/{id}/notes`
2. See all notes linked to that item
3. Click on a note to view full details

## Authorization & Security

### Access Control
- **View Notes**: Users can see only their own notes; Admins can see all
- **Create Notes**: All authenticated users
- **Edit Notes**: Only the note owner or Admins
- **Delete Notes**: Only the note owner or Admins
- **Download Attachments**: Same as viewing the note

### Policy Implementation
Authorization is enforced via `NotePolicy` in `app/Policies/NotePolicy.php`:
- Policies are automatically checked via `@can` directives in views
- Controller methods use `authorize()` to enforce permissions

## File Upload Security

- Maximum file size: 10MB per file
- Accepted formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, GIF, TXT, ZIP
- Files stored in: `storage/app/public/notes/{note_id}/`
- Files are validated on upload via Laravel's `file` validation rule

## WYSIWYG Editor Features

The Summernote editor includes:
- **Text Formatting**: Bold, underline, clear formatting
- **Font Options**: Font family and size selection
- **Colors**: Text color selection
- **Paragraphs**: Ordered/unordered lists, indentation
- **Tables**: Insert and format tables
- **Media**: Insert links, pictures, and videos
- **Full Screen**: Edit in fullscreen mode
- **Code View**: Edit raw HTML
- **Undo/Redo**: Full history navigation

## Troubleshooting

### Issue: Migrations not found
**Solution**: Ensure migration files exist in `database/migrations/` directory. Run `php artisan migrate` to apply them.

### Issue: Storage directory permission error
**Solution**: Run `php artisan storage:link` to create the symbolic link, then ensure `storage/app/public/` is writable:
```bash
chmod -R 775 storage/app/public
```

### Issue: WYSIWYG editor not loading
**Solution**: Check browser console for errors. Ensure jQuery is loaded before the Summernote scripts (it is, via Materialize).

### Issue: File upload not working
**Solution**: 
1. Check `php.ini` upload_max_filesize and post_max_size settings
2. Ensure storage/app/public directory exists and is writable
3. Check .env for correct APP_URL setting

## Integration with Other Features

### Adding Notes Link to Inventory Item Page

To add a "View Notes" link on the inventory item detail page, add this to `resources/views/inventories.blade.php` or the item edit view:

```blade
<a href="{{ route('inventory.notes', $item->id) }}" class="btn waves-effect waves-light">
    <i class="material-icons left">note</i>View Notes
</a>
```

## Best Practices

1. **Use Clear Titles**: Make note titles descriptive for easy searching
2. **Link to Items**: When notes are about specific items, link them for better organization
3. **Use Formatting**: Utilize the WYSIWYG editor's formatting features for readability
4. **Archive Old Notes**: Consider periodically reviewing and deleting old notes
5. **File Organization**: Use clear file names for attachments
6. **Backup Attachments**: Important attachments should be backed up separately

## Performance Considerations

- Notes are paginated (15 per page) for better performance
- Eager loading is used to avoid N+1 queries
- Indexes on `user_id`, `inventory_id`, and `created_at` for fast filtering
- Consider archiving old notes if the table grows very large (1000+ records)
