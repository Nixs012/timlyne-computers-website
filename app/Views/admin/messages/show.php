<?php require __DIR__ . '/../layout_header.php'; ?>

<div class="header-action-bar" style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Message Details</h2>
    <a href="/admin/messages" class="btn btn--ghost">Back to List</a>
</div>

<div class="card" style="max-width: 800px;">
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="margin: 0;"><?= \App\Core\Security::e($message['name']) ?></h3>
            <p style="margin: 0; color: #64748b;"><?= \App\Core\Security::e($message['email']) ?> | <?= \App\Core\Security::e($message['phone'] ?? 'No phone') ?></p>
        </div>
        <div>
            <span style="font-size: 0.8rem; padding: 0.25rem 0.5rem; border-radius: 4px; background: #f1f5f9;">
                Status: <?= ucfirst(\App\Core\Security::e($message['status'])) ?>
            </span>
        </div>
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label style="font-weight: 600; display: block; margin-bottom: 0.5rem;">Subject</label>
        <p style="margin-bottom: 1.5rem;"><?= \App\Core\Security::e($message['subject'] ?? '(No Subject)') ?></p>

        <label style="font-weight: 600; display: block; margin-bottom: 0.5rem;">Message</label>
        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid #e2e8f0; white-space: pre-wrap; line-height: 1.6;">
            <?= nl2br(\App\Core\Security::e($message['message'])) ?>
        </div>
        <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 0.5rem;">Received on: <?= date('Y-m-d H:i', strtotime($message['created_at'])) ?></p>
    </div>

    <div style="display: flex; gap: 1rem; border-top: 1px solid #eee; padding-top: 1.5rem;">
        <form action="/admin/messages/<?= $message['id'] ?>/status" method="POST" style="display: inline;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
            <input type="hidden" name="status" value="<?= $message['status'] === 'archived' ? 'read' : 'archived' ?>">
            <button type="submit" class="btn btn--ghost">
                <?= $message['status'] === 'archived' ? 'Restore to Read' : 'Archive Message' ?>
            </button>
        </form>

        <form action="/admin/messages/<?= $message['id'] ?>/delete" method="POST" style="display: inline;" onsubmit="return confirm('Delete this message permanently?');">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCsrfToken() ?>">
            <button type="submit" class="btn btn-danger" style="background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer;">Delete</button>
        </form>
    </div>
</div>
<?php require __DIR__ . '/../layout_footer.php'; ?>
