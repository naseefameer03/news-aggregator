<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/news-filters.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">Latest News Articles</h2>


        <form id="filterForm" class="row mb-4 g-3 align-items-end justify-content-center">
            <div class="col-lg-2 col-md-6 col-12 mb-2 mb-lg-0">
                <label for="titleInput" class="form-label mb-1">Title</label>
                <input type="text" id="titleInput" class="form-control search-box" placeholder="Search by title...">
            </div>
            <div class="col-lg-2 col-md-6 col-12 mb-2 mb-lg-0">
                <label for="authorInput" class="form-label mb-1">Author</label>
                <input type="text" id="authorInput" class="form-control search-box" placeholder="Author">
            </div>
            <div class="col-lg-2 col-md-6 col-12 mb-2 mb-lg-0">
                <label for="sourceInput" class="form-label mb-1">Source</label>
                <input type="text" id="sourceInput" class="form-control search-box" placeholder="Source">
            </div>
            <div class="col-lg-2 col-md-6 col-12 mb-2 mb-lg-0">
                <label for="categoryInput" class="form-label mb-1">Category</label>
                <input type="text" id="categoryInput" class="form-control search-box" placeholder="Category">
            </div>
            <div class="col-lg-2 col-md-6 col-12 mb-2 mb-lg-0">
                <label for="dateInput" class="form-label mb-1">Date</label>
                <input type="date" id="dateInput" class="form-control search-box" placeholder="Date">
            </div>
            <div class="col-lg-2 col-md-6 col-12 d-flex gap-2 mb-2 mb-lg-0">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <button type="button" id="resetFilters" class="btn btn-secondary flex-fill">Reset</button>
            </div>
        </form>

        <div class="row" id="articlesContainer">
            <!-- Articles will be rendered here -->
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <nav class="d-flex justify-content-center">
                    <ul class="pagination custom-pagination" id="pagination">
                        <!-- Pagination will be rendered here -->
                    </ul>
                </nav>
            </div>
        </div>
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
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" alt="No articles found" style="width:90px;opacity:0.7;">
                        <div class="mt-3 fs-5 fw-semibold text-secondary">No articles found</div>
                    </div>
                `;
                return;
            }

            articles.forEach(article => {
            const card = document.createElement('div');
            card.className = 'col-lg-4 col-md-6 col-12 mb-4';
            card.innerHTML = `
                <a href="${article.url}" target="_blank" rel="noopener" class="text-decoration-none text-reset">
                    <div class="card news-card h-100 hover-shadow">
                        <div class="card-body pt-3">
                            <div class="news-title" style="color:#23272b;">${article.title}</div>
                            <p class="news-meta">By ${article.author || 'Unknown'} | ${article.category || 'General'} | ${article.source || 'Unknown'}</p>
                            <p>${article.content?.slice(0, 120) || 'No description available...'}...</p>
                        </div>
                    </div>
                </a>
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
                    fetchArticles(i);
                });
                pagination.appendChild(li);
            }
        }

        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetchArticles(1);
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('titleInput').value = '';
            document.getElementById('authorInput').value = '';
            document.getElementById('sourceInput').value = '';
            document.getElementById('categoryInput').value = '';
            document.getElementById('dateInput').value = '';
            fetchArticles(1);
        });

        // Initial fetch
        fetchArticles();
    </script>
</body>
</html>
