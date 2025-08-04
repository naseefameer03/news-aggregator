<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .news-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        .news-card:hover {
            transform: translateY(-5px);
        }
        .news-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .news-meta {
            font-size: 0.9rem;
            color: #888;
        }
        .search-box {
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 1px solid #ccc;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">Latest News Articles</h2>


        <form id="filterForm" class="row mb-4 g-3 align-items-end justify-content-center">
            <div class="col-md-3">
                <input type="text" id="titleInput" class="form-control search-box" placeholder="Search by title...">
            </div>
            <div class="col-md-2">
                <input type="text" id="authorInput" class="form-control search-box" placeholder="Author">
            </div>
            <div class="col-md-2">
                <input type="text" id="sourceInput" class="form-control search-box" placeholder="Source">
            </div>
            <div class="col-md-2">
                <input type="text" id="categoryInput" class="form-control search-box" placeholder="Category">
            </div>
            <div class="col-md-2">
                <input type="date" id="dateInput" class="form-control search-box" placeholder="Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="row" id="articlesContainer">
            <!-- Articles will be rendered here -->
        </div>

        <nav>
            <ul class="pagination mt-4" id="pagination">
                <!-- Pagination will be rendered here -->
            </ul>
        </nav>
    </div>

    <script>

        let currentPage = 1;
        let lastPage = 1;
        let currentFilters = {
            title: '',
            author: '',
            source: '',
            category: '',
            date: ''
        };

        function getFilterParams() {
            return {
                title: document.getElementById('titleInput').value.trim(),
                author: document.getElementById('authorInput').value.trim(),
                source: document.getElementById('sourceInput').value.trim(),
                category: document.getElementById('categoryInput').value.trim(),
                date: document.getElementById('dateInput').value
            };
        }

        async function fetchArticles(page = 1) {
            currentPage = page;
            const filters = getFilterParams();
            currentFilters = { ...filters };
            const params = new URLSearchParams();
            if (filters.title) params.append('title', filters.title);
            if (filters.author) params.append('author', filters.author);
            if (filters.source) params.append('source', filters.source);
            if (filters.category) params.append('category', filters.category);
            if (filters.date) params.append('date', filters.date);
            params.append('page', page);
            const res = await fetch(`/api/articles?${params.toString()}`);
            const response = await res.json();
            if (!response.success) {
                renderArticles([]);
                setupPagination({ current_page: 1, last_page: 1 });
                return;
            }
            renderArticles(response.data);
            setupPagination(response.meta);
        }

        function renderArticles(articles) {
            const container = document.getElementById('articlesContainer');
            container.innerHTML = '';

            if (!articles || articles.length === 0) {
                container.innerHTML = '<div class="col-12 text-center">No articles found.</div>';
                return;
            }

            articles.forEach(article => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                card.innerHTML = `
                    <div class="card news-card h-100">
                        <div class="card-body">
                            <div class="news-title">${article.title}</div>
                            <p class="news-meta">By ${article.author || 'Unknown'} | ${article.category || 'General'} | ${article.source || 'Unknown'}</p>
                            <p>${article.content?.slice(0, 120) || 'No description available...'}...</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function setupPagination(meta) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            currentPage = meta.current_page;
            lastPage = meta.last_page;

            for (let i = 1; i <= lastPage; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.addEventListener('click', (e) => {
                    e.preventDefault();
                    fetchArticles(currentQuery, i);
                });
                pagination.appendChild(li);
            }
        }

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetchArticles(1);
        });

        // Optional: allow instant filtering on input change (uncomment if desired)
        // ['titleInput','authorInput','sourceInput','categoryInput','dateInput'].forEach(id => {
        //     document.getElementById(id).addEventListener('input', () => fetchArticles(1));
        // });

        // Initial fetch
        fetchArticles();
    </script>
</body>
</html>
