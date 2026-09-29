<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="margin-bottom: 2rem;">
    <h2>Contact Messages</h2>
</div>

<?php if (\App\Core\Session::get('success')): ?>
    <div style="background: #10b981; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::get('success')) ?>
    </div>
<?php endif; ?>
<?php if (\App\Core\Session::get('error')): ?>
    <div style="background: #ef4444; color: white; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">
        <?= \App\Core\Security::e(\App\Core\Session::get('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div style="margin-bottom: 1rem; display: flex; gap: 0.5rem; align-items: center;">
        <strong>Filter:</strong>
        <a href="/admin/messages" class="btn btn--ghost" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">All (<?= array_sum($counts) ?>)</a>
        <a href="/admin/messages?status=new" class="btn btn--ghost" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">New (<?= $counts['new'] ?>)</a>
        <a href="/admin/messages?status=read" class="btn btn--ghost" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Read (<?= $counts['read'] ?>)</a>
        <a href="/admin/messages?status=archived" class="btn btn--ghost" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Archived (<?= $counts['archived'] ?>)</a>
    </div>

    <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background: #f1f5f9;">
            <tr>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Date</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">From</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Subject</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Status</th>
                <th style="padding: 1rem; border-bottom: 1px solid #eee;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $m): ?>
            <tr style="border-top: 1px solid #e2e8f0;">
                <td style="padding: 1rem; border-bottom: 1px solid #eee; font-size: 0.85rem;"><?= date('Y-m-d H:i', strtotime($m['created_at'])) ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <strong><?= \App\Core\Security::e($m['name']) ?></strong><br>
                    <small><?= \App\Core\Security::e($m['email']) ?></small>
                </td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;"><?= \App\Core\Security::e($m['subject'] ?? '(No Subject)') ?></td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <?php if ($m['status'] === 'new'): ?>
                        <span style="color: #2563eb; font-weight: 600;">New</span>
                    <?php elseif ($m['status'] === 'read'): ?>
                        <span style="color: #64748b; font-weight: 600;">Read</span>
                    <?php else: ?>
                        <span style="color: #94a3b8; font-weight: 600;">Archived</span>
                    <?php endif; ?>
                </td>
                <td style="padding: 1rem; border-bottom: 1px solid #eee;">
                    <a href="/admin/messages/<?= $m['id'] ?>" style="color: #3b82f6; text-decoration: none;">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($messages)): ?>
            <tr>
                <td colspan="5" style="padding: 2rem; text-align: center; color: #64748b;">No messages found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
