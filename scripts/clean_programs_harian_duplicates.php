<?php
/**
 * Script to clean duplicate programs_harian entries
 * Keeps only one entry per day_name (Senin - Jumat)
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/app/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "Starting cleanup of duplicate programs_harian entries...\n\n";
    
    // Get all entries grouped by day_name
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    
    foreach ($days as $day) {
        echo "Processing $day...\n";
        
        // Get all entries for this day, ordered by id ASC (keep the first one)
        $entries = $db->query(
            "SELECT id FROM programs_harian WHERE day_name = ? ORDER BY id ASC",
            [$day]
        )->fetchAll();
        
        if (count($entries) > 1) {
            // Keep the first entry, delete the rest
            $keepId = $entries[0]['id'];
            $deleteIds = array_column(array_slice($entries, 1), 'id');
            
            echo "  Found " . count($entries) . " entries for $day\n";
            echo "  Keeping ID: $keepId\n";
            echo "  Deleting IDs: " . implode(', ', $deleteIds) . "\n";
            
            // Delete duplicates
            $placeholders = implode(',', array_fill(0, count($deleteIds), '?'));
            $db->query(
                "DELETE FROM programs_harian WHERE id IN ($placeholders)",
                $deleteIds
            );
            
            echo "  ✓ Deleted " . count($deleteIds) . " duplicate entries\n\n";
        } else if (count($entries) == 1) {
            echo "  ✓ Only 1 entry found, no duplicates\n\n";
        } else {
            echo "  ⚠ No entries found for $day\n\n";
        }
    }
    
    // Show final count
    $total = $db->query("SELECT COUNT(*) as total FROM programs_harian")->fetch();
    echo "Cleanup complete!\n";
    echo "Total programs_harian entries remaining: " . $total['total'] . "\n\n";
    
    // Show remaining entries
    echo "Remaining entries:\n";
    $remaining = $db->query("SELECT id, day_name, program_name FROM programs_harian ORDER BY display_order ASC, id ASC")->fetchAll();
    foreach ($remaining as $entry) {
        echo "  ID {$entry['id']}: {$entry['day_name']} - {$entry['program_name']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
