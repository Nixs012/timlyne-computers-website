<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class AuditLog {
    public static function getAll($entity = null, $page = 1, $perPage = 20) {
        $db = Database::getConnection();
        $entity = is_string($entity) && trim($entity) !== '' ? trim($entity) : null;
        $offset = ((int)$page - 1) * (int)$perPage;
        $sql = "SELECT id, admin_id, action, entity, entity_id, details, ip_address, created_at FROM audit_logs";
        if ($entity !== null) {
            $sql .= " WHERE entity = ?";
        }
        $sql .= " ORDER BY created_at DESC, id DESC LIMIT ? OFFSET ?";
        $stmt = $db->prepare($sql);
        $position = 1;
        if ($entity !== null) {
            $stmt->bindValue($position++, $entity, PDO::PARAM_STR);
        }
        $stmt->bindValue($position++, (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue($position, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTotalCount($entity = null) {
        $db = Database::getConnection();
        $entity = is_string($entity) && trim($entity) !== '' ? trim($entity) : null;
        if ($entity !== null) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM audit_logs WHERE entity = ?");
            $stmt->execute([$entity]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$db->query("SELECT COUNT(*) FROM audit_logs")->fetchColumn();
    }

    public static function getDistinctEntities() {
        $db = Database::getConnection();
        return $db->query("SELECT DISTINCT entity FROM audit_logs ORDER BY entity ASC")->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function log($adminId, $action, $entity, $entityId, $details = null) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO audit_logs (admin_id, action, entity, entity_id, details, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
            $stmt->execute([
                $adminId,
                $action,
                $entity,
                $entityId,
                $details,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (\Throwable $e) {
            error_log('Audit log write failed: ' . $e->getMessage());
        }
    }
}