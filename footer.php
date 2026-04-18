    <footer>
        <p>Lost & Found System</p>
        <small>Securely manage lost and found items across your community.</small>
    </footer>
</div>

<script>
// Theme toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;
    const themeIcon = themeToggle.querySelector('i');

    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);

    // Toggle theme on button click
    themeToggle.addEventListener('click', function() {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    function updateThemeIcon(theme) {
        themeIcon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
});

// Search functionality
function searchItems() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const cards = document.querySelectorAll('.card');
    const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
    let visibleCount = 0;
    let totalCount = cards.length;

    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const description = card.querySelector('.card-text').textContent.toLowerCase();
        const location = card.querySelector('p.text-muted').textContent.toLowerCase();
        const type = card.querySelector('.badge').textContent.toLowerCase();

        // Check text search
        const textMatch = searchTerm === '' ||
                         title.includes(searchTerm) ||
                         description.includes(searchTerm) ||
                         location.includes(searchTerm) ||
                         type.includes(searchTerm);

        // Check filter
        const filterMatch = activeFilter === 'all' ||
                           type.includes(activeFilter);

        const shouldShow = textMatch && filterMatch;

        card.style.display = shouldShow ? 'block' : 'none';
        if (shouldShow) visibleCount++;
    });

    // Update results count
    const resultsDiv = document.getElementById('search-results');
    if (searchTerm || activeFilter !== 'all') {
        if (visibleCount === 0) {
            resultsDiv.textContent = 'No items found matching your search.';
        } else {
            resultsDiv.textContent = `Showing ${visibleCount} of ${totalCount} items`;
        }
        resultsDiv.style.display = 'block';
    } else {
        resultsDiv.style.display = 'none';
    }

    // Show/hide clear button
    const clearBtn = document.getElementById('clear-search');
    if (searchTerm) {
        clearBtn.style.display = 'block';
    } else {
        clearBtn.style.display = 'none';
    }
}

// Clear search functionality
document.addEventListener('DOMContentLoaded', function() {
    // ... existing theme toggle code ...

    // Add clear search functionality
    const clearBtn = document.getElementById('clear-search');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            document.getElementById('search-input').value = '';
            searchItems();
        });
    }

    // Add filter button functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            // Trigger search
            searchItems();
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>