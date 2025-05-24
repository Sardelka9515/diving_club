<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * 獲取要導出的數據集
     */
    public function collection()
    {
        // 獲取所有與這個活動相關的報名，包含用戶數據
        return $this->activity->registrations()->with('user')->get();
    }

    /**
     * 設置Excel的欄位標題
     */
    public function headings(): array
    {
        return [
            '編號',
            '姓名',
            '電子郵件',
            '電話',
            '狀態',
            '報名時間',
            '核准/拒絕時間',
            '處理人員ID',
            '備註'
        ];
    }

    /**
     * 將每一行數據映射到欄位
     */
    public function map($registration): array
    {
        $statusMap = [
            'pending' => '待審核',
            'approved' => '已核准',
            'rejected' => '已拒絕',
            'cancelled' => '已取消'
        ];

        // 根據狀態決定顯示哪個時間戳和處理人員
        $processTime = null;
        $processBy = null;

        if ($registration->status == 'approved') {
            $processTime = $registration->approved_at;
            $processBy = $registration->approved_by;
        } elseif ($registration->status == 'rejected') {
            $processTime = $registration->rejected_at;
            $processBy = $registration->rejected_by;
        }

        // 以安全方式格式化時間
        $formattedCreatedAt = '無資料';
        try {
            if ($registration->created_at) {
                $formattedCreatedAt = is_string($registration->created_at) 
                    ? $registration->created_at 
                    : $registration->created_at->format('Y-m-d H:i:s');
            }
        } catch (\Exception $e) {
            $formattedCreatedAt = '格式錯誤';
        }

        // 格式化處理時間
        $formattedProcessTime = '尚未處理';
        try {
            if ($processTime) {
                if (is_object($processTime) && method_exists($processTime, 'format')) {
                    $formattedProcessTime = $processTime->format('Y-m-d H:i:s');
                } else {
                    $formattedProcessTime = (string) $processTime;
                }
            }
        } catch (\Exception $e) {
            $formattedProcessTime = '格式錯誤';
        }

        return [
            $registration->id,
            $registration->user ? $registration->user->name : '未知用戶',
            $registration->user ? $registration->user->email : '未知郵箱',
            $registration->user && $registration->user->phone ? $registration->user->phone : '未提供',
            $statusMap[$registration->status] ?? $registration->status ?? '未知狀態',
            $formattedCreatedAt,
            $formattedProcessTime,
            $processBy ?? '無',
            $registration->notes ?? ''
        ];
    }

    /**
     * 設置工作表的標題
     */
    public function title(): string
    {
        // 限制標題長度，避免Excel工作表名稱太長的問題
        $title = mb_substr($this->activity->title, 0, 25);
        return "報名資料-{$title}";
    }

    /**
     * 設置工作表樣式
     */
    public function styles(Worksheet $sheet)
    {
        // 添加活動信息在頂部
        $sheet->insertNewRowBefore(1, 3);
        $sheet->setCellValue('A1', '活動名稱: ' . $this->activity->title);
        $sheet->setCellValue('A2', '匯出時間: ' . now()->format('Y-m-d H:i:s'));
        $sheet->setCellValue('A3', '報名人數: ' . $this->activity->registrations->count() . ' 人');

        // 合併這些單元格
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

        // 設置頂部資訊的背景色
        $sheet->getStyle('A1:I3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('F0F8FF'); // 淺藍色

        // 設置標題行為粗體 (現在是第4行)
        $sheet->getStyle('A4:I4')->getFont()->setBold(true);
        $sheet->getStyle('A4:I4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('EEEEEE'); // 淺灰色背景

        // 設置整個表格的邊框
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A4:I'.$lastRow)->applyFromArray($styleArray);

        return $sheet;
    }
}