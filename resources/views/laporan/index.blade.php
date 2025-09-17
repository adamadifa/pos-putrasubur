@extends('layouts.pos')

@section('title', 'Laporan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Laporan</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Laporan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Pilih Jenis Laporan</h4>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div
                                                class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                                <i class="ti ti-chart-line"></i>
                                            </div>
                                        </div>
                                        <h5 class="font-size-16">Laporan Kas Bank</h5>
                                        <p class="text-muted">Laporan arus kas dan bank</p>
                                        <a href="{{ route('laporan.kas-bank.index') }}" class="btn btn-primary btn-sm">Lihat
                                            Laporan</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div
                                                class="avatar-title rounded-circle bg-success-subtle text-success font-size-20">
                                                <i class="ti ti-package"></i>
                                            </div>
                                        </div>
                                        <h5 class="font-size-16">Laporan Stok</h5>
                                        <p class="text-muted">Laporan stok produk</p>
                                        <a href="{{ route('laporan.stok.index') }}" class="btn btn-success btn-sm">Lihat
                                            Laporan</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div class="avatar-title rounded-circle bg-info-subtle text-info font-size-20">
                                                <i class="ti ti-credit-card"></i>
                                            </div>
                                        </div>
                                        <h5 class="font-size-16">Laporan Piutang</h5>
                                        <p class="text-muted">Laporan piutang pelanggan</p>
                                        <a href="{{ route('laporan.piutang.index') }}" class="btn btn-info btn-sm">Lihat
                                            Laporan</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="avatar-sm mx-auto mb-3">
                                            <div
                                                class="avatar-title rounded-circle bg-warning-subtle text-warning font-size-20">
                                                <i class="ti ti-receipt"></i>
                                            </div>
                                        </div>
                                        <h5 class="font-size-16">Laporan Hutang</h5>
                                        <p class="text-muted">Laporan hutang supplier</p>
                                        <a href="{{ route('laporan.hutang.index') }}" class="btn btn-warning btn-sm">Lihat
                                            Laporan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
















