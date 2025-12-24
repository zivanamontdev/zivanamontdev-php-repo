# CSRF Token Implementation Plan

## 📋 Overview
Dokumentasi ini menganalisis semua endpoint yang memerlukan CSRF token protection dalam format UUID untuk aplikasi native PHP ini.

---

## 🎯 Prinsip Implementasi

### ✅ Endpoint yang PERLU CSRF Token:
- Semua POST/PUT/PATCH/DELETE requests
- Form submissions
- Data modifications (Create, Update, Delete)
- Authentication actions (Login, Logout, Password Reset)

### ❌ Endpoint yang TIDAK Perlu CSRF Token:
- GET requests (read-only)
- Public API endpoints (jika ada)

---

## 📊 Analisis Endpoint

### **Total Endpoint Ditemukan:** 93 endpoints
### **Endpoint yang Perlu CSRF:** 92 endpoints (POST methods)
### **Endpoint yang Tidak Perlu CSRF:** 1 endpoint (GET /admin/logout - redirect)

---

## 📝 Detail Endpoint per Controller

### 1. **AuthController** (4 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/login` | `login()` | 🔴 CRITICAL |
| 2 | POST | `/admin/forget-password` | `forgetPassword()` | 🔴 CRITICAL |
| 3 | POST | `/admin/reset-password` | `resetPassword()` | 🔴 CRITICAL |
| 4 | ANY | `/admin/logout` | `logout()` | 🔴 CRITICAL |

**Notes:**
- Authentication endpoints adalah priority tertinggi
- Logout bisa diubah dari ANY ke POST untuk konsistensi

---

### 2. **HomeController** (1 endpoint)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/registration` | `submitRegistration()` | 🔴 CRITICAL |

**Notes:**
- Public registration form harus dilindungi dari spam/bot
- Sangat rentan terhadap abuse

---

### 3. **RegistrationController** (1 endpoint)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/registrations/{id}/delete` | `delete($id)` | 🟡 HIGH |

---

### 4. **ProgramController** (5 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/programs/store` | `store()` | 🟡 HIGH |
| 2 | POST | `/admin/programs/{id}/update` | `update($id)` | 🟡 HIGH |
| 3 | POST | `/admin/programs/{id}/delete` | `delete($id)` | 🟡 HIGH |
| 4 | POST | `/admin/programs/{id}/upload-image` | `uploadImage($id)` | 🟢 MEDIUM |
| 5 | POST | `/admin/programs/{programId}/delete-image/{imageId}` | `deleteImage($programId, $imageId)` | 🟢 MEDIUM |

---

### 5. **ArticleController** (3 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/articles/store` | `store()` | 🟡 HIGH |
| 2 | POST | `/admin/articles/{id}/update` | `update($id)` | 🟡 HIGH |
| 3 | POST | `/admin/articles/{id}/delete` | `delete($id)` | 🟡 HIGH |

---

### 6. **EmployeeController** (3 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/employees/store` | `store()` | 🟡 HIGH |
| 2 | POST | `/admin/employees/{id}/update` | `update($id)` | 🟡 HIGH |
| 3 | POST | `/admin/employees/{id}/delete` | `delete($id)` | 🟡 HIGH |

---

### 7. **ActivityController** (16 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/activities/classes/store` | `storeClass()` | 🟡 HIGH |
| 2 | POST | `/admin/activities/classes/{id}/update` | `updateClass($id)` | 🟡 HIGH |
| 3 | POST | `/admin/activities/classes/{id}/delete` | `deleteClass($id)` | 🟡 HIGH |
| 4 | POST | `/admin/activities/programs-tahun/store` | `storeProgramTahun()` | 🟡 HIGH |
| 5 | POST | `/admin/activities/programs-tahun/{id}/update` | `updateProgramTahun($id)` | 🟡 HIGH |
| 6 | POST | `/admin/activities/programs-tahun/{id}/delete` | `deleteProgramTahun($id)` | 🟡 HIGH |
| 7 | POST | `/admin/activities/programs-tahun/{programId}/gallery/store` | `storeGalleryImage($programId)` | 🟢 MEDIUM |
| 8 | POST | `/admin/activities/programs-tahun/{programId}/gallery/{galleryId}/update` | `updateGalleryImage($programId, $galleryImageId)` | 🟢 MEDIUM |
| 9 | POST | `/admin/activities/programs-tahun/{programId}/gallery/{galleryId}/delete` | `deleteGalleryImage($programId, $galleryImageId)` | 🟢 MEDIUM |
| 10 | POST | `/admin/activities/programs-tahun/{programId}/update-program-image` | `updateProgramImage($programId)` | 🟢 MEDIUM |
| 11 | POST | `/admin/activities/programs-tahun/{programId}/delete-program-image` | `deleteProgramImage($programId)` | 🟢 MEDIUM |
| 12 | POST | `/admin/activities/programs-harian/{id}/update` | `updateProgramHarian($id)` | 🟡 HIGH |
| 13 | POST | `/admin/activities/programs-harian/{programId}/gallery/store` | `storeGalleryHarianImage($programId)` | 🟢 MEDIUM |
| 14 | POST | `/admin/activities/programs-harian/{programId}/gallery/{galleryId}/update` | `updateGalleryHarianImage($programId, $galleryImageId)` | 🟢 MEDIUM |
| 15 | POST | `/admin/activities/programs-harian/{programId}/gallery/{galleryId}/delete` | `deleteGalleryHarianImage($programId, $galleryImageId)` | 🟢 MEDIUM |
| 16 | POST | `/admin/activities/programs-harian/{programId}/update-program-image` | `updateProgramHarianImage($programId)` | 🟢 MEDIUM |
| 17 | POST | `/admin/activities/programs-harian/{programId}/delete-program-image` | `deleteProgramHarianImage($programId)` | 🟢 MEDIUM |

---

### 8. **SettingsController** (19 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/settings/registration/save` | `saveRegistrationSettings()` | 🟡 HIGH |
| 2 | POST | `/admin/settings/fields/create` | `createField()` | 🟡 HIGH |
| 3 | POST | `/admin/settings/fields/update` | `updateField()` | 🟡 HIGH |
| 4 | POST | `/admin/settings/fields/delete` | `deleteField()` | 🟡 HIGH |
| 5 | POST | `/admin/settings/fields/update-order` | `updateFieldOrder()` | 🟢 MEDIUM |
| 6 | POST | `/admin/settings/events/create` | `createEvent()` | 🟡 HIGH |
| 7 | POST | `/admin/settings/events/{id}/update` | `updateEvent($id)` | 🟡 HIGH |
| 8 | POST | `/admin/settings/events/{id}/delete` | `deleteEvent($id)` | 🟡 HIGH |
| 9 | POST | `/admin/settings/faqs/create` | `createFaq()` | 🟡 HIGH |
| 10 | POST | `/admin/settings/faqs/{id}/update` | `updateFaq($id)` | 🟡 HIGH |
| 11 | POST | `/admin/settings/faqs/{id}/delete` | `deleteFaq($id)` | 🟡 HIGH |
| 12 | POST | `/admin/settings/faqs/update-order` | `updateFaqOrder()` | 🟢 MEDIUM |
| 13 | POST | `/admin/settings/testimonials/create` | `createTestimonial()` | 🟡 HIGH |
| 14 | POST | `/admin/settings/testimonials/{id}/update` | `updateTestimonial($id)` | 🟡 HIGH |
| 15 | POST | `/admin/settings/testimonials/{id}/delete` | `deleteTestimonial($id)` | 🟡 HIGH |
| 16 | POST | `/admin/settings/testimonials/update-order` | `updateTestimonialOrder()` | 🟢 MEDIUM |
| 17 | POST | `/admin/settings/highlight-programs/{id}/replace` | `replaceHighlightProgram($id)` | 🟡 HIGH |
| 18 | POST | `/admin/settings/highlight-programs/{id}/remove` | `removeHighlightProgram($highlightId)` | 🟡 HIGH |
| 19 | POST | `/admin/settings/highlight-programs/update-order` | `updateHighlightProgramOrder()` | 🟢 MEDIUM |
| 20 | POST | `/admin/settings/email/update` | `updateEmailSettings()` | 🔴 CRITICAL |
| 21 | POST | `/admin/settings/email/test` | `testEmail()` | 🟢 MEDIUM |

---

### 9. **ManagementController** (28 endpoints)

| No | Method | Route | Function | Priority |
|----|--------|-------|----------|----------|
| 1 | POST | `/admin/management/prakata/update` | `updatePrakata()` | 🟡 HIGH |
| 2 | POST | `/admin/management/kepala-sekolah/update` | `updateKepalaSekolah()` | 🟡 HIGH |
| 3 | POST | `/admin/management/karyawan/create` | `createKaryawan()` | 🟡 HIGH |
| 4 | POST | `/admin/management/karyawan/{id}/update` | `updateKaryawan($id)` | 🟡 HIGH |
| 5 | POST | `/admin/management/karyawan/{id}/delete` | `deleteKaryawan($id)` | 🟡 HIGH |
| 6 | POST | `/admin/management/karyawan/update-order` | `updateKaryawanOrder()` | 🟢 MEDIUM |
| 7 | POST | `/admin/management/schedules/create` | `createSchedule()` | 🟡 HIGH |
| 8 | POST | `/admin/management/schedules/{id}/update` | `updateSchedule($id)` | 🟡 HIGH |
| 9 | POST | `/admin/management/schedules/{id}/delete` | `deleteSchedule($id)` | 🟡 HIGH |
| 10 | POST | `/admin/management/awards/create` | `createAward()` | 🟡 HIGH |
| 11 | POST | `/admin/management/awards/{id}/update` | `updateAward($id)` | 🟡 HIGH |
| 12 | POST | `/admin/management/awards/{id}/delete` | `deleteAward($id)` | 🟡 HIGH |
| 13 | POST | `/admin/management/social-media/create` | `createSocialMedia()` | 🟡 HIGH |
| 14 | POST | `/admin/management/social-media/{id}/update` | `updateSocialMedia($id)` | 🟡 HIGH |
| 15 | POST | `/admin/management/social-media/{id}/delete` | `deleteSocialMedia($id)` | 🟡 HIGH |
| 16 | POST | `/admin/management/settings/update` | `updateSettings()` | 🟡 HIGH |
| 17 | POST | `/admin/management/fasilitas/create` | `createFasilitas()` | 🟡 HIGH |
| 18 | POST | `/admin/management/fasilitas/{id}/update` | `updateFasilitas($id)` | 🟡 HIGH |
| 19 | POST | `/admin/management/fasilitas/{id}/delete` | `deleteFasilitas($id)` | 🟡 HIGH |
| 20 | POST | `/admin/management/fasilitas/{id}/gallery/store` | `storeGalleryImage($id)` | 🟢 MEDIUM |
| 21 | POST | `/admin/management/fasilitas/{id}/gallery/{imageId}/update` | `updateGalleryImage($fasilitasId, $imageId)` | 🟢 MEDIUM |
| 22 | POST | `/admin/management/fasilitas/{id}/gallery/{imageId}/delete` | `deleteGalleryImage($fasilitasId, $imageId)` | 🟢 MEDIUM |
| 23 | POST | `/admin/management/fasilitas/{id}/gallery/upload` | `uploadGalleryImages($id)` | 🟢 MEDIUM |
| 24 | POST | `/admin/management/fasilitas/{id}/update-fasilitas-image` | `updateFasilitasImage($id)` | 🟢 MEDIUM |
| 25 | POST | `/admin/management/fasilitas/{id}/delete-fasilitas-image` | `deleteFasilitasImage($id)` | 🟢 MEDIUM |

---

## 📑 Summary by Priority

### 🔴 CRITICAL Priority (6 endpoints)
**Must implement FIRST** - Authentication & Security sensitive
1. `/admin/login` - Login form
2. `/admin/forget-password` - Password reset request
3. `/admin/reset-password` - Password reset confirmation
4. `/admin/logout` - Logout action
5. `/registration` - Public registration form
6. `/admin/settings/email/update` - Email settings (can be exploited)

### 🟡 HIGH Priority (60 endpoints)
**Core CRUD operations** - Data modification endpoints
- All store/create operations
- All update operations
- All delete operations
- Settings modifications

### 🟢 MEDIUM Priority (26 endpoints)
**Supporting operations** - Image uploads, ordering, testing
- Image upload/update/delete operations
- Order update operations
- Test email functionality

---

## 🛠️ Implementation Strategy

### Phase 1: Core Infrastructure (Week 1)
1. Create `app/helpers/Security.php` with UUID-based CSRF functions:
   - `generateCsrfToken()` - Generate UUID format token
   - `validateCsrfToken($token)` - Validate token
   - `getCsrfToken()` - Get current token
   - `regenerateCsrfToken()` - Regenerate after use

2. Create CSRF Middleware: `app/middleware/CsrfMiddleware.php`
   - Auto-validate on POST/PUT/PATCH/DELETE
   - Return 403 on invalid token
   - Auto-regenerate after validation

3. Create CSRF helper component: `app/views/components/csrf_field.php`
   ```php
   <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
   ```

### Phase 2: Critical Endpoints (Week 1-2)
Implement CSRF on 🔴 CRITICAL priority endpoints:
- Authentication forms (login, password reset)
- Public registration form
- Email settings

### Phase 3: High Priority Endpoints (Week 2-3)
Implement CSRF on 🟡 HIGH priority endpoints:
- All CRUD operations across controllers
- Settings modifications

### Phase 4: Medium Priority Endpoints (Week 3-4)
Implement CSRF on 🟢 MEDIUM priority endpoints:
- Image operations
- Ordering operations
- Testing endpoints

### Phase 5: Testing & Validation (Week 4)
- Test all forms with valid tokens
- Test all forms with invalid tokens
- Test token expiration (1 hour)
- Test token regeneration
- Security audit

---

## 📋 Files to Modify

### New Files to Create:
1. `app/helpers/Security.php` - CSRF token functions
2. `app/middleware/CsrfMiddleware.php` - CSRF validation middleware
3. `app/views/components/csrf_field.php` - CSRF hidden input component

### Existing Files to Modify:
1. `app/core/Router.php` - Add CSRF middleware to POST routes
2. All admin form views (~50+ files) - Add `<?php component('csrf_field'); ?>`
3. All admin controllers (~10 files) - Add token validation
4. Public registration form - Add CSRF protection

### Estimated Files Impact:
- **New files:** 3
- **Modified files:** ~65 files (10 controllers + 50+ views + 5 core files)

---

## 🔒 Security Benefits

### What CSRF Protection Prevents:
1. ✅ Cross-site request forgery attacks
2. ✅ Unauthorized data modifications
3. ✅ Spam bot submissions (public forms)
4. ✅ Session hijacking exploits
5. ✅ CSRF in admin panel

### Additional Security Recommendations:
1. Combine with rate limiting for login/registration
2. Add captcha for public registration form
3. Log failed CSRF validations for monitoring
4. Implement IP-based restrictions for admin panel
5. Use HTTPS in production (required for secure tokens)

---

## 📊 Estimated Effort

| Phase | Effort | Duration |
|-------|--------|----------|
| Phase 1: Infrastructure | 4-6 hours | 1 day |
| Phase 2: Critical (6 endpoints) | 3-4 hours | 1 day |
| Phase 3: High Priority (60 endpoints) | 12-15 hours | 3-4 days |
| Phase 4: Medium Priority (26 endpoints) | 5-6 hours | 1-2 days |
| Phase 5: Testing | 4-6 hours | 1 day |
| **TOTAL** | **28-37 hours** | **7-9 days** |

---

## ✅ Next Steps

1. **Review this document** - Confirm endpoints and priority
2. **Get approval** - Proceed with implementation?
3. **Start Phase 1** - Build core CSRF infrastructure
4. **Incremental deployment** - Test each phase before moving to next
5. **Documentation** - Update README with CSRF implementation details

---

## 📝 Notes

- Token format: UUID v4 (`550e8400-e29b-41d4-a716-446655440000`)
- Token storage: PHP `$_SESSION`
- Token lifetime: 1 hour (configurable)
- Token regeneration: After each validation (single-use)
- Failed validation response: HTTP 403 Forbidden with JSON error

---

**Document Version:** 1.1  
**Last Updated:** December 23, 2025  
**Status:** 🚀 Phase 1 & 2 Complete - AuthController Protected

---

## 📋 Implementation Progress

### ✅ Phase 1: Core Infrastructure - COMPLETED
- ✅ Created `app/helpers/Security.php` with UUID-based CSRF functions
- ✅ Created `app/views/components/csrf_field.php` component  
- ✅ Updated legacy CSRF functions in `app/helpers/functions.php`
- ✅ All automated tests passed successfully

### ✅ Phase 2: Critical Endpoints - COMPLETED (AuthController)
**All 4 AuthController endpoints now protected:**
- ✅ POST `/admin/login` - Login form with CSRF token
- ✅ POST `/admin/forget-password` - Forget password form with CSRF token
- ✅ POST `/admin/reset-password` - Reset password form with CSRF token
- ✅ ANY `/admin/logout` - Logout action validated

**Test Results:**
```
✅ UUID v4 token generation: PASSED
✅ Token validation: PASSED
✅ Token expiration (1 hour): PASSED
✅ Single-use pattern (regeneration): PASSED
✅ csrf_field() component: PASSED
✅ Legacy csrf_verify() compatibility: PASSED
```

### 🔄 Next Phase: Remaining CRITICAL Endpoints
**Remaining endpoints to protect:**
1. ⏳ POST `/registration` - Public registration form (HomeController)
2. ⏳ POST `/admin/settings/email/update` - Email settings (SettingsController)

