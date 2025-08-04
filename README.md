# 📚 Laravel News Aggregator

A scalable Laravel 12 application that **aggregates articles** from multiple external news APIs (e.g., NewsAPI, NYTimes, The Guardian), stores them locally, and exposes **RESTful API endpoints** for filtered, paginated article access. News from external APIs are fetched automatically every 30 minutes.

Built with a focus on **clean architecture**, **SOLID principles**, and **DRY coding practices**.

---

## 🚀 Features

- ✅ Fetch articles from NewsAPI, NYTimes and The Guardian
- ✅ Scheduled background jobs to keep data updated
- ✅ Search and filter articles via REST API
- ✅ Extensible service-based architecture
- ✅ Uses Laravel Queues, HTTP client, and Scheduling
- ✅ Follows SOLID & DRY best practices

---

## 🏗️ Architecture Overview

```
app/
├── Console/Commands/FetchArticlesCommand.php   # Triggers article fetch job
├── Jobs/FetchArticlesJob.php                   # Fetches and stores articles
├── Services/                                   # API integrations
│   ├── Contracts/NewsSourceInterface.php
│   ├── NewsAPIService.php
│   └── NYTimesService.php
│   └── TheGuardianAPIService.php
├── Repositories/ArticleRepository.php          # Handles article persistence
├── Http/Controllers/Api/ArticleController.php  # API endpoints
├── Http/Requests/SearchArticlesRequest.php     # Validates filters
├── Http/Resources/ArticleResource.php          # Transforms response data
└── Models/Article.php                          # Article model
└── Filters/ArticleFilter.php                   # Filters
```

---

## 📦 Installation

```bash
git clone https://github.com/naseefameer03/news-aggregator.git
cd news-aggregator
composer install
cp .env.example .env
php artisan key:generate
```

---

## 🔧 Configuration

### 1. Database
Edit your `.env`:

```
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

### 2. External APIs

Add these to `.env`:

```
NEWSAPI_KEY=your_newsapi_key
NYTIMES_API_KEY=your_nytimes_key
GUARDIAN_API_KEY=your_guardian_api_key
```

## 🛠️ Migrate Database

```bash
php artisan migrate
```

---

## ⏱️ Schedule Article Fetching

To run the scheduler:

```bash
php artisan schedule:work
```

You can also trigger manually:

```bash
php artisan app:fetch-articles-command
```

Since jobs are processed using the database queue, also run:

```bash
php artisan queue:work
```

---
## 🔍 API Endpoints

| Method | URI           | Description                          |
|--------|---------------|--------------------------------------|
| GET    | /api/articles | Search & filter news articles        |

### 🔎 Query Parameters

- `title` - Search by title
- `source` - Filter by source
- `category` - Filter by category
- `author` - Filter by author
- `date` - Filter by published date

#### 📝 Example Request

Fetch articles from NYTimes in the "World" category by author "John Doe":

```
GET /api/articles?source=NYTimes&category=World&author=John%20Doe
```

## 📤 Example Response

```json
{
  "success": true,
  "message": "Articles retrieved successfully.",
  "data": [
    {
      "id": "9f81491a-758d-4d77-9cbe-1476ec4bafc7",
      "title": "Breaking News",
      "content": "Breaking news content",
      "source": "NYTimes",
      "category": "World",
      "author": "John Doe",
      "url": "https://example.com/9f81491a-758d-4d77-9cbe-1476ec4bafc7"
      "published_at": "2025-07-29T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 9,
    "total": 40
  }
}
```

---

## 📄 License

This project is open-source and available under the [MIT license](LICENSE).

---

## ✨ Contributing

Pull requests are welcome! Please ensure code quality and formatting follow Laravel conventions.