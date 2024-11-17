# **Uber - Data Management and Application Design**

Welcome to the **Uber** project repository. This project involves the complete design and development of a data management system for an Uber-like application. It covers all aspects of database design, modeling, query creation, and security, from conceptualization to final deployment.

## **Project Overview**
The goal of this project is to build a data management system that supports the functionality of an Uber-like application. This includes the ability to manage users (passengers and drivers), trips, payments, reservations, and vehicle types, as well as the implementation of security features such as data encryption and privacy measures. The project also includes detailed design and specification documentation, as well as interactive reports for business intelligence analysis.

### **Key Features**
- **User Management:** Registration and management of both passengers and drivers, including personal details, vehicle information, and driving history.
- **Trip Management:** Tracks trips from start to finish, including trip duration, distance, and pricing.
- **Payment Management:** Handles payments, including transaction history and commission management for drivers.
- **Ride Reservations:** Allows passengers to book rides in advance.
- **Ratings and Feedback:** System to rate both passengers and drivers based on trip quality and experience.
- **Geographical Data Analysis:** Analyzes trips by geographical locations to gain insights into ride distribution.
- **Security & Data Protection:** Implements necessary privacy and security measures, including encryption, cookie management, and GDPR compliance.

---

## **Deliverables**

### **Design Documentation**
1. **Collaboration Diagrams:**
   - Process for requesting VTC status.
   - Account creation for Uber drivers.
   - Ride management and handling.
   
2. **Use Case Diagram:**
   - Describes the various actions users (drivers and passengers) can perform and the system's responses.  
   - Includes textual descriptions and sequence diagrams where necessary.

3. **Class Diagrams:**
   - A business class diagram representing the main entities of the application (e.g., Driver, Passenger, Trip, Payment).
   - Transition state diagrams for specific parts of the system, such as user status or trip status.

4. **MVC Class Diagrams:**
   - Detailed design of classes for ride search, visualization, and booking processes following the MVC architecture.

---

### **Database Design**
1. **MCD (Conceptual Data Model), MLD (Logical Data Model):**
   - Created with PowerAMC and based on the relational model for PostgreSQL.  
   - Includes tables for Users, Trips, Payments, Ratings, Reservations, and others.

2. **Data Population:**
   - 80 restaurant listings and 30 drivers with detailed profiles.
   - 50 users distributed across France, including 100 reservations and payment details.
   - For some customers, stored credit card references for easier payments.
   - Additional custom data to simulate real-world use cases.

3. **SQL Scripts:**
   - Scripts for database creation, table setup, and data population.  

---

### **Database Setup and Usage**
1. **Clone the GitHub Repository:**
   ```bash
   git clone https://github.com/melih0132/UBER.git
   ```

2. **Setup PostgreSQL Database:**
   - Install PostgreSQL and pgAdmin.
   - Create a new database and run the provided SQL scripts to create tables and populate them with sample data.
   - Test queries.

---

### **Development**
1. **Functional Application Deployment:**
   - Fully deployed application simulating Uber functionalities, with web API and database integration.
   
2. **Source Code:**
   - All source code is available in the repository, including backend API and database handling.

3. **Architecture Summary:**
   - The application utilizes **ORM (Object-Relational Mapping)** for database management, PostgreSQL for storage, and a simple API for managing requests between the frontend and the database.

---

### **Security & Optimization**
1. **Security Tests:**
   - Reports on basic security tests such as XSS, SQL injections, and sensitive URL checks.

2. **Performance Testing:**
   - Load testing to evaluate the website’s performance under high traffic conditions.

3. **Encryption:**
   - A report explaining the chosen encryption method for sensitive data (e.g., card details), including technical details and justifications.

4. **Cookie and Privacy Management:**
   - Implementation of a cookie consent banner and detailed cookie settings to comply with GDPR regulations.
   - Creation of a “Privacy Policy” page detailing how user data is processed.

---

### **Communication & UI/UX**
1. **User Guide:**
   - A detailed user guide explaining how to navigate the website, book rides, manage accounts, and make payments.

2. **Website Content:**
   - Implementation of this content within the site’s structure, ensuring an easy-to-use interface for both customers and drivers.

---

### **Project Management and Reporting**
1. **Tracking:**
   - Use of **Azure DevOps** for task tracking, sprint planning, and documentation management.
   - Regular sprint reviews and progress updates.

2. **Final Sprint Deliverables:**
   - Demonstration of functional application and analysis features.
   - Detailed reports on the tools used, architecture decisions, and system performance.

---

### **Business Intelligence Reports (Power BI)**
1. **Revenue Analysis:**
   - A Power BI report showcasing revenue by vehicle type over time, using the data stored in the database.

2. **Geographic Analysis:**
   - Analyzing trips by geographical area to identify trends and hotspots.

---

### **Installation and Deployment Instructions**
1. **Environment Setup:**
   - Instructions on setting up the development environment, including installation of **Visual Studio**, **PostgreSQL**, and **API services**.

2. **Chatbot Integration:**
   - Integration of a chatbot for customer support using Natural Language Processing (NLP) or keyword analysis.

3. **API Documentation:**
   - Details of the API used for handling ride requests, payments, and user management.

---

## **Technologies Used**
- **Database:** PostgreSQL, pgAdmin4
- **Backend:** Node.js, Express
- **Frontend:** React.js
- **Data Visualization:** Power BI
- **API Integration:** RESTful API
- **Security:** Data Encryption, XSS Prevention, Cookie Management
- **Project Management:** Azure DevOps, GitHub

---

## **Conclusion**
This project represents a full-stack solution for an Uber-like service, with emphasis on proper database design, data security, and system optimization. By combining SQL databases, modern web technologies, and performance testing, the system is ready for real-world deployment and business intelligence analysis.
