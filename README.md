# ✅ CMSC 129 Lab 3 — AI-Integrated Task Manager

**Built on top of Lab 2 (Laravel MVC CRUD Application)**

**Authors:** Janiola, A.M. | Verde, M.

---

## 📋 Overview

This is a Laravel-based task management web application enhanced with AI capabilities through a built-in chatbot assistant. Users can manage tasks and task lists through a standard UI, and also interact with the app using natural language via the integrated AI chat widget.

The AI assistant can answer questions about your tasks, perform full CRUD operations through chat commands, and maintain context across a conversation session.

---

## 🤖 AI Features

### Minimum: AI Chatbot for Inquiries

The chatbot can answer natural language questions about your tasks and task lists using live data from the database. It supports at least the following types of inquiries:

- "What tasks do I have?"
- "Show me all high-priority tasks"
- "How many completed tasks do I have?"
- "What's my oldest pending task?"
- "List tasks in the 'Work' list"

### Expanded: AI Assistant for CRUD Operations

The assistant goes beyond inquiry — it can perform full CRUD operations through natural language commands. Destructive operations (archive, force delete) require explicit confirmation before executing.

**Supported actions:**

| Action | Example Command |
|---|---|
| `create_task` | "Create a new task: Finish Lab 3 with high priority" |
| `update_task` | "Mark task #5 as completed" |
| `delete_task` (archive) | "Delete the task called Submit Report" |
| `confirm_archive` | "confirm archive" |
| `restore_task` | "Restore the archived task Submit Report" |
| `force_delete_task` | "Permanently delete task #3" |
| `confirm_force_delete` | "confirm force delete" |
| `query_tasks` | "Show me all pending tasks in the School list" |
| `list_tasks` | "List all my tasks" |
| `count_tasks` | "How many high-priority tasks do I have?" |
| `oldest_task` | "What's my oldest task?" |

### Intelligent Context Awareness

The assistant maintains conversation history (last 10 messages) and supports follow-up questions referencing previous context:

```
User: "What tasks do I have?"
AI:   "You have 5 tasks: [list]"

User: "Which ones are high priority?"
AI:   [Filters to high-priority tasks]

User: "Which ones are due this week?"
AI:   [Further filters by due date]
```

---

## 🧠 AI Service & Model Used

| Provider | Model | Role |
|---|---|---|
| **Google Gemini** | `gemini-2.5-flash` | Primary AI |
| **Groq** | `llama-3.3-70b-versatile` | Fallback AI |

The app uses a **primary-with-fallback** strategy: Gemini is tried first. If it fails (e.g., rate limit or timeout), it automatically switches to Groq. This ensures availability and avoids running out of free credits on a single provider.

---

## 🛠️ Tech Stack

- **Framework:** Laravel 13 (PHP 8.3+)
- **Frontend:** Blade templates, Tailwind CSS v4, Vite
- **Database:** SQLite (default)
- **AI APIs:** Google Gemini API, Groq API
- **AI Architecture:** `AIService` → `PromptService` → `FunctionCallService`

---

## ⚙️ Setup Instructions

### Prerequisites

- PHP 8.3+
- Composer
- Node.js (v16+) & npm

### 1. Clone the repository

```bash
git clone <your-repo-url>
cd CMSC129-Lab3-JaniolaAM_VerdeM
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Configure environment variables

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and fill in your API keys (see [Environment Variables](#-environment-variables) below).

### 4. Set up the database and seed dummy data

```bash
php artisan migrate
php artisan db:seed
```

This seeds **10–20 realistic sample tasks** across multiple task lists (e.g., Work, School, Personal) with varying priorities, statuses, and due dates.

### 5. Build frontend assets

```bash
npm run build
```

### 6. Start the development server

```bash
composer run dev
```

Or run just the Laravel server:

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## 🔑 Environment Variables

Copy `.env.example` to `.env` and fill in the following:

```env
# Google Gemini API (Primary AI)
# Get your key at: https://makersuite.google.com/app/apikey
GEMINI_API_KEY=your_gemini_api_key_here

```

> ⚠️ **Never commit your actual `.env` file to GitHub.** It is already in `.gitignore`. Only commit `.env.example`.

### Getting API Keys

- **Google Gemini (Free tier — recommended):** [Google AI Studio](https://makersuite.google.com/app/apikey) — 60 req/min on free tier
- **Groq (Free tier — fast):** [Groq Console](https://console.groq.com/) — generous free tier

---

## 💬 Example Queries to Try

Once the app is running, open the chat widget (bottom-right corner) and try:

### Inquiry (Read)
- "Show me all my tasks"
- "What tasks are in the Work list?"
- "List all high-priority tasks"
- "How many tasks do I have?"
- "What is my oldest pending task?"
- "Show completed tasks in School"
- "Which tasks are due today?"

### CRUD via Chat
- "Add a task called 'Buy groceries' with low priority in the Personal list"
- "Create a high-priority task: Finish Lab 3, due tomorrow, in School"
- "Mark task #2 as done"
- "Archive the task called Fix the bug"
- "Restore the archived task Submit Report"
- "Permanently delete task #4"

### Follow-up / Context
- "Show me all tasks" → "Which ones are high priority?" → "Now filter by pending only"
- "How many tasks are in Work?" → "What about completed ones?"

---

## 🔒 Security Notes

- All AI API calls go through the **Laravel backend** (`ChatController` → `AIService`). The frontend never calls AI APIs directly.
- API keys are stored in `.env` and never exposed to the browser.
- The AI interacts with the database **only through internal Laravel controllers** (`TaskAPIController`), not via raw SQL or direct DB access.

---

## 📁 Project Structure (AI-related files)

```
app/
├── Http/
│   └── Controllers/
│       ├── ChatController.php        # Handles /chat endpoint
│       ├── TaskAPIController.php     # Internal API for AI to call
│       ├── TaskController.php        # Standard CRUD controller
│       └── TaskListController.php
├── Services/
│   ├── AIService.php                 # Gemini + Groq API integration w/ fallback
│   ├── PromptService.php             # Builds system prompt with live task data
│   └── FunctionCallService.php       # Parses AI JSON output → executes actions
├── Models/
│   ├── Task.php                      # SoftDeletes (archive/restore)
│   └── TaskList.php
database/
└── seeders/
    ├── TaskListSeeder.php
    └── TaskSeeder.php
routes/
├── web.php                           # Includes POST /chat route
```

---

## 📸 Screenshots

> <img width="1919" height="910" alt="image" src="https://github.com/user-attachments/assets/b97bdd9f-4c58-41f4-ad35-179a9d9b74dc" />

> <img width="1919" height="900" alt="image" src="https://github.com/user-attachments/assets/82825580-a5ee-4b7e-835d-24ec805cff89" />



---

## 📚 References

- [Google Gemini API Documentation](https://ai.google.dev/docs)
- [Groq API Documentation](https://console.groq.com/docs)
- [Laravel Documentation](https://laravel.com/docs)
- [Prompt Engineering Guide](https://www.promptingguide.ai/)
