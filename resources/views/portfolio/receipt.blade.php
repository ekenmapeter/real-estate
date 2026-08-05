@extends('layouts.main')

@section('title', 'Cycle Receipt ' . $cycle->receipt_number . ' | ' . site_name())

@section('content')
<div class="container py-5" style="background-color: #f8fafc; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('portfolio.index') }}" class="text-decoration-none text-muted fw-semibold small">
                    <i class="bi bi-arrow-left me-1"></i> Back to My Portfolio
                </a>
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-3">
                    <i class="bi bi-printer me-1"></i> Print Receipt
                </button>
            </div>

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white p-4 p-md-5" id="printableReceipt">
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                    <div>
                        <h3 class="fw-bold text-primary mb-1">{{ site_name() }}</h3>
                        <p class="text-muted small mb-0">Official Project Share Cycle Certificate & Receipt</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success px-3 py-2 fs-6 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> {{ strtoupper($cycle->status) }}</span>
                        <span class="text-muted small d-block mt-1">Receipt #: <strong>{{ $cycle->receipt_number }}</strong></span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-6">
                        <span class="text-muted small d-block">Investor Name</span>
                        <h6 class="fw-bold text-dark mb-0">{{ $cycle->user->name ?? 'Valued Investor' }}</h6>
                        <span class="text-muted small">{{ $cycle->user->email ?? '' }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted small d-block">Project Name</span>
                        <h6 class="fw-bold text-dark mb-0">{{ $cycle->project->title ?? 'Property Project' }}</h6>
                        <span class="text-muted small"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $cycle->project->location ?? '' }}</span>
                    </div>
                </div>

                <div class="bg-light rounded-4 p-4 mb-4">
                    <h6 class="fw-bold text-dark mb-3">Share Cycle Breakdown</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Cycle Reference Code</td>
                                    <td class="fw-bold text-end py-2 text-dark">{{ $cycle->cycle_code }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Earning Duration</td>
                                    <td class="fw-bold text-end py-2 text-primary">{{ $cycle->duration_label }} ({{ $cycle->duration_days }} Days)</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Shares Owned</td>
                                    <td class="fw-bold text-end py-2 text-dark">{{ $cycle->shares_owned }} Shares</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Share Price</td>
                                    <td class="fw-bold text-end py-2 text-dark">{{ number_format($cycle->share_price, 2) }} AVC</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Total Principal Investment</td>
                                    <td class="fw-bold text-end py-2 text-dark">{{ number_format($cycle->total_purchase_amount, 2) }} AVC</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Target Return Percentage</td>
                                    <td class="fw-bold text-end py-2 text-success">{{ number_format($cycle->target_earnings_pct, 2) }}%</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="text-muted py-2">Projected / Earned Returns</td>
                                    <td class="fw-bold text-end py-2 text-success">+{{ number_format($cycle->projected_earnings, 2) }} AVC</td>
                                </tr>
                                <tr class="bg-white border rounded">
                                    <td class="fw-bold py-3 text-dark fs-5">Total Completion Value</td>
                                    <td class="fw-bold text-end py-3 text-primary fs-4">{{ number_format($cycle->completion_value, 2) }} AVC</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row g-3 text-muted small border-top pt-4">
                    <div class="col-6">
                        <span>Purchased On: <strong>{{ $cycle->purchased_at ? $cycle->purchased_at->format('F d, Y - H:i') : 'N/A' }}</strong></span>
                    </div>
                    <div class="col-6 text-end">
                        <span>Activated On: <strong>{{ $cycle->activated_at ? $cycle->activated_at->format('F d, Y - H:i') : 'Pending Threshold' }}</strong></span>
                    </div>
                    <div class="col-6">
                        <span>Completion Target: <strong>{{ $cycle->completion_date ? $cycle->completion_date->format('F d, Y') : 'N/A' }}</strong></span>
                    </div>
                    <div class="col-6 text-end">
                        <span>Payout Credit Status: <strong class="text-success">{{ $cycle->earnings_credited_at ? 'Credited to AVC Balance on ' . $cycle->earnings_credited_at->format('M d, Y') : 'Scheduled upon completion' }}</strong></span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <p class="mb-0">This document confirms your ownership of project shares under the {{ site_name() }} Project Marketplace. All financial distributions are governed by the terms of service.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
