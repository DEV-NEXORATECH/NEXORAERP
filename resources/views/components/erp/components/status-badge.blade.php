@props([
    'status' => 'draft',
    'label'  => null,
])

@php
    $cleanStatus = strtolower(trim((string) $status));
    $displayLabel = $label ?? ucwords(str_replace(['_', '-'], ' ', (string) $status));

    // Semantic action mapping:
    // Approved / Active / Paid / Completed / Success / Won -> Green (#15803D)
    // Rejected / Cancelled / Void / Overdue / Critical / Failed / Absent -> Red (#DC2626)
    // Pending / Warning / Due Soon / Hold / Late -> Amber (#D97706)
    // In Progress / Review / Sent / Open / Partial -> Blue (#0369A1)
    // Draft / Inactive / Closed / Archived -> Gray (#6B7280)

    $badgeClass = match($cleanStatus) {
        'approved', 'active', 'paid', 'completed', 'success', 'won', 'done', 'present', 'matched'
            => 'border-[#15803D]/30 bg-[#15803D]/10 text-[#15803D]',
        
        'rejected', 'cancelled', 'void', 'overdue', 'critical', 'danger', 'failed', 'absent', 'unmatched'
            => 'border-[#DC2626]/30 bg-[#DC2626]/10 text-[#DC2626]',
        
        'pending', 'warning', 'due_soon', 'hold', 'on_hold', 'late', 'partially_matched', 'partial'
            => 'border-[#D97706]/30 bg-[#D97706]/10 text-[#D97706]',
        
        'in_progress', 'progress', 'review', 'sent', 'open', 'planning', 'qualified', 'proposal'
            => 'border-[#0369A1]/30 bg-[#0369A1]/10 text-[#0369A1]',
        
        default
            => 'border-[#D1D5DB] bg-[#F3F4F6] text-[#475569]',
    };
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $badgeClass }}">
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-75"></span>
    <span>{{ $displayLabel }}</span>
</span>
