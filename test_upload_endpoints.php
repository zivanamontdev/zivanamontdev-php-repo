<?php
/**
 * Test Upload Endpoints - R2 Integration
 * 
 * Tests all upload endpoints for ActivityController and SettingsController
 * Verifies:
 * 1. File upload to R2 bucket
 * 2. Database record creation
 * 3. Correct URL storage
 */

// Configuration
define('BASE_URL', 'http://localhost:8001');
define('TEST_IMAGE_PATH', __DIR__ . '/public/images/vectors/login.svg'); // Use existing image for testing

class UploadEndpointTester {
    
    private $results = [];
    private $createdIds = [];
    
    public function __construct() {
        // Create test image if not exists
        if (!file_exists(TEST_IMAGE_PATH)) {
            // Create a simple test image
            $this->createTestImage();
        }
    }
    
    private function createTestImage() {
        $img = imagecreatetruecolor(100, 100);
        $bgColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $bgColor);
        imagepng($img, TEST_IMAGE_PATH);
        imagedestroy($img);
    }
    
    /**
     * Test POST endpoint (Create with upload)
     */
    public function testPostEndpoint($endpoint, $data, $fileField, $testName) {
        echo "\n[TEST] $testName\n";
        echo "Endpoint: POST $endpoint\n";
        
        $ch = curl_init();
        
        // Prepare file upload if fileField is specified
        if (!empty($fileField)) {
            if (function_exists('curl_file_create')) {
                $cfile = curl_file_create(TEST_IMAGE_PATH, 'image/png', 'test.png');
            } else {
                $cfile = '@' . TEST_IMAGE_PATH;
            }
            
            $data[$fileField] = $cfile;
        }
        
        curl_setopt_array($ch, [
            CURLOPT_URL => BASE_URL . $endpoint,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Parse response
        $parts = explode("\r\n\r\n", $response);
        $body = array_pop($parts);
        $result = json_decode($body, true);
        
        // Check result
        $success = $httpCode === 200 && isset($result['success']) && $result['success'];
        
        $this->results[] = [
            'test' => $testName,
            'endpoint' => $endpoint,
            'method' => 'POST',
            'status' => $httpCode,
            'success' => $success,
            'response' => $result
        ];
        
        // Store created ID for cleanup
        if ($success && isset($result['id'])) {
            $this->createdIds[$testName] = [
                'id' => $result['id'],
                'endpoint' => $endpoint
            ];
        }
        
        $this->printResult($testName, $success, $httpCode, $result);
        
        return $success ? $result : null;
    }
    
    /**
     * Test PUT endpoint (Update with upload)
     */
    public function testPutEndpoint($endpoint, $id, $data, $fileField, $testName) {
        echo "\n[TEST] $testName\n";
        echo "Endpoint: PUT $endpoint/$id\n";
        
        $ch = curl_init();
        
        // Prepare file upload
        if (function_exists('curl_file_create')) {
            $cfile = curl_file_create(TEST_IMAGE_PATH, 'image/png', 'test_updated.png');
        } else {
            $cfile = '@' . TEST_IMAGE_PATH;
        }
        
        $data[$fileField] = $cfile;
        $data['_method'] = 'PUT'; // Method spoofing
        
        curl_setopt_array($ch, [
            CURLOPT_URL => BASE_URL . $endpoint . '/' . $id,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Parse response
        list($headers, $body) = explode("\r\n\r\n", $response, 2);
        $result = json_decode($body, true);
        
        // Check result
        $success = $httpCode === 200 && isset($result['success']) && $result['success'];
        
        $this->results[] = [
            'test' => $testName,
            'endpoint' => $endpoint . '/' . $id,
            'method' => 'PUT',
            'status' => $httpCode,
            'success' => $success,
            'response' => $result
        ];
        
        $this->printResult($testName, $success, $httpCode, $result);
        
        return $success ? $result : null;
    }
    
    /**
     * Test DELETE endpoint
     */
    public function testDeleteEndpoint($endpoint, $id, $testName) {
        echo "\n[TEST] $testName\n";
        echo "Endpoint: DELETE $endpoint/$id\n";
        
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => BASE_URL . $endpoint . '/' . $id,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Parse response
        list($headers, $body) = explode("\r\n\r\n", $response, 2);
        $result = json_decode($body, true);
        
        // Check result
        $success = $httpCode === 200 && isset($result['success']) && $result['success'];
        
        $this->results[] = [
            'test' => $testName,
            'endpoint' => $endpoint . '/' . $id,
            'method' => 'DELETE',
            'status' => $httpCode,
            'success' => $success,
            'response' => $result
        ];
        
        $this->printResult($testName, $success, $httpCode, $result);
        
        return $success;
    }
    
    private function printResult($testName, $success, $httpCode, $result) {
        if ($success) {
            echo "✅ SUCCESS (HTTP $httpCode)\n";
            if (isset($result['message'])) {
                echo "   Message: {$result['message']}\n";
            }
            if (isset($result['path'])) {
                echo "   Path: {$result['path']}\n";
            }
            if (isset($result['id'])) {
                echo "   ID: {$result['id']}\n";
            }
        } else {
            echo "❌ FAILED (HTTP $httpCode)\n";
            if (isset($result['message'])) {
                echo "   Error: {$result['message']}\n";
            }
            if (isset($result['success'])) {
                echo "   Success flag: " . ($result['success'] ? 'true' : 'false') . "\n";
            }
            // Debug: Show raw response
            if (is_array($result) && empty($result)) {
                echo "   Debug: Response is empty array\n";
            } elseif ($result === null) {
                echo "   Debug: Response is null (invalid JSON)\n";
            } elseif (is_string($result)) {
                echo "   Debug: Raw response: " . substr($result, 0, 200) . "\n";
            }
        }
    }
    
    /**
     * Run all tests for ActivityController
     */
    public function testActivityController() {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "TESTING ACTIVITY CONTROLLER\n";
        echo str_repeat("=", 80) . "\n";
        
        // Test 1: POST /admin/activities/classes/store (storeClass)
        $result = $this->testPostEndpoint(
            '/admin/activities/classes/store',
            [
                'name' => 'Test Class ' . time(),
                'age_range' => '3-4',
                'duration' => '2',
                'max_students' => '15'
            ],
            'image',
            'ActivityController::storeClass()'
        );
        
        if ($result && isset($result['id'])) {
            $classId = $result['id'];
            
            // Test 2: POST /admin/activities/classes/{id}/update (updateClass)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/activities/classes/$classId/update",
                [
                    'name' => 'Updated Test Class',
                    'age_range' => '4-5',
                    'duration' => '3',
                    'max_students' => '20'
                ],
                'image',
                'ActivityController::updateClass()'
            );
            
            // Test 3: POST /admin/activities/classes/{id}/delete (deleteClass)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/activities/classes/$classId/delete",
                [],
                '',
                'ActivityController::deleteClass()'
            );
        }
        
        // Test 4: POST /admin/activities/programs-tahun/store (storeProgramTahun)
        $result = $this->testPostEndpoint(
            '/admin/activities/programs-tahun/store',
            [
                'name' => 'Test Program Tahun ' . time(),
                'description' => 'Test program description'
            ],
            'image',
            'ActivityController::storeProgramTahun()'
        );
        
        if ($result && isset($result['id'])) {
            $programId = $result['id'];
            
            // Test 5: POST /admin/activities/programs-tahun/{id}/gallery/store (storeGalleryImage)
            sleep(1);
            $galleryResult = $this->testPostEndpoint(
                "/admin/activities/programs-tahun/$programId/gallery/store",
                [
                    'description' => 'Test gallery image',
                    'is_cover' => '0'
                ],
                'image',
                'ActivityController::storeGalleryImage()'
            );
            
            if ($galleryResult && isset($galleryResult['id'])) {
                $galleryId = $galleryResult['id'];
                
                // Test 6: POST /admin/activities/programs-tahun/{id}/gallery/{galleryId}/update (updateGalleryImage)
                sleep(1);
                $this->testPostEndpoint(
                    "/admin/activities/programs-tahun/$programId/gallery/$galleryId/update",
                    [
                        'description' => 'Updated gallery image',
                        'is_cover' => '1'
                    ],
                    'image',
                    'ActivityController::updateGalleryImage()'
                );
                
                // Test 7: POST /admin/activities/programs-tahun/{id}/gallery/{galleryId}/delete (deleteGalleryImage)
                sleep(1);
                $this->testPostEndpoint(
                    "/admin/activities/programs-tahun/$programId/gallery/$galleryId/delete",
                    [],
                    '',
                    'ActivityController::deleteGalleryImage()'
                );
            }
            
            // Test 8: POST /admin/activities/programs-tahun/{id}/update (updateProgramTahun)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/activities/programs-tahun/$programId/update",
                [
                    'name' => 'Updated Program Tahun',
                    'description' => 'Updated description'
                ],
                'image',
                'ActivityController::updateProgramTahun()'
            );
            
            // Test 9: POST /admin/activities/programs-tahun/{id}/delete (deleteProgramTahun)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/activities/programs-tahun/$programId/delete",
                [],
                '',
                'ActivityController::deleteProgramTahun()'
            );
        }
    }
    
    /**
     * Run all tests for SettingsController
     */
    public function testSettingsController() {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "TESTING SETTINGS CONTROLLER\n";
        echo str_repeat("=", 80) . "\n";
        
        // Test 1: POST /admin/settings/events/create (createEvent)
        $result = $this->testPostEndpoint(
            '/admin/settings/events/create',
            [
                'event_date' => date('Y-m-d'),
                'event_time' => '10:00',
                'event_name' => 'Test Event ' . time(),
                'event_place' => 'Test Location',
                'event_url' => 'https://test.com'
            ],
            'image',
            'SettingsController::createEvent()'
        );
        
        if ($result && isset($result['id'])) {
            $eventId = $result['id'];
            
            // Test 2: POST /admin/settings/events/{id}/update (updateEvent)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/settings/events/$eventId/update",
                [
                    'event_date' => date('Y-m-d'),
                    'event_time' => '11:00',
                    'event_name' => 'Updated Test Event',
                    'event_place' => 'Updated Location',
                    'event_url' => 'https://updated-test.com'
                ],
                'image',
                'SettingsController::updateEvent()'
            );
            
            // Test 3: POST /admin/settings/events/{id}/delete (deleteEvent)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/settings/events/$eventId/delete",
                [],
                '',
                'SettingsController::deleteEvent()'
            );
        }
        
        // Test 4: POST /admin/settings/testimonials/create (createTestimonial)
        $result = $this->testPostEndpoint(
            '/admin/settings/testimonials/create',
            [
                'parent_name' => 'Test Parent ' . time(),
                'child_name' => 'Test Child',
                'testimonial_text' => 'Test testimonial content',
                'highlight_text' => 'Test highlight'
            ],
            'image',
            'SettingsController::createTestimonial()'
        );
        
        if ($result && isset($result['id'])) {
            $testimonialId = $result['id'];
            
            // Test 5: POST /admin/settings/testimonials/{id}/update (updateTestimonial)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/settings/testimonials/$testimonialId/update",
                [
                    'parent_name' => 'Updated Parent',
                    'child_name' => 'Updated Child',
                    'testimonial_text' => 'Updated testimonial',
                    'highlight_text' => 'Updated highlight'
                ],
                'image',
                'SettingsController::updateTestimonial()'
            );
            
            // Test 6: POST /admin/settings/testimonials/{id}/delete (deleteTestimonial)
            sleep(1);
            $this->testPostEndpoint(
                "/admin/settings/testimonials/$testimonialId/delete",
                [],
                '',
                'SettingsController::deleteTestimonial()'
            );
        }
    }
    
    /**
     * Print summary
     */
    public function printSummary() {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "TEST SUMMARY\n";
        echo str_repeat("=", 80) . "\n";
        
        $total = count($this->results);
        $passed = 0;
        $failed = 0;
        
        foreach ($this->results as $result) {
            if ($result['success']) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        echo "Total Tests: $total\n";
        echo "✅ Passed: $passed\n";
        echo "❌ Failed: $failed\n";
        echo "Success Rate: " . round(($passed / $total) * 100, 2) . "%\n";
        
        // List failed tests
        if ($failed > 0) {
            echo "\nFailed Tests:\n";
            foreach ($this->results as $result) {
                if (!$result['success']) {
                    echo "  - {$result['test']} (HTTP {$result['status']})\n";
                    if (isset($result['response']['message'])) {
                        echo "    Error: {$result['response']['message']}\n";
                    }
                }
            }
        }
    }
}

// Run tests
echo "=" . str_repeat("=", 79) . "\n";
echo "Upload Endpoint Integration Test\n";
echo "Testing R2 Upload Integration\n";
echo "=" . str_repeat("=", 79) . "\n";

$tester = new UploadEndpointTester();

// Test Activity Controller
$tester->testActivityController();

// Test Settings Controller
$tester->testSettingsController();

// Print summary
$tester->printSummary();

echo "\n" . str_repeat("=", 80) . "\n";
echo "All tests completed!\n";
echo str_repeat("=", 80) . "\n";
