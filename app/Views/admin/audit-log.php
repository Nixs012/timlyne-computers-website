<?php require __DIR__ . '/layout_header.php'; ?>

<h2>Audit Log</h2>

<div style="display:flex; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem;">
    <a href="/admin/audit-log" class="btn btn--ghost">All</a>
    <?php foreach ($entities as $entityOption): ?>
        <a href="/admin/audit-log?entity=<?= rawurlencode($entityOption) ?>" class="btn btn--ghost"><?= \App\Core\Security::e(ucfirst($entityOption)) ?></a>
    <?php endforeach; ?>
</div>

<?php if ($entity !== null): ?>
    <p style="margin-bottom:0.75rem; color:#64748b;">Filtered by <?= \App\Core\Security::e($entity) ?>.</p>
<?php endif; ?>

<table class="data-table" style="width:100%; text-align:left; border-collapse:collapse;">
    <thead>
        <tr>
            <th>Timestamp</th>
            <th>Admin ID</th>
            <th>Action</th>
            <th>Entity</th>
            <th>Entity ID</th>
            <th>Details</th>
            <th>IP Address</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= \App\Core\Security::e($log['created_at']) ?></td>
            <td><?= \App\Core\Security::e($log['admin_id'] ?? '') ?></td>
            <td><?= \App\Core\Security::e($log['action']) ?></td>
            <td><?= \App\Core\Security::e($log['entity']) ?></td>
            <td><?= \App\Core\Security::e($log['entity_id'] ?? '') ?></td>
            <td><?= \App\Core\Security::e($log['details'] ?? '') ?></td>
            <td><?= \App\Core\Security::e($log['ip_address'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($logs)): ?>
        <tr><td colspan="7">No audit entries found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<div style="margin-top:1rem; display:flex; justify-content:space-between; align-items:center;">
    <div style="color:#64748b; font-size:0.875rem;">Showing <?= (int)$start ?>-<?= (int)$end ?> of <?= (int)$total ?> entries</div>
    <div style="display:flex; gap:0.5rem;">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?><?= $entity !== null ? '&entity=' . rawurlencode($entity) : '' ?>" class="btn btn--ghost">Previous</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?><?= $entity !== null ? '&entity=' . rawurlencode($entity) : '' ?>" class="btn btn--ghost">Next</a>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>