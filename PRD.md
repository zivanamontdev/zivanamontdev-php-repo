# **Zivana Montessori School Website - Product Requirements Document**

## **1. Product Overview**

The Zivana Montessori School Website is a professional company profile website designed to introduce the school and simplify the enrollment process for parents looking to register their children.

### **Technology Stack**

- **Backend:** PHP (Laravel Framework or Vanilla PHP with MVC pattern)
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Styling:** Tailwind CSS
- **UI Components:** Custom components inspired by shadcn/ui design patterns
- **Database:** MySQL
- **Hosting:** Shared hosting compatible (Hostinger)
- **Version Control:** Git

### **Architecture Approach**

The application will be built using PHP with full CRUD functionality connected to MySQL database. The system is designed to run efficiently on shared hosting environments without requiring VPS or Node.js runtime.

**Two Development Options:**
1. **Laravel Framework** - If shared hosting supports Composer and Laravel deployment
2. **Custom PHP MVC** - Lightweight custom framework for guaranteed shared hosting compatibility

Both approaches will deliver:
- Dynamic server-side rendering
- RESTful API endpoints for CRUD operations
- Session-based authentication for admin panel
- Responsive design with Tailwind CSS
- Interactive UI components with vanilla JavaScript

---

## **2. Product Goals**

### **Primary Objectives**

- Increase awareness about Zivana Montessori School
- Simplify the enrollment process for prospective parents
- Showcase school activities and development to the public
- Boost school enrollment and attract more registrations

### **Design Principles**

- **Simple and Lightweight:** Interface designed to ensure users don't experience slow content loading, maximizing the school introduction process
- **Fun, Friendly, yet Professional:** While designed for kindergarten, professional values remain top priority, especially in convincing parents to trust enrolling their children at Zivana Montessori
- **Informatively Parent-Centered Content:** Informative especially for parents, as they are the target audience - people with children who need kindergarten enrollment
- **Simple Navigation & Fast Orientation:** Website needs to be scannable within the typical above-the-fold size, approximately 0.05 seconds, with final decision optimized within the first 10 seconds to CTA as a standard KPI for click-through rate
- **Visual Storytelling:** Website doesn't just run statically but also carries deep story value to make prospective enrollees more interested
- **Mobile First Design (Responsive):** All design principles above need to be more compactly designed for mobile responsiveness to achieve balanced accessibility

---

## **3. User Flows**

### **3.1 Prospective Enrollee (Parents)**

```
1. User accesses website landing page from Google/Social media
2. User views website content
3. User clicks CTA button
4. User fills out brief form
5. User clicks Submit button
6. User redirects to WhatsApp to communicate with admin
```

### **3.2 Admin (Back-office)**

```
1. User logs into back-office
2. User can edit website content (documentation, school programs, articles, employees)
3. User can check website access trends
```

---

## **4. Page Structure & Features**

### **4.1 Homepage (Landing Page)**

**Hero Section (Above The Fold)**

- Above the fold contains school advantages compared to other schools

**Content:**

- Full-width hero image
- Title & Sub-copy
- Primary CTA Button (Sub-focus)
- School advantages compared to others (Main Focus)

**Additional Sections:**

- Featured programs carousel
- Quick stats (years of operation, number of students, awards)
- Testimonials from parents
- Call-to-action for enrollment
- School social media links

### **4.2 School Activities Page**

**Curriculum (Sub-focus)**

- Comprehensive information about the curriculum implemented at the school
- Montessori method explanation
- Learning approaches and methodologies

**Learning Programs (Main Focus)**

- Several flagship programs conducted monthly by the school
- Documentation gallery per Learning Program that users can view
- Program descriptions with images and details

**School Schedule**

- School schedule rundown from arrival to dismissal
- Minimum information: school start and end times according to class divisions
- Daily activity timeline

### **4.3 School Profile Page**

**Brief School History**

- Explanation of when and why the school was founded
- School milestones and achievements
- School philosophy and educational approach

**School Vision & Mission**

- Vision statement
- Mission statements
- Core values

**Staff Members**

- List of staff members from principal, administrative staff, teachers, and others
- Photos and brief bios
- Positions and qualifications

**School Awards**

- Several awards achieved by the school and its teachers
- Award images displayed in carousel
- Award descriptions and years received

### **4.4 Articles/News Page**

**Article Listing:**

- Newsletter-style layout
- Article thumbnail images
- Article titles and excerpts
- Author names
- Publication dates
- Category/tags
- Pagination or load more functionality

**Article Detail:**

- Full article content
- Featured image
- Author information
- Publication date
- Share buttons
- Related articles section

### **4.5 Registration Page**

**Components:**

- Registration form with configurable fields
- Form validation (client-side and server-side)
- Submit button with WhatsApp integration
- Registration instructions banner
- Required documents information
- Frequently asked questions

**Form Fields:**

- Child's name
- Parent's name
- Email address
- Phone number
- Address (optional)
- Message/notes (optional)

### **4.6 Dashboard (Back-office)**

**Analytics Overview:**

- Access trend graph (daily/weekly/monthly)
- Top access by device table (Desktop, Mobile, Tablet)
- Top access by location table (Optional)
- Total visitors count
- Registration submissions count
- Quick action buttons

### **4.7 School Activities Management (Back-office)**

**Program Management:**

- CRUD School Programs containing Program Name and Program Description
- Program ordering/sorting functionality
- Program status (active/inactive)

**Image Management:**

- CRUD Images with Captions divided per Program
- Multiple image upload functionality
- Image preview and editing
- Image ordering within programs

### **4.8 Articles/News Management (Back-office)**

**Article Management:**

- CRUD Articles containing Image, Article Content, Author Name
- Rich text editor for content formatting
- Article status (draft/published)
- Publication date scheduling
- Featured article toggle
- Article preview functionality

### **4.9 Management Page (Back-office)**

**Employee Management:**

- CRUD Employees with 4 employee levels:
  - School Leadership (Principal, Vice Principal)
  - Educational Support Staff (Admin, Counselor)
  - Teaching Staff (Teachers, Assistant Teachers)
  - Operational Staff (Maintenance, Security)
- Employee photo upload
- Position and bio management
- Employee ordering/sorting

**Schedule Management:**

- CRUD School Schedule containing Time and Activity Name
- Schedule ordering/sorting
- Time range formatting

**Awards Management:**

- CRUD School Awards containing Images for display on Profile Page carousel
- Award title and description
- Year received
- Award ordering/sorting

**Social Media Management:**

- CRUD Social Media accounts containing Platform Type, Account Name, and URL
- Supported platforms (Facebook, Instagram, YouTube, Twitter, TikTok)
- Display order management

**Settings Management:**

- General site settings (school name, address, contact info)
- SEO settings (meta title, meta description)
- Registration form field configuration
- Email notification settings
- System configuration options

---

## **5. Database Schema**

### **Tables Required:**

1. **programs** - School programs/activities
2. **images** - Image gallery linked to programs
3. **articles** - Articles/news posts
4. **employees** - Staff member information
5. **schedules** - Daily school schedule
6. **awards** - School awards and achievements
7. **social_media** - Social media links
8. **registrations** - Enrollment form submissions
9. **analytics** - Website visit tracking
10. **settings** - Site configuration
11. **form_fields** - Dynamic form field configuration
12. **users** - Admin authentication (admin login)

---

## **6. Technical Requirements**

### **Frontend Requirements:**

- Responsive design (mobile-first approach)
- Cross-browser compatibility (Chrome, Firefox, Safari, Edge)
- Page load time < 3 seconds
- Accessibility standards (WCAG 2.1 Level AA)
- SEO optimization
- Progressive enhancement approach

### **Backend Requirements:**

- PHP 7.4+ or 8.0+ compatibility
- MySQL 5.7+ or MariaDB 10.3+
- Secure file upload handling (images)
- Input validation and sanitization
- CSRF protection
- SQL injection prevention (prepared statements)
- Session-based authentication
- Password hashing (bcrypt/Argon2)

### **Hosting Requirements:**

- Shared hosting compatible (no VPS required)
- PHP support
- MySQL database access
- File manager or FTP access
- Git deployment support (optional)
- SSL certificate support
- Sufficient storage for images (minimum 5GB recommended)

---

## **7. Security Considerations**

- Admin login with secure authentication
- Password strength requirements
- Session management and timeout
- File upload validation (type, size, malicious content)
- XSS prevention
- CSRF token protection
- SQL injection prevention
- Secure database credentials storage (.env file)
- HTTPS enforcement
- Regular backup procedures

---

## **8. Performance Optimization**

- Image optimization and lazy loading
- CSS/JS minification and concatenation
- Browser caching headers
- Database query optimization
- Pagination for large datasets
- CDN for static assets (optional)
- Gzip compression

---

## **9. Development Phases**

### **Phase 1: Foundation (Week 1-2)**
- Project setup and structure
- Database schema creation
- Authentication system
- Basic admin panel layout

### **Phase 2: Public Pages (Week 2-3)**
- Homepage development
- School activities page
- Profile page
- Articles page
- Registration page

### **Phase 3: Admin CRUD (Week 3-4)**
- Programs management
- Articles management
- Employee management
- Schedule/Awards/Social media management

### **Phase 4: Polish & Testing (Week 4-5)**
- UI/UX refinements
- Cross-browser testing
- Mobile responsiveness testing
- Performance optimization
- Security audit

### **Phase 5: Deployment (Week 5)**
- Hostinger setup
- Database migration
- Git deployment configuration
- SSL setup
- Final testing on production

---

## **10. Future Enhancements (Post-Launch)**

- Newsletter subscription system
- Photo gallery with lightbox
- Event calendar integration
- Online payment gateway for registration fees
- Parent portal for student information
- Multi-language support (Bahasa Indonesia + English)
- Advanced analytics dashboard
- Push notifications for new articles
- Integration with Google Analytics
- Social media feed integration