<?php

namespace App\Exports;

use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LeaveReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithProperties,
    WithColumnWidths
{
    /**
     * Filter laporan
     */
    protected array $filters;

    /**
     * Nomor urut Excel
     */
    protected int $rowNumber = 0;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * =========================================================
     * DATA
     * =========================================================
     *
     * Urutan:
     * 1. Department A-Z
     * 2. Nama karyawan A-Z
     * 3. Tanggal mulai cuti
     */
    public function collection(): Collection
    {
        return $this->query()
            ->orderByRaw("COALESCE(departments.name, 'ZZZZZZ') ASC")
            ->orderByRaw("COALESCE(users.name, 'ZZZZZZ') ASC")
            ->orderBy('leave_requests.start_date', 'ASC')
            ->get();
    }

    /**
     * =========================================================
     * QUERY
     * =========================================================
     */
    protected function query(): Builder
    {
        $query = LeaveRequest::query()
            ->select('leave_requests.*')

            /*
             * =====================================================
             * JOIN EMPLOYEE
             * =====================================================
             */
            ->leftJoin(
                'employees',
                'employees.id',
                '=',
                'leave_requests.employee_id'
            )

            /*
             * =====================================================
             * JOIN USER
             * =====================================================
             */
            ->leftJoin(
                'users',
                'users.id',
                '=',
                'employees.user_id'
            )

            /*
             * =====================================================
             * JOIN DEPARTMENT
             * =====================================================
             */
            ->leftJoin(
                'departments',
                'departments.id',
                '=',
                'employees.department_id'
            )

            /*
             * =====================================================
             * RELATION
             * =====================================================
             */
            ->with([
                'employee.user',
                'employee.department',
                'employee.position',
                'leaveType',
            ]);

        /*
         * =========================================================
         * SEARCH
         * =========================================================
         */
        if (!empty($this->filters['search'])) {

            $search = trim(
                $this->filters['search']
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'leave_requests.request_number',
                    'LIKE',
                    "%{$search}%"
                )

                ->orWhere(
                    'employees.nik',
                    'LIKE',
                    "%{$search}%"
                )

                ->orWhere(
                    'users.name',
                    'LIKE',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'leaveType',
                    function ($leaveTypeQuery) use ($search) {

                        $leaveTypeQuery->where(
                            'name',
                            'LIKE',
                            "%{$search}%"
                        );
                    }
                );
            });
        }

        /*
         * =========================================================
         * DATE FROM
         * =========================================================
         */
        if (!empty($this->filters['date_from'])) {

            $query->whereDate(
                'leave_requests.start_date',
                '>=',
                $this->filters['date_from']
            );
        }

        /*
         * =========================================================
         * DATE TO
         * =========================================================
         */
        if (!empty($this->filters['date_to'])) {

            $query->whereDate(
                'leave_requests.end_date',
                '<=',
                $this->filters['date_to']
            );
        }

        /*
         * =========================================================
         * DEPARTMENT
         * =========================================================
         */
        if (!empty($this->filters['department_id'])) {

            $query->where(
                'employees.department_id',
                $this->filters['department_id']
            );
        }

        /*
         * =========================================================
         * POSITION
         * =========================================================
         */
        if (!empty($this->filters['position_id'])) {

            $query->where(
                'employees.position_id',
                $this->filters['position_id']
            );
        }

        /*
         * =========================================================
         * LEAVE TYPE
         * =========================================================
         */
        if (!empty($this->filters['leave_type_id'])) {

            $query->where(
                'leave_requests.leave_type_id',
                $this->filters['leave_type_id']
            );
        }

        /*
         * =========================================================
         * STATUS
         * =========================================================
         */
        if (!empty($this->filters['status'])) {

            $query->where(
                'leave_requests.status',
                $this->filters['status']
            );
        }

        return $query;
    }

    /**
     * =========================================================
     * HEADINGS
     * =========================================================
     */
    public function headings(): array
    {
        return [
            'NO',
            'NOMOR PENGAJUAN',
            'NIK',
            'NAMA KARYAWAN',
            'DEPARTMENT',
            'POSITION',
            'JENIS CUTI',
            'TANGGAL MULAI',
            'TANGGAL SELESAI',
            'DURASI',
            'STATUS',
            'TANGGAL PENGAJUAN',
            'ALASAN',
        ];
    }

    /**
     * =========================================================
     * MAPPING
     * =========================================================
     */
    public function map($leave): array
    {
        $this->rowNumber++;

        return [

            /*
             * NO
             */
            $this->rowNumber,

            /*
             * NOMOR PENGAJUAN
             */
            $leave->request_number ?? '-',

            /*
             * NIK
             */
            $leave->employee?->nik ?? '-',

            /*
             * NAMA
             */
            $leave->employee?->user?->name
                ?? 'Tidak diketahui',

            /*
             * DEPARTMENT
             */
            $leave->employee?->department?->name
                ?? '-',

            /*
             * POSITION
             */
            $leave->employee?->position?->name
                ?? '-',

            /*
             * JENIS CUTI
             */
            $leave->leaveType?->name
                ?? '-',

            /*
             * TANGGAL MULAI
             */
            $leave->start_date
                ? $leave->start_date->format('d/m/Y')
                : '-',

            /*
             * TANGGAL SELESAI
             */
            $leave->end_date
                ? $leave->end_date->format('d/m/Y')
                : '-',

            /*
             * DURASI
             */
            (int) (
                $leave->total_days
                ?? 0
            ),

            /*
             * STATUS
             */
            match ($leave->status) {

                LeaveRequest::STATUS_APPROVED =>
                    'Approved',

                LeaveRequest::STATUS_REJECTED =>
                    'Rejected',

                default =>
                    'Pending',
            },

            /*
             * TANGGAL PENGAJUAN
             */
            $leave->submitted_at
                ? $leave->submitted_at->format('d/m/Y H:i')
                : '-',

            /*
             * ALASAN
             */
            $leave->reason ?? '-',
        ];
    }

    /**
     * =========================================================
     * COLUMN WIDTH
     * =========================================================
     */
    public function columnWidths(): array
    {
        return [

            'A' => 7,

            'B' => 22,

            'C' => 18,

            'D' => 28,

            'E' => 24,

            'F' => 22,

            'G' => 22,

            'H' => 16,

            'I' => 16,

            'J' => 12,

            'K' => 15,

            'L' => 22,

            'M' => 45,
        ];
    }

    /**
     * =========================================================
     * EXCEL PROPERTIES
     * =========================================================
     */
    public function properties(): array
    {
        return [

            'creator' =>
                'HRD System',

            'lastModifiedBy' =>
                'HRD System',

            'title' =>
                'Laporan Pengajuan Cuti',

            'description' =>
                'Laporan pengajuan cuti karyawan berdasarkan filter laporan HRD.',

            'subject' =>
                'Laporan Pengajuan Cuti',

            'keywords' =>
                'HRD, cuti, leave, employee, department',

            'category' =>
                'HRD Report',
        ];
    }

    /**
     * =========================================================
     * EVENTS
     * =========================================================
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (
                AfterSheet $event
            ) {

                $sheet =
                    $event->sheet->getDelegate();

                /*
                 * =================================================
                 * DIMENSIONS
                 * =================================================
                 */
                $highestRow =
                    $sheet->getHighestRow();

                $highestColumn =
                    $sheet->getHighestColumn();

                /*
                 * =================================================
                 * FREEZE HEADER
                 * =================================================
                 */
                $sheet->freezePane('A2');

                /*
                 * =================================================
                 * AUTO FILTER
                 * =================================================
                 */
                $sheet->setAutoFilter(
                    "A1:{$highestColumn}{$highestRow}"
                );

                /*
                 * =================================================
                 * HEADER STYLE
                 * =================================================
                 */
                $sheet
                    ->getStyle(
                        "A1:{$highestColumn}1"
                    )
                    ->applyFromArray([

                        'font' => [

                            'bold' => true,

                            'color' => [
                                'rgb' => 'FFFFFF',
                            ],

                            'size' => 11,
                        ],

                        'fill' => [

                            'fillType' =>
                                Fill::FILL_SOLID,

                            'startColor' => [
                                'rgb' => '173F67',
                            ],
                        ],

                        'alignment' => [

                            'horizontal' =>
                                Alignment::HORIZONTAL_CENTER,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,

                            'wrapText' => true,
                        ],

                        'borders' => [

                            'bottom' => [

                                'borderStyle' =>
                                    Border::BORDER_MEDIUM,

                                'color' => [
                                    'rgb' => '102E4A',
                                ],
                            ],
                        ],
                    ]);

                /*
                 * =================================================
                 * HEADER HEIGHT
                 * =================================================
                 */
                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(30);

                /*
                 * =================================================
                 * BODY STYLE
                 * =================================================
                 */
                if ($highestRow >= 2) {

                    $sheet
                        ->getStyle(
                            "A2:{$highestColumn}{$highestRow}"
                        )
                        ->applyFromArray([

                            'font' => [

                                'name' =>
                                    'Calibri',

                                'size' =>
                                    10,
                            ],

                            'alignment' => [

                                'vertical' =>
                                    Alignment::VERTICAL_CENTER,
                            ],

                            'borders' => [

                                'bottom' => [

                                    'borderStyle' =>
                                        Border::BORDER_HAIR,

                                    'color' => [
                                        'rgb' => 'D9E2EC',
                                    ],
                                ],
                            ],
                        ]);
                }

                /*
                 * =================================================
                 * CENTER COLUMN
                 * =================================================
                 */
                if ($highestRow >= 2) {

                    /*
                     * NO
                     */
                    $sheet
                        ->getStyle(
                            "A2:A{$highestRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                     * DATE + DURATION + STATUS
                     */
                    $sheet
                        ->getStyle(
                            "H2:L{$highestRow}"
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );
                }

                /*
                 * =================================================
                 * WRAP TEXT
                 * =================================================
                 */
                $sheet
                    ->getStyle(
                        "A1:{$highestColumn}{$highestRow}"
                    )
                    ->getAlignment()
                    ->setVertical(
                        Alignment::VERTICAL_CENTER
                    );

                if ($highestRow >= 2) {

                    $sheet
                        ->getStyle(
                            "M2:M{$highestRow}"
                        )
                        ->getAlignment()
                        ->setWrapText(true);
                }

                /*
                 * =================================================
                 * ALTERNATING DEPARTMENT
                 * =================================================
                 *
                 * Department A
                 * Department A
                 * Department A
                 *
                 * Department B
                 * Department B
                 *
                 * Department C
                 * Department C
                 */
                $previousDepartment = null;

                $departmentIndex = 0;

                $departmentColors = [

                    'F5F9FD',

                    'FFFFFF',
                ];

                if ($highestRow >= 2) {

                    for (
                        $row = 2;
                        $row <= $highestRow;
                        $row++
                    ) {

                        $department =
                            trim(
                                (string) $sheet
                                    ->getCell(
                                        "E{$row}"
                                    )
                                    ->getValue()
                            );

                        /*
                         * Department berubah
                         */
                        if (
                            $previousDepartment !== null
                            &&
                            $department !== $previousDepartment
                        ) {

                            $departmentIndex++;
                        }

                        $background =
                            $departmentColors[
                                $departmentIndex % 2
                            ];

                        /*
                         * Apply background
                         */
                        $sheet
                            ->getStyle(
                                "A{$row}:{$highestColumn}{$row}"
                            )
                            ->applyFromArray([

                                'fill' => [

                                    'fillType' =>
                                        Fill::FILL_SOLID,

                                    'startColor' => [

                                        'rgb' =>
                                            $background,
                                    ],
                                ],
                            ]);

                        $previousDepartment =
                            $department;
                    }
                }

                /*
                 * =================================================
                 * DEPARTMENT EMPHASIS
                 * =================================================
                 */
                if ($highestRow >= 2) {

                    $sheet
                        ->getStyle(
                            "E2:E{$highestRow}"
                        )
                        ->getFont()
                        ->setBold(true);
                }

                /*
                 * =================================================
                 * EMPLOYEE NAME EMPHASIS
                 * =================================================
                 */
                if ($highestRow >= 2) {

                    $sheet
                        ->getStyle(
                            "D2:D{$highestRow}"
                        )
                        ->getFont()
                        ->setBold(true);
                }

                /*
                 * =================================================
                 * STATUS STYLE
                 * =================================================
                 */
                if ($highestRow >= 2) {

                    for (
                        $row = 2;
                        $row <= $highestRow;
                        $row++
                    ) {

                        $status =
                            strtolower(
                                trim(
                                    (string) $sheet
                                        ->getCell(
                                            "K{$row}"
                                        )
                                        ->getValue()
                                )
                            );

                        $statusStyle =
                            $sheet
                                ->getStyle(
                                    "K{$row}"
                                );

                        /*
                         * Bold
                         */
                        $statusStyle
                            ->getFont()
                            ->setBold(true);

                        /*
                         * Center
                         */
                        $statusStyle
                            ->getAlignment()
                            ->setHorizontal(
                                Alignment::HORIZONTAL_CENTER
                            );

                        /*
                         * APPROVED
                         */
                        if (
                            $status === 'approved'
                        ) {

                            $statusStyle
                                ->getFont()
                                ->getColor()
                                ->setRGB(
                                    '008A5A'
                                );
                        }

                        /*
                         * REJECTED
                         */
                        elseif (
                            $status === 'rejected'
                        ) {

                            $statusStyle
                                ->getFont()
                                ->getColor()
                                ->setRGB(
                                    'C62828'
                                );
                        }

                        /*
                         * PENDING
                         */
                        else {

                            $statusStyle
                                ->getFont()
                                ->getColor()
                                ->setRGB(
                                    'B7791F'
                                );
                        }
                    }
                }

                /*
                 * =================================================
                 * TABLE BORDER
                 * =================================================
                 *
                 * JANGAN menggunakan:
                 *
                 * getInsideHorizontal()
                 *
                 * karena method tersebut tidak tersedia
                 * pada versi PhpSpreadsheet yang digunakan.
                 *
                 * Gunakan applyFromArray().
                 */
                $sheet
                    ->getStyle(
                        "A1:{$highestColumn}{$highestRow}"
                    )
                    ->applyFromArray([

                        'borders' => [

                            'insideHorizontal' => [

                                'borderStyle' =>
                                    Border::BORDER_HAIR,

                                'color' => [

                                    'rgb' =>
                                        'D9E2EC',
                                ],
                            ],

                            'insideVertical' => [

                                'borderStyle' =>
                                    Border::BORDER_HAIR,

                                'color' => [

                                    'rgb' =>
                                        'E5EAF0',
                                ],
                            ],

                            'outline' => [

                                'borderStyle' =>
                                    Border::BORDER_THIN,

                                'color' => [

                                    'rgb' =>
                                        'C9D5E2',
                                ],
                            ],
                        ],
                    ]);

                /*
                 * =================================================
                 * PAGE SETUP
                 * =================================================
                 */
                $sheet
                    ->getPageSetup()
                    ->setOrientation(
                        PageSetup::ORIENTATION_LANDSCAPE
                    );

                $sheet
                    ->getPageSetup()
                    ->setPaperSize(
                        PageSetup::PAPERSIZE_A4
                    );

                $sheet
                    ->getPageSetup()
                    ->setFitToWidth(1);

                $sheet
                    ->getPageSetup()
                    ->setFitToHeight(0);

                /*
                 * =================================================
                 * PAGE MARGIN
                 * =================================================
                 */
                $sheet
                    ->getPageMargins()
                    ->setTop(0.4);

                $sheet
                    ->getPageMargins()
                    ->setBottom(0.4);

                $sheet
                    ->getPageMargins()
                    ->setLeft(0.3);

                $sheet
                    ->getPageMargins()
                    ->setRight(0.3);

                /*
                 * =================================================
                 * PRINT TITLE
                 * =================================================
                 *
                 * Header akan tetap muncul ketika
                 * Excel dicetak ke halaman berikutnya.
                 */
                $sheet
                    ->getPageSetup()
                    ->setRowsToRepeatAtTopByStartAndEnd(
                        1,
                        1
                    );

                /*
                 * =================================================
                 * PRINT AREA
                 * =================================================
                 */
                $sheet
                    ->getPageSetup()
                    ->setPrintArea(
                        "A1:{$highestColumn}{$highestRow}"
                    );
            },
        ];
    }
}