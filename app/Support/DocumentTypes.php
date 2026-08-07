<?php

namespace App\Support;

use App\Models\Document;
use App\Models\ProjectShareCycle;

class DocumentTypes
{
    public const CATEGORIES = [
        'project_investment' => 'Project Investments',
        'property' => 'Properties',
        'finance' => 'Finance',
        'marketplace' => 'AVC Marketplace',
        'verification' => 'Verification & Legal',
        'statement' => 'Statements & Reports',
    ];

    public const STATUS_COLORS = [
        'active' => ['#f0fdf4', '#16a34a'],
        'completed' => ['#f0fdf4', '#16a34a'],
        'new' => ['#eff6ff', '#2563eb'],
        'verified' => ['#f0fdfa', '#0d9488'],
        'pending' => ['#fffbeb', '#d97706'],
        'archived' => ['#f1f5f9', '#64748b'],
        'rejected' => ['#fef2f2', '#dc2626'],
    ];

    /**
     * Registry: document_type => [category, pdf view, default status, title suffix fn].
     */
    public const TYPES = [
        // Project Investment
        'investment_agreement' => ['project_investment', 'documents.pdf.investment-agreement', 'active'],
        'share_certificate' => ['project_investment', 'documents.pdf.share-certificate', 'active'],
        'ownership_certificate' => ['project_investment', 'documents.pdf.ownership-certificate', 'verified'],
        'project_update' => ['project_investment', 'documents.pdf.project-update', 'new'],
        'cycle_receipt' => ['project_investment', 'documents.pdf.cycle-receipt', 'completed'],
        // Property
        'property_contract' => ['property', 'documents.pdf.property-contract', 'active'],
        'rental_agreement' => ['property', 'documents.pdf.rental-agreement', 'active'],
        'property_receipt' => ['property', 'documents.pdf.property-receipt', 'completed'],
        // Finance
        'deposit_receipt' => ['finance', 'documents.pdf.deposit-receipt', 'completed'],
        'deposit_confirmation' => ['finance', 'documents.pdf.deposit-confirmation', 'active'],
        'withdrawal_request_receipt' => ['finance', 'documents.pdf.withdrawal-receipt', 'pending'],
        'withdrawal_confirmation' => ['finance', 'documents.pdf.withdrawal-confirmation', 'completed'],
        'finance_request_receipt' => ['finance', 'documents.pdf.finance-request-receipt', 'completed'],
        'payment_confirmation' => ['finance', 'documents.pdf.payment-confirmation', 'completed'],
        // AVC Marketplace
        'buy_order_receipt' => ['marketplace', 'documents.pdf.order-receipt', 'completed'],
        'sell_order_receipt' => ['marketplace', 'documents.pdf.order-receipt', 'completed'],
        'escrow_agreement' => ['marketplace', 'documents.pdf.escrow-agreement', 'active'],
        'escrow_completion_certificate' => ['marketplace', 'documents.pdf.escrow-certificate', 'completed'],
        // Verification & Legal
        'kyc_verification_certificate' => ['verification', 'documents.pdf.kyc-certificate', 'verified'],
        'identity_report' => ['verification', 'documents.pdf.identity-report', 'verified'],
        // Statements & Reports
        'monthly_statement' => ['statement', 'documents.pdf.monthly-statement', 'new'],
        'annual_statement' => ['statement', 'documents.pdf.monthly-statement', 'new'],
        'tax_summary' => ['statement', 'documents.pdf.monthly-statement', 'new'],
    ];

    public static function category(string $type): string
    {
        return static::TYPES[$type][0] ?? 'statement';
    }

    public static function view(string $type): string
    {
        return static::TYPES[$type][1] ?? 'documents.pdf.monthly-statement';
    }

    public static function defaultStatus(string $type): string
    {
        return static::TYPES[$type][2] ?? 'new';
    }

    public static function isKnown(string $type): bool
    {
        return isset(static::TYPES[$type]);
    }

    public static function title(string $type, $related): string
    {
        return match ($type) {
            'investment_agreement' => 'Investment Agreement',
            'share_certificate' => 'Share Certificate',
            'ownership_certificate' => 'Ownership Certificate',
            'project_update' => 'Project Update',
            'cycle_receipt' => 'Share Cycle Receipt',
            'property_contract' => 'Property Purchase Contract',
            'rental_agreement' => 'Lease Agreement',
            'property_receipt' => 'Property Receipt',
            'deposit_receipt' => 'Deposit Receipt',
            'deposit_confirmation' => 'Deposit Confirmation',
            'withdrawal_request_receipt' => 'Withdrawal Request Receipt',
            'withdrawal_confirmation' => 'Withdrawal Confirmation',
            'finance_request_receipt' => 'Finance Request Receipt',
            'payment_confirmation' => 'Payment Confirmation',
            'buy_order_receipt' => 'AVC Buy Order Receipt',
            'sell_order_receipt' => 'AVC Sell Order Receipt',
            'escrow_agreement' => 'Escrow Agreement',
            'escrow_completion_certificate' => 'Escrow Completion Certificate',
            'kyc_verification_certificate' => 'KYC Verification Certificate',
            'identity_report' => 'Identity Verification Report',
            'monthly_statement' => 'Monthly Finance Statement',
            'annual_statement' => 'Annual Finance Statement',
            'tax_summary' => 'Tax Summary',
            default => ucwords(str_replace('_', ' ', $type)),
        };
    }

    /**
     * Human-readable reference of the related record, e.g. "Ocean View Villas (PRJ-001)".
     */
    public static function relatedLabel($related): string
    {
        if ($related instanceof ProjectShareCycle) {
            return $related->project?->title . ' (Cycle ' . $related->cycle_code . ')';
        }

        if (is_object($related) && method_exists($related, 'ref')) {
            return $related->ref();
        }

        $ref = $related->listing_number ?? $related->deposit_code ?? $related->withdrawal_code ?? $related->reference ?? $related->cycle_code ?? null;
        $title = $related->title ?? $related->subject ?? null;

        if ($title && $ref) {
            return $title . ' (' . $ref . ')';
        }

        return $ref ?: ($title ?: ('#' . $related->id));
    }
}
