# Repair Requests Datalist Feature - Test Guide

## Overview
This document describes the implementation of the datalist functionality for Serial Number and Tag Number fields on the `/repair-requests` page, along with automatic inventory item creation.

## Features Implemented

### 1. Datalist Input Fields
- **Serial Number** and **Tag Number** fields are now `<input type="text">` with `<datalist>` support
- Users can:
  - Type freely to search/filter existing values
  - Select from a dropdown list of existing serial/tag numbers
  - **Enter completely new values not in the list**
- Backward compatible with existing data

### 2. Automatic Inventory Item Creation
When a repair request is submitted with a serial/tag number:

**If the serial/tag combination EXISTS in inventory:**
- Updates the existing inventory item's status and remarks
- Links repair request to existing item

**If the serial/tag combination is NEW:**
- Creates a new inventory record with:
  - `serial_no`: Provided serial number
  - `tag_no`: Provided tag number
  - `state`: Selected state from form
  - `facility_id`: Selected facility from form
  - `status`: Repair request status
  - `remarks`: Repair request description
  - Default category: "Equipment"
  - Default department_id and unit_id: 1
  - User tracking: Current logged-in user

- Creates corresponding concurrency record with:
  - `state`: Selected state
  - `location`: Facility name
  - `serial_number`: Provided serial number
  - `tag_number`: Provided tag number
  - `condition`: Repair request status
  - `comments`: Description

- Links repair request to newly created item

## File Changes

### View File: `resources/views/repair_requests/index.blade.php`

#### Changes:
1. **Replaced select elements with input+datalist:**
   - Before: `<select class="serial-select select2">`
   - After: `<input type="text" list="serialNumbers">`

2. **Added datalist elements before form close:**
   ```blade
   <datalist id="serialNumbers">
       @foreach($serialNumbers as $serial)
           <option value="{{ $serial }}">{{ $serial }}</option>
       @endforeach
   </datalist>

   <datalist id="tagNumbers">
       @foreach($tagNumbers as $tag)
           <option value="{{ $tag }}">{{ $tag }}</option>
       @endforeach
   </datalist>
   ```

3. **Updated JavaScript for dynamic rows:**
   - Changed Select2 initialization to skip serial/tag input fields
   - Only initialize Select2 on item-select and status-select
   - Input fields auto-complete with datalist natively

### Controller File: `app/Http/Controllers/RepairRequestController.php`

#### Logic Flow in `store()` method:
```
1. Validate form data
2. For each repair request:
   a. Check if serial AND tag numbers are provided
   b. Look for existing inventory item with these serial/tag numbers
   c. If NOT FOUND:
      - Get facility object to retrieve facility_name
      - Get default item if no item_id provided
      - Create NEW inventory record
      - Create NEW concurrency record
      - Link item_id to newly created inventory
   d. If FOUND:
      - Update existing inventory status/remarks
      - Link item_id to existing inventory
   e. Create repair request with linked item_id
3. Commit all changes
4. Send admin notification
```

#### Key Additions:
- Check for existing inventory: `inventory::where('serial_no', $serialNumber)->where('tag_no', $tagNumber)->first()`
- Create inventory: `new inventory()` with all required fields
- Create concurrency: `new concurrency()` with matching information
- Facility name resolution: `facilities::find($facilityId)->facility_name`

## Testing Scenarios

### Test 1: Existing Serial/Tag Number
**Steps:**
1. Navigate to `/repair-requests`
2. Select a state and facility
3. In Serial Number field, start typing an existing serial number
4. Select from dropdown list
5. Select corresponding tag number
6. Submit form

**Expected Result:**
- Repair request created
- Existing inventory item updated with new status
- Item appears in repair request detail

### Test 2: New Serial/Tag Number
**Steps:**
1. Navigate to `/repair-requests`
2. Select a state and facility
3. In Serial Number field, type a NEW serial number (e.g., "SERIAL-NEW-001")
4. In Tag Number field, type a NEW tag number (e.g., "TAG-NEW-001")
5. Select a status (e.g., "Need Repairs")
6. Add description
7. Submit form

**Expected Result:**
- Repair request created
- NEW inventory record created with:
  - serial_no = "SERIAL-NEW-001"
  - tag_no = "TAG-NEW-001"
  - state = selected state
  - facility_id = selected facility
  - status = "Need Repairs"
  - remarks = description provided
- NEW concurrency record created with same data
- Repair request linked to new inventory item
- Item appears in `/inventory` page with new serial/tag numbers

### Test 3: Multiple Requests with Mixed Scenarios
**Steps:**
1. Click "Add More" to create multiple request rows
2. First row: Use existing serial/tag
3. Second row: Use new serial/tag
4. Third row: Use existing serial/tag (different from first)
5. Submit all requests

**Expected Result:**
- All 3 repair requests created
- Inventory items correctly created/updated as per scenario
- All items appear in repair requests list with correct information

### Test 4: Datalist Search
**Steps:**
1. Click on Serial Number field
2. Type partial value (e.g., first 3 characters of serial)
3. Observe dropdown filter

**Expected Result:**
- Dropdown shows only matching serial numbers
- Can still select from filtered list
- Can clear and type new value

## Verification Checks

After implementing and testing:

1. **Database Checks:**
   ```sql
   -- Verify new inventory items created
   SELECT * FROM inventories WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR);
   
   -- Verify concurrency records created
   SELECT * FROM concurrencies WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR);
   
   -- Verify repair requests linked correctly
   SELECT r.*, i.serial_no, i.tag_no FROM repair_requests r 
   LEFT JOIN inventories i ON r.item_id = i.id 
   WHERE r.created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR);
   ```

2. **UI Checks:**
   - ✓ Serial/Tag fields are text inputs with datalist
   - ✓ Dropdown appears when field is focused
   - ✓ Existing values are suggested
   - ✓ New values can be entered
   - ✓ Multiple rows can be added with same pattern
   - ✓ Form validation still works

3. **Data Integrity:**
   - ✓ Inventory items show correct serial/tag numbers
   - ✓ State and facility are preserved
   - ✓ Status is correctly recorded
   - ✓ Concurrency records match inventory records

## Error Handling

- If facility_id not found: Uses "Unknown Facility" as fallback
- If no item_id: Creates inventory with generic name based on serial
- If department/unit not found: Uses defaults (id=1)
- All operations in transaction: Rolls back if any step fails

## Browser Compatibility

Datalist is supported in:
- Chrome 20+
- Firefox 4+
- Safari 12.1+
- Edge (all versions)
- IE 10+

Note: IE displays as basic text input (still functional)

## Performance Notes

- Datalist rendering is native browser feature (fast)
- No additional JavaScript libraries required for datalist
- Inventory lookup is indexed on (serial_no, tag_no)
- Concurrency creation minimal DB impact

## Future Enhancements

Possible improvements:
1. Add keyboard shortcuts for quick selection
2. Add quick-add modal for frequently used serial/tags
3. Batch import serial/tags from CSV
4. Auto-populate facility based on serial/tag history
5. Add serial/tag barcode scanning support
