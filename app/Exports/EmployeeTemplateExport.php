<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeTemplateExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
{
    /**
     * ==========================================================
     * HEADER TEMPLATE
     * ==========================================================
     */
    public function headings(): array
    {
        return [

            'nik',

            'name',

            'email',

            'phone',

            'password',

            'role',

            'department_code',

            'position_code',

            'join_date',

            'birth_date',

            'gender',

            'address',

            'supervisor_nik',

            'manager_nik',

            'director_nik',

            'status',

        ];
    }


    /**
     * ==========================================================
     * CONTOH DATA
     * ==========================================================
     */
    public function array(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE
            |--------------------------------------------------------------------------
            */

            [
                'EMP001',
                'Budi Santoso',
                'budi@gmail.com',
                '081234567890',
                'Budi12345',
                'Employee',
                'TGI-ENG',
                'ENG',
                '2026-08-01',
                '1998-05-12',
                'Male',
                'Cilegon',
                'SPV001',
                'MGR001',
                'DIR001',
                'Active',
            ],


            /*
            |--------------------------------------------------------------------------
            | SUPERVISOR
            |--------------------------------------------------------------------------
            */

            [
                'SPV001',
                'Andi Wijaya',
                'andi@gmail.com',
                '081234567891',
                'Andi12345',
                'Employee',
                'TGI-ENG',
                'SPV',
                '2025-01-01',
                '1990-03-15',
                'Male',
                'Cilegon',
                '',
                'MGR001',
                'DIR001',
                'Active',
            ],


            /*
            |--------------------------------------------------------------------------
            | MANAGER
            |--------------------------------------------------------------------------
            */

            [
                'MGR001',
                'Dedi Saputra',
                'dedi@gmail.com',
                '081234567892',
                'Dedi12345',
                'Manager',
                'TGI-ENG',
                'MNJ',
                '2024-01-01',
                '1985-06-20',
                'Male',
                'Cilegon',
                '',
                '',
                'DIR001',
                'Active',
            ],


            /*
            |--------------------------------------------------------------------------
            | DIRECTOR
            |--------------------------------------------------------------------------
            */

            [
                'DIR001',
                'Agus Setiawan',
                'agus@gmail.com',
                '081234567893',
                'Agus12345',
                'Manager',
                'TGI-ENG',
                'DKT',
                '2020-01-01',
                '1978-08-10',
                'Male',
                'Cilegon',
                '',
                '',
                '',
                'Active',
            ],

        ];
    }


    /**
     * ==========================================================
     * STYLING EXCEL
     * ==========================================================
     */
    public function styles(Worksheet $sheet): ?array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | HEADER
            |--------------------------------------------------------------------------
            */

            1 => [

                'font' => [

                    'bold' => true,

                    'color' => [
                        'rgb' => 'FFFFFF',
                    ],

                    'size' => 11,

                ],

                'fill' => [

                    'fillType' => Fill::FILL_SOLID,

                    'startColor' => [
                        'rgb' => '1F4E78',
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

                    'allBorders' => [

                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' => 'D9E2F3',
                        ],

                    ],

                ],

            ],

        ];
    }
}