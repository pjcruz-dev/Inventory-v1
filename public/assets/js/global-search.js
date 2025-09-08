/**
 * Global Search Functionality
 * Provides real-time search across all modules with dropdown results
 */

class GlobalSearch {
    constructor() {
        this.searchInput = document.getElementById('globalSearchInput');
        this.searchDropdown = document.getElementById('globalSearchDropdown');
        this.searchResults = document.getElementById('searchResults');
        this.searchLoading = document.getElementById('searchLoading');
        this.searchNoResults = document.getElementById('searchNoResults');
        
        this.searchTimeout = null;
        this.minSearchLength = 2;
        this.searchDelay = 300; // milliseconds
        
        this.init();
    }
    
    init() {
        if (!this.searchInput) return;
        
        // Bind events
        this.searchInput.addEventListener('input', this.handleInput.bind(this));
        this.searchInput.addEventListener('focus', this.handleFocus.bind(this));
        this.searchInput.addEventListener('blur', this.handleBlur.bind(this));
        this.searchInput.addEventListener('keydown', this.handleKeydown.bind(this));
        
        // Close dropdown when clicking outside
        document.addEventListener('click', this.handleDocumentClick.bind(this));
    }
    
    handleInput(event) {
        const query = event.target.value.trim();
        
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
        
        if (query.length < this.minSearchLength) {
            this.hideDropdown();
            return;
        }
        
        // Debounce search
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, this.searchDelay);
    }
    
    handleFocus(event) {
        const query = event.target.value.trim();
        if (query.length >= this.minSearchLength) {
            this.showDropdown();
        }
    }
    
    handleBlur(event) {
        // Delay hiding to allow clicking on results
        setTimeout(() => {
            if (!this.searchDropdown.matches(':hover')) {
                this.hideDropdown();
            }
        }, 150);
    }
    
    handleKeydown(event) {
        if (event.key === 'Escape') {
            this.hideDropdown();
            this.searchInput.blur();
        }
        
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            this.navigateResults(event.key === 'ArrowDown' ? 'down' : 'up');
        }
        
        if (event.key === 'Enter') {
            event.preventDefault();
            this.selectActiveResult();
        }
    }
    
    handleDocumentClick(event) {
        if (!this.searchInput.contains(event.target) && !this.searchDropdown.contains(event.target)) {
            this.hideDropdown();
        }
    }
    
    async performSearch(query) {
        try {
            this.showLoading();
            this.showDropdown();
            
            const response = await fetch(`/global-search?query=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                throw new Error('Search request failed');
            }
            
            const data = await response.json();
            this.displayResults(data.results, query);
            
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Search failed. Please try again.');
        } finally {
            this.hideLoading();
        }
    }
    
    displayResults(results, query) {
        this.searchResults.innerHTML = '';
        
        if (results.length === 0) {
            this.showNoResults();
            return;
        }
        
        this.hideNoResults();
        
        // Group results by module
        const groupedResults = this.groupResultsByModule(results);
        
        Object.keys(groupedResults).forEach(module => {
            const moduleResults = groupedResults[module];
            
            // Create module header
            const moduleHeader = document.createElement('div');
            moduleHeader.className = 'search-module-header';
            moduleHeader.innerHTML = `
                <div class="px-3 py-2 bg-light border-bottom">
                    <small class="text-muted font-weight-bold">${module}</small>
                </div>
            `;
            this.searchResults.appendChild(moduleHeader);
            
            // Create results for this module
            moduleResults.forEach((result, index) => {
                const resultElement = this.createResultElement(result, query);
                this.searchResults.appendChild(resultElement);
            });
        });
    }
    
    groupResultsByModule(results) {
        return results.reduce((groups, result) => {
            const module = result.module || 'Other';
            if (!groups[module]) {
                groups[module] = [];
            }
            groups[module].push(result);
            return groups;
        }, {});
    }
    
    createResultElement(result, query) {
        const resultDiv = document.createElement('div');
        resultDiv.className = 'search-result-item';
        resultDiv.setAttribute('data-url', result.url);
        
        // Highlight search term in title and subtitle
        const highlightedTitle = this.highlightText(result.title, query);
        const highlightedSubtitle = this.highlightText(result.subtitle, query);
        
        resultDiv.innerHTML = `
            <a href="${result.url}" class="d-block p-3 text-decoration-none search-result-link">
                <div class="d-flex align-items-center">
                    <div class="search-result-icon me-3">
                        <i class="${result.icon} text-primary"></i>
                    </div>
                    <div class="search-result-content flex-grow-1">
                        <div class="search-result-title text-dark font-weight-bold">${highlightedTitle}</div>
                        <div class="search-result-subtitle text-muted small">${highlightedSubtitle}</div>
                    </div>
                    <div class="search-result-arrow">
                        <i class="fas fa-chevron-right text-muted small"></i>
                    </div>
                </div>
            </a>
        `;
        
        // Add click handler
        resultDiv.addEventListener('click', (event) => {
            if (event.target.tagName !== 'A') {
                event.preventDefault();
                window.location.href = result.url;
            }
            this.hideDropdown();
        });
        
        return resultDiv;
    }
    
    highlightText(text, query) {
        if (!text || !query) return text;
        
        const regex = new RegExp(`(${this.escapeRegExp(query)})`, 'gi');
        return text.replace(regex, '<mark class="bg-warning">$1</mark>');
    }
    
    escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    navigateResults(direction) {
        const results = this.searchResults.querySelectorAll('.search-result-item');
        if (results.length === 0) return;
        
        const currentActive = this.searchResults.querySelector('.search-result-item.active');
        let newActiveIndex = 0;
        
        if (currentActive) {
            const currentIndex = Array.from(results).indexOf(currentActive);
            currentActive.classList.remove('active');
            
            if (direction === 'down') {
                newActiveIndex = (currentIndex + 1) % results.length;
            } else {
                newActiveIndex = currentIndex === 0 ? results.length - 1 : currentIndex - 1;
            }
        }
        
        results[newActiveIndex].classList.add('active');
        results[newActiveIndex].scrollIntoView({ block: 'nearest' });
    }
    
    selectActiveResult() {
        const activeResult = this.searchResults.querySelector('.search-result-item.active');
        if (activeResult) {
            const url = activeResult.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        }
    }
    
    showDropdown() {
        this.searchDropdown.style.display = 'block';
        this.searchDropdown.classList.add('show');
    }
    
    hideDropdown() {
        this.searchDropdown.style.display = 'none';
        this.searchDropdown.classList.remove('show');
    }
    
    showLoading() {
        this.searchLoading.style.display = 'block';
        this.hideNoResults();
    }
    
    hideLoading() {
        this.searchLoading.style.display = 'none';
    }
    
    showNoResults() {
        this.searchNoResults.style.display = 'block';
    }
    
    hideNoResults() {
        this.searchNoResults.style.display = 'none';
    }
    
    showError(message) {
        this.searchResults.innerHTML = `
            <div class="p-3 text-center text-danger">
                <i class="fas fa-exclamation-triangle mb-2"></i>
                <p class="mb-0">${message}</p>
            </div>
        `;
        this.hideLoading();
        this.hideNoResults();
    }
}

// Initialize global search when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new GlobalSearch();
});

// Export for potential external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GlobalSearch;
}