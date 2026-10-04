<?php
namespace App\Controllers\Admin;

use App\Models\AuditLog;

class AuditLogController {
    public function index() {
        $title = 'Audit Log';
        $entity = is_string($_GET['entity'] ?? null) ? trim($_GET['entity']) : null;
        if ($entity === '') {
            $entity = null;
        }
        $perPage = 20;
        $total = AuditLog::getTotalCount($entity);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = max(1, min((int)($_GET['page'] ?? 1), $totalPages));
        $logs = AuditLog::getAll($entity, $page, $perPage);
        $entities = AuditLog::getDistinctEntities();
        $start = $total > 0 ? (($page - 1) * $perPage) + 1 : 0;
        $end = min($page * $perPage, $total);

        require APP_PATH . '/Views/admin/audit-log.php';
    }
}