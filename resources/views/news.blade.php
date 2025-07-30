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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">Latest News Articles</h2>

        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <input type="text" id="searchInput" class="form-control search-box"
                    placeholder="Search by title, author, category, or source...">
            </div>
        </div>

        <div class="row" id="articlesContainer">
            <!-- Articles will be rendered here -->
        </div>
    </div>

    <script>
        async function fetchArticles(query = '') {
            const res = await fetch(`/api/articles?q=${encodeURIComponent(query)}`);
            const data = await res.json();
            renderArticles(data);
        }

        function renderArticles(articles) {
            const container = document.getElementById('articlesContainer');
            container.innerHTML = '';

            if (articles.data.length === 0) {
                container.innerHTML = '<div class="col-12 text-center">No articles found.</div>';
                return;
            }

            articles.data.forEach(article => {
                const card = document.createElement('div');
                card.className = 'col-md-4 mb-4';
                card.innerHTML = `
                    <div class="card news-card h-100">
                        <div class="card-body">
                            <div class="news-title">${article.title}</div>
                            <p class="news-meta">By ${article.author || 'Unknown'} | ${article.category || 'General'} | ${article.source || 'Unknown'}</p>
                            <p>${article.description?.slice(0, 120) || 'No description available...'}...</p>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const query = e.target.value;
            fetchArticles(query);
        });

        fetchArticles();
    </script>
</body>

</html>
