<p align="center">
<h1 align="center">💼 JobApp: Modern Career & Recruitment Platform</h1>
</p>

<p align="center">
<img src="https://img.shields.io/badge/Node.js-20.x-43853D?style=for-the-badge&logo=node.js&logoColor=white" alt="Node.js">
<img src="https://img.shields.io/badge/Express.js-Enabled-000000?style=for-the-badge&logo=express&logoColor=white" alt="Express">
<img src="https://img.shields.io/badge/MongoDB-Cloud-47A248?style=for-the-badge&logo=mongodb&logoColor=white" alt="MongoDB">
<img src="https://img.shields.io/badge/React-UI-61DAFB?style=for-the-badge&logo=react&logoColor=white" alt="React">
<img src="https://img.shields.io/badge/JWT-Secure-fb015b?style=for-the-badge&logo=json-web-tokens&logoColor=white" alt="JWT">
</p>

> **JobApp** is a high-performance web application designed to bridge the gap between job seekers and recruiters. Built on a scalable MERN stack architecture, it provides a seamless interface for managing resumes, tracking applications, and facilitating the hiring pipeline.

---

## 🌟 What Does This Platform Do?

| Experience | Description |
| --- | --- |
| 🧑‍💻 **The Applicant** | Users can build profiles, upload resumes, and apply for positions with a single click. The dashboard provides real-time status updates on active applications. |
| 🏢 **The Employer** | Companies can post job openings, filter through candidate pools using keyword matching, and manage interview schedules from a centralized admin panel. |

---

## 🛠️ Technical Architecture

The platform is divided into two distinct layers to ensure maximum performance and security.

### The Full-Stack Pipeline

| Layer | Component | Technology Used |
| --- | --- | --- |
| **Frontend** | **User Interface:** Responsive SPA (Single Page Application) for smooth navigation. | React / Tailwind CSS |
| **Backend** | **RESTful API:** Handles business logic, authentication, and data routing. | Node.js / Express |
| **Database** | **Cloud Storage:** NoSQL database for flexible user profiles and job schemas. | MongoDB Atlas |
| **Auth** | **Security:** Role-based access control (RBAC) to separate Admin and User data. | JWT (JSON Web Tokens) |

**Key Features:**

> `🚀 Fast Search` | `📄 Resume Parsing` | `🔔 Real-time Notifications` | `📊 Hiring Analytics`

---

## 🚀 Getting Started (How to Run)

To set up the project locally for development, follow these steps:

### Prerequisites
* **Node.js** (v18 or higher)
* **npm** or **yarn**
* **MongoDB Connection String** (local or Atlas)

### Installation

1. **Clone the Repository:**
   ```bash
   git clone [https://github.com/omsikels/jobapp.git](https://github.com/omsikels/jobapp.git)
   cd jobapp

   Server Setup:

2. **Bash**
cd backend
npm install
# Create a .env file with your MONGO_URI and JWT_SECRET
npm start

3. **Client Setup:**
cd ../frontend
npm install
npm start

**📁 Advanced Info: Directory Structure**
<details>
<summary><b>🖱️ Click to expand the folder architecture</b></summary>

Below is the visual map of the system's codebase organization:
```
📦 jobapp
 ┣ 📂 client/              # 🎨 React Frontend (UI Components)
 ┃ ┣ 📂 src/components/    # 🧱 Reusable UI parts (Navbar, Buttons)
 ┃ ┗ 📂 src/pages/         # 📄 Main views (Home, Login, Dashboard)
 ┣ 📂 server/              # ⚙️ Node.js Backend (Logic)
 ┃ ┣ 📂 controllers/       # 🧠 Request handling logic
 ┃ ┣ 📂 models/            # 🗄️ Database Schemas (User, Job, Company)
 ┃ ┗ 📂 routes/            # 🛣️ API Endpoints definitions
 ┣ 📜 .env.example         # 🔑 Template for environment variables
 ┗ 📜 package.json         # 📋 Global project dependencies
```
