<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\concurrency;
use App\inventory;
use App\items;
use Illuminate\Support\Facades\DB;
// dates are treated as raw text in uploads; no Carbon parsing required

class ConcurrenciesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $userStateRaw = auth()->user()->state ?? '';
        $userState = strtolower($userStateRaw);
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // normalize keys to string and trim
                $id = isset($row['id']) ? trim((string)$row['id']) : null;
                $rowStateRaw = isset($row['state']) ? trim((string)$row['state']) : null;
                $rowState = $rowStateRaw !== null ? strtolower($rowStateRaw) : null;

                // If user not 'all' and the row state is present but different, skip it
                if ($userState !== '' && $userState !== 'all') {
                    if ($rowState !== null && $rowState !== $userState) {
                        // skip rows not in user's state
                        continue;
                    }
                }

                // If row has no state and user has a specific state, set it to user's state
                if (($rowState === null || $rowState === '') && $userState !== '' && $userState !== 'all') {
                    $rowStateRaw = $userStateRaw;
                    $rowState = $userState;
                }

                // if id present -> update existing concurrency
                if (!empty($id)) {
                    $concurrency = concurrency::find($id);
                    if (!$concurrency) {
                        // If id provided but not found, create new
                        $concurrency = new concurrency();
                    } else {
                        // If user is restricted to a state and the concurrency belongs to another state, skip update
                        if ($userState !== '' && $userState !== 'all' && strtolower($concurrency->state ?? '') !== $userState) {
                            continue;
                        }
                    }

                    // update concurrency fields
                    $concurrency->state = $rowStateRaw ?? $concurrency->state;
                    $concurrency->location = $row['location'] ?? $concurrency->location;
                    $concurrency->model = $row['model'] ?? $concurrency->model;
                    $concurrency->serial_number = $row['serial_number'] ?? $concurrency->serial_number;
                    $concurrency->tag_number = $row['tag_number'] ?? $concurrency->tag_number;
                    $concurrency->user = $row['user'] ?? $concurrency->user;
                    $concurrency->date_of_purchase = $row['date_of_purchase'] ?? $concurrency->date_of_purchase;
                    $concurrency->grant = $row['grant'] ?? $concurrency->grant;
                    $concurrency->category = $row['category'] ?? $concurrency->category;
                    $concurrency->batch = $row['batch'] ?? $concurrency->batch;
                    $concurrency->condition = $row['condition'] ?? $concurrency->condition;
                    $concurrency->date_delivered = $row['date_delivered'] ?? $concurrency->date_delivered;
                    $concurrency->received_by = $row['received_by'] ?? $concurrency->received_by;
                    $concurrency->comments = $row['comments'] ?? $concurrency->comments;
                    $concurrency->other_info = $row['other_info'] ?? $concurrency->other_info;
                    $concurrency->save();

                    // update related inventory by concurrency->iid or by cid if present
                    $inv = null;
                    if (!empty($concurrency->iid)) {
                        $inv = inventory::find($concurrency->iid);
                    }
                    if (!$inv) {
                        $inv = inventory::where('cid', $concurrency->id)->first();
                    }
                    if ($inv) {
                        $inv->state = $rowStateRaw ?? $inv->state;
                        $inv->facility = $row['location'] ?? $inv->facility;
                        $inv->item_name = $row['model'] ?? $inv->item_name;
                        $inv->serial_no = $row['serial_number'] ?? $inv->serial_no;
                        $inv->tag_no = $row['tag_number'] ?? $inv->tag_no;
                        $inv->assigned_to = $row['user'] ?? $inv->assigned_to;
                        if (!empty($row['date_of_purchase'])) {
                            // store raw text value for date_of_purchase as provided in the sheet
                            $inv->date_purchased = $row['date_of_purchase'];
                        }
                        $inv->grants = $row['grant'] ?? $inv->grants;
                        $inv->category = $row['category'] ?? $inv->category;
                        $inv->batch = $row['batch'] ?? $inv->batch;
                        $inv->status = $row['condition'] ?? $inv->status;
                        $inv->date_delivered = $row['date_delivered'] ?? $inv->date_delivered;
                        $inv->received_by = $row['received_by'] ?? $inv->received_by;
                        $inv->remarks = $row['comments'] ?? $inv->remarks;
                        $inv->description = $row['other_info'] ?? $inv->description;
                        // save lga -> inventories.type and sr -> inventories.supplier
                        $inv->type = $row['lga'] ?? $inv->type;
                        $inv->supplier = $row['sr'] ?? $inv->supplier;
                        $inv->save();
                    }

                    continue;
                }

                // No id -> create new inventory then concurrency, then link them
                // Determine or create an item (items table) to satisfy item_id FK
                $modelName = $row['model'] ?? 'Imported Item';
                $item = items::firstOrCreate(
                    ['item_name' => $modelName],
                    ['item_name' => $modelName]
                );

                // create inventory
                $inv = new inventory();
                $inv->item_id = $item->id ?? null;
                $inv->item_name = $modelName;
                $inv->state = $rowStateRaw ?? null;
                $inv->facility = $row['location'] ?? null;
                $inv->assigned_to = $row['user'] ?? null;
                
                if (!empty($row['date_of_purchase'])) {
                    // store raw text value for date_of_purchase as provided in the sheet
                    $inv->date_purchased = $row['date_of_purchase'];
                }
                $inv->serial_no = $row['serial_number'] ?? null;
                $inv->tag_no = $row['tag_number'] ?? null;
                $inv->grants = $row['grant'] ?? null;
                $inv->category = $row['category'] ?? null;
                $inv->batch = $row['batch'] ?? null;
                $inv->status = $row['condition'] ?? null;
                $inv->date_delivered = $row['date_delivered'] ?? null;
                $inv->received_by = $row['received_by'] ?? null;
                $inv->remarks = $row['comments'] ?? null;
                $inv->description = $row['other_info'] ?? null;
                // save lga -> inventories.type and sr -> inventories.supplier
                $inv->type = $row['lga'] ?? null;
                $inv->supplier = $row['sr'] ?? null;
                $inv->save();

                // create concurrency and link iid
                $concurrency = new concurrency();
                $concurrency->iid = $inv->id;
                $concurrency->state = $rowStateRaw ?? null;
                $concurrency->location = $row['location'] ?? null;
                $concurrency->model = $row['model'] ?? null;
                $concurrency->serial_number = $row['serial_number'] ?? null;
                $concurrency->tag_number = $row['tag_number'] ?? null;
                $concurrency->user = $row['user'] ?? null;
                $concurrency->date_of_purchase = $row['date_of_purchase'] ?? null;
                $concurrency->grant = $row['grant'] ?? null;
                $concurrency->category = $row['category'] ?? null;
                $concurrency->batch = $row['batch'] ?? null;
                $concurrency->condition = $row['condition'] ?? null;
                $concurrency->date_delivered = $row['date_delivered'] ?? null;
                $concurrency->received_by = $row['received_by'] ?? null;
                $concurrency->comments = $row['comments'] ?? null;
                $concurrency->other_info = $row['other_info'] ?? null;
                $concurrency->save();

                // if inventories table has cid column, try to set it to concurrency id
                try {
                    if (schema_has_column('inventories', 'cid')) {
                        $inv->cid = $concurrency->id;
                        $inv->save();
                    }
                } catch (\Throwable $e) {
                    // ignore if schema helper not available or column missing
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Concurrencies import error: '.$e->getMessage());
            throw $e;
        }
    }
}