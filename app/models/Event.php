<?php
/**
 * Event Model
 * Handles event data operations
 */
class Event extends Model {
    protected $table = 'events';
    
    /**
     * Get all events ordered by date (newest first)
     */
    public function getAllEvents() {
        return $this->all('event_date DESC, start_time DESC');
    }
    
    /**
     * Get event by ID
     */
    public function getEventById($id) {
        return $this->find($id);
    }
    
    /**
     * Create new event
     */
    public function createEvent($data) {
        // Parse time range (e.g., "08:30 - 12:30")
        $timeRange = explode(' - ', $data['event_time']);
        $startTime = trim($timeRange[0] ?? '00:00');
        $endTime = trim($timeRange[1] ?? '00:00');
        
        $eventData = [
            'event_date' => $data['event_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'name' => $data['event_name'],
            'place' => $data['event_place'],
            'url' => $data['event_url'] ?? '',
            'is_public' => isset($data['is_public']) ? 1 : 0
        ];
        
        return $this->create($eventData);
    }
    
    /**
     * Update event
     */
    public function updateEvent($id, $data) {
        // Parse time range (e.g., "08:30 - 12:30")
        $timeRange = explode(' - ', $data['event_time']);
        $startTime = trim($timeRange[0] ?? '00:00');
        $endTime = trim($timeRange[1] ?? '00:00');
        
        $eventData = [
            'event_date' => $data['event_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'name' => $data['event_name'],
            'place' => $data['event_place'],
            'url' => $data['event_url'] ?? '',
            'is_public' => isset($data['is_public']) ? 1 : 0
        ];
        
        return $this->update($id, $eventData);
    }
    
    /**
     * Delete event
     */
    public function deleteEvent($id) {
        return $this->delete($id);
    }
    
    /**
     * Get upcoming events (from today onwards)
     */
    public function getUpcomingEvents() {
        return $this->where(
            'event_date >= CURDATE()', 
            [], 
            'event_date ASC, start_time ASC'
        );
    }
}
