<?php

namespace App\Exports;

use App\concurrency;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConcurrenciesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        $userState = strtolower(auth()->user()->state ?? '');
        $query = concurrency::select(
            'iid',
            'state',
            'id',
            'location',
            'model',
            'serial_number',
            'tag_number',
            'user',
            'date_of_purchase',
            'grant',
            'category',
            'batch',
            'condition',
            'date_delivered',
            'received_by',
            'comments',
            'other_info',
            'created_at',
            'updated_at'
        )->orderBy('id');

        // If user state is not 'all', limit to that state
        if ($userState !== '' && $userState !== 'all') {
            $query->where('state', $userState);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'iid',
            'state',
            'id',
            'location',
            'model',
            'serial_number',
            'tag_number',
            'user',
            'date_of_purchase',
            'grant',
            'category',
            'batch',
            'condition',
            'date_delivered',
            'received_by',
            'comments',
            'other_info',
            'created_at',
            'updated_at',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $workbook = $sheet->getParent();

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $fullRange = 'A1:' . $highestColumn . $highestRow;

                // 1) Make all cells unlocked (so they're editable)
                $sheet->getStyle($fullRange)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);

                // 2) Lock header row and columns A (iid), B (state), C (id)
                $sheet->getStyle('1:1')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
                $sheet->getStyle('A:A')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
                $sheet->getStyle('B:B')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);
                $sheet->getStyle('C:C')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);

                // Protect the sheet with password 'ihvn2k1'
                $sheet->getProtection()->setPassword('ihvn2k1');
                $sheet->getProtection()->setSheet(true);
                $sheet->getProtection()->setSort(true);
                $sheet->getProtection()->setInsertRows(true);
                $sheet->getProtection()->setFormatCells(true);

                // Freeze the header row
                $sheet->freezePane('A2');

                // Header styling
                $sheet->getStyle('A1:' . $highestColumn . '1')->getFont()->setBold(true);

                // Build lists for dropdowns: Location (col D), Model (col E), Condition (col M)
                $userState = strtolower(auth()->user()->state ?? '');
                $locationsQuery = concurrency::whereNotNull('location')->where('location', '!=', '');
                $modelsQuery = concurrency::whereNotNull('model')->where('model', '!=', '');

                if ($userState !== '' && $userState !== 'all') {
                    $locationsQuery->where('state', $userState);
                    $modelsQuery->where('state', $userState);
                }

                $locations = $locationsQuery->distinct()->orderBy('location')->pluck('location')->values()->all();
                $models = $modelsQuery->distinct()->orderBy('model')->pluck('model')->values()->all();

                $conditions = [
                    'Operational',
                    'Not Operational',
                    'Lost',
                    'Archived',
                    'Need Repairs'
                ];

                // create hidden Lists sheet (replace if exists)
                $listsSheetName = 'Lists';
                foreach ($workbook->getAllSheets() as $wks) {
                    if ($wks->getTitle() === $listsSheetName) {
                        $workbook->removeSheetByIndex($workbook->getIndex($wks));
                        break;
                    }
                }

                $listsSheet = new Worksheet($workbook, $listsSheetName);
                $workbook->addSheet($listsSheet);

                if (!empty($locations)) {
                    $listsSheet->fromArray($locations, null, 'A1');
                }
                if (!empty($models)) {
                    $listsSheet->fromArray($models, null, 'B1');
                }
                if (!empty($conditions)) {
                    $listsSheet->fromArray($conditions, null, 'C1');
                }

                $listsSheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

                // apply validation helper
                $applyValidation = function($colLetter, $listRange) use ($sheet, $highestRow) {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $cell = $sheet->getCell($colLetter . $row);
                        $validation = $cell->getDataValidation();
                        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setShowDropDown(true);
                        $validation->setFormula1($listRange);
                    }
                };

                if (!empty($locations)) {
                    $locCount = count($locations);
                    $locRange = sprintf('=%s!$A$1:$A$%d', $listsSheetName, $locCount);
                    $applyValidation('D', $locRange);
                }

                if (!empty($models)) {
                    $modCount = count($models);
                    $modRange = sprintf('=%s!$B$1:$B$%d', $listsSheetName, $modCount);
                    $applyValidation('E', $modRange);
                }

                if (!empty($conditions)) {
                    $condCount = count($conditions);
                    $condRange = sprintf('=%s!$C$1:$C$%d', $listsSheetName, $condCount);
                    $applyValidation('M', $condRange);
                }
            },
        ];
    }
}