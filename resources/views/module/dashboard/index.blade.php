@extends('main')
@section('title', '| Dashboard')

@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-md-flex align-items-center">
                    <div>
                        <h4 class="card-title">Sales Summary</h4>
                        <h6 class="card-subtitle">Ample admin Vs Pixel admin</h6>
                    </div>
                    <div class="ms-auto d-flex no-block align-items-center">
                        <ul class="list-inline dl d-flex align-items-center m-r-15 m-b-0">
                            <li class="list-inline-item d-flex align-items-center text-info"><i class="fa fa-circle font-10 me-1"></i> Ample
                            </li>
                            <li class="list-inline-item d-flex align-items-center text-primary"><i class="fa fa-circle font-10 me-1"></i> Pixel
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="amp-pxl mt-4" style="height: 350px;">
                    <div class="chartist-tooltip"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Weekly Stats</h4>
                <h6 class="card-subtitle">Average sales</h6>
                <div class="mt-5 pb-3 d-flex align-items-center">
                    <span class="btn btn-primary btn-circle d-flex align-items-center">
                        <i class="mdi mdi-cart-outline fs-4" ></i>
                    </span>
                    <div class="ms-3">
                        <h5 class="mb-0 fw-bold">Top Sales</h5>
                        <span class="text-muted fs-6">Johnathan Doe</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-muted">+68%</span>
                    </div>
                </div>
                <div class="py-3 d-flex align-items-center">
                    <span class="btn btn-warning btn-circle d-flex align-items-center">
                        <i class="mdi mdi-star-circle fs-4" ></i>
                    </span>
                    <div class="ms-3">
                        <h5 class="mb-0 fw-bold">Best Seller</h5>
                        <span class="text-muted fs-6">MaterialPro Admin</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-muted">+68%</span>
                    </div>
                </div>
                <div class="py-3 d-flex align-items-center">
                    <span class="btn btn-success btn-circle d-flex align-items-center">
                        <i class="mdi mdi-comment-multiple-outline text-white fs-4" ></i>
                    </span>
                    <div class="ms-3">
                        <h5 class="mb-0 fw-bold">Most Commented</h5>
                        <span class="text-muted fs-6">Ample Admin</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-muted">+68%</span>
                    </div>
                </div>
                <div class="py-3 d-flex align-items-center">
                    <span class="btn btn-info btn-circle d-flex align-items-center">
                        <i class="mdi mdi-diamond fs-4 text-white" ></i>
                    </span>
                    <div class="ms-3">
                        <h5 class="mb-0 fw-bold">Top Budgets</h5>
                        <span class="text-muted fs-6">Sunil Joshi</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-muted">+15%</span>
                    </div>
                </div>

                <div class="pt-3 d-flex align-items-center">
                    <span class="btn btn-danger btn-circle d-flex align-items-center">
                        <i class="mdi mdi-content-duplicate fs-4 text-white" ></i>
                    </span>
                    <div class="ms-3">
                        <h5 class="mb-0 fw-bold">Best Designer</h5>
                        <span class="text-muted fs-6">Nirav Joshi</span>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-muted">+90%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/libs/chartist/dist/chartist.min.js') }}"></script>
<script src="{{ asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('dist/js/pages/dashboards/dashboard1.js') }}"></script>
@endpush