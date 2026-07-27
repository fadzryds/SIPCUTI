@extends('manager.layouts.app')

@section('title','Dashboard')

@section('content')

<div class="dashboard">


    {{-- HERO --}}
    <section class="hero-card">

        <div class="hero-content">

            <span class="hero-label">
                <i class="fa-solid fa-chart-line"></i>
                Manager Panel
            </span>


            <h1>
                Selamat Datang,
                {{ $manager->user->name }}
            </h1>


            <p>
                Kelola proses approval cuti karyawan,
                pantau aktivitas dan keputusan secara efektif.
            </p>


            <a href="#" class="hero-button">
                <i class="fa-solid fa-file-circle-check"></i>
                Kelola Approval
            </a>

        </div>



        <div class="hero-avatar">

            <div class="avatar-circle">

                {{ strtoupper(substr($manager->user->name,0,1)) }}

            </div>


        </div>


    </section>




    {{-- STATISTIC --}}

    <section class="dashboard-summary">


        <div class="summary-card pending">

            <div class="summary-icon">

                <i class="fa-solid fa-clock"></i>

            </div>

            <div>

                <span>Pending</span>

                <h2>
                    {{ $pending }}
                </h2>

                <small>
                    Menunggu approval
                </small>

            </div>

        </div>




        <div class="summary-card approved">

            <div class="summary-icon">

                <i class="fa-solid fa-circle-check"></i>

            </div>


            <div>

                <span>Approved</span>

                <h2>
                    {{ $approved }}
                </h2>

                <small>
                    Telah disetujui
                </small>

            </div>

        </div>




        <div class="summary-card rejected">


            <div class="summary-icon">

                <i class="fa-solid fa-circle-xmark"></i>

            </div>


            <div>

                <span>Rejected</span>

                <h2>
                    {{ $rejected }}
                </h2>


                <small>
                    Ditolak
                </small>

            </div>


        </div>




        <div class="summary-card employee">


            <div class="summary-icon">

                <i class="fa-solid fa-users"></i>

            </div>


            <div>

                <span>Karyawan</span>

                <h2>
                    {{ $totalSubordinates }}
                </h2>


                <small>
                    Total karyawan
                </small>

            </div>


        </div>

    </section>

    {{-- TABLE --}}

    <section class="table-card">


        <div class="table-header">

            <div>

                <h3>
                    Pengajuan Terbaru
                </h3>

                <p>
                    Daftar cuti terakhir dari karyawan
                </p>

            </div>


            <button>
                Lihat Semua
            </button>


        </div>



        <div class="table-wrapper">


        <table>


            <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>



            <tbody>


            @forelse($leaveRequests as $leave)


            <tr>


                <td>
                    {{ $loop->iteration }}
                </td>


                <td>

                    <strong>
                    {{ $leave->employee->user->name }}
                    </strong>

                </td>


                <td>
                    {{ $leave->leaveType->name }}
                </td>



                <td>
                    {{ $leave->start_date->format('d M Y') }}
                </td>



                <td>


                <span class="status">

                    {{ $leave->managerApproval->status ?? '-' }}

                </span>


                </td>


                <td>

                    <a class="detail-btn">
                        Detail
                    </a>

                </td>



            </tr>


            @empty


            <tr>

                <td colspan="6" class="empty">

                    Belum ada pengajuan

                </td>

            </tr>


            @endforelse


            </tbody>


        </table>


        </div>


    </section>



</div>


@endsection