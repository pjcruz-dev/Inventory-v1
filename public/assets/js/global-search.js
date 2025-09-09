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
        this.recentSearches = this.loadRecentSearches();
        this.maxRecentSearches = 5;
        
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
        
        // Global keyboard shortcuts
        document.addEventListener('keydown', this.handleGlobalKeydown.bind(this));
        
        // Add search input animations
        this.addInputAnimations();
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
        } else {
            this.showRecentSearches();
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
    
    handleGlobalKeydown(event) {
        // Ctrl+K or Cmd+K to focus search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            this.focusSearch();
        }
        
        // Escape to clear and blur search
        if (event.key === 'Escape' && document.activeElement === this.searchInput) {
            this.clearSearch();
        }
    }
    
    addInputAnimations() {
        // Add focus/blur animations
        this.searchInput.addEventListener('focus', () => {
            this.searchInput.parentElement.classList.add('search-focused');
        });
        
        this.searchInput.addEventListener('blur', () => {
            setTimeout(() => {
                if (!this.searchDropdown.matches(':hover')) {
                    this.searchInput.parentElement.classList.remove('search-focused');
                }
            }, 150);
        });
    }
    
    focusSearch() {
        this.searchInput.focus();
        this.searchInput.select();
    }
    
    clearSearch() {
        this.searchInput.value = '';
        this.hideDropdown();
        this.searchInput.blur();
    }
    
    loadRecentSearches() {
        try {
            const stored = localStorage.getItem('global_search_recent');
            return stored ? JSON.parse(stored) : [];
        } catch (error) {
            console.warn('Failed to load recent searches:', error);
            return [];
        }
    }
    
    saveRecentSearches() {
        try {
            localStorage.setItem('global_search_recent', JSON.stringify(this.recentSearches));
        } catch (error) {
            console.warn('Failed to save recent searches:', error);
        }
    }
    
    addToRecentSearches(query) {
        if (!query || query.length < this.minSearchLength) return;
        
        // Remove if already exists
        this.recentSearches = this.recentSearches.filter(search => search !== query);
        
        // Add to beginning
        this.recentSearches.unshift(query);
        
        // Limit to max recent searches
        this.recentSearches = this.recentSearches.slice(0, this.maxRecentSearches);
        
        this.saveRecentSearches();
    }
    
    showRecentSearches() {
        if (this.recentSearches.length === 0) {
            this.hideDropdown();
            return;
        }
        
        this.searchResults.innerHTML = '';
        
        // Create recent searches header
        const header = document.createElement('div');
        header.className = 'search-module-header';
        header.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted font-weight-bold">Recent Searches</small>
                <button class="btn btn-link btn-sm p-0 text-muted" id="clear-recent-searches">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        this.searchResults.appendChild(header);
        
        // Add recent searches
        this.recentSearches.forEach(query => {
            const recentItem = document.createElement('div');
            recentItem.className = 'search-result-item recent-search-item';
            recentItem.innerHTML = `
                <div class="search-result-link" data-query="${query}">
                    <div class="d-flex align-items-center">
                        <div class="search-result-icon me-3">
                            <i class="fas fa-history text-muted"></i>
                        </div>
                        <div class="search-result-content flex-grow-1">
                            <div class="search-result-title">${query}</div>
                        </div>
                        <div class="search-result-arrow">
                            <i class="fas fa-arrow-up-left text-muted"></i>
                        </div>
                    </div>
                </div>
            `;
            
            recentItem.addEventListener('click', () => {
                this.searchInput.value = query;
                this.performSearch(query);
            });
            
            this.searchResults.appendChild(recentItem);
        });
        
        // Add clear recent searches handler
        const clearBtn = document.getElementById('clear-recent-searches');
        if (clearBtn) {
            clearBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.clearRecentSearches();
            });
        }
        
        this.showDropdown();
    }
    
    clearRecentSearches() {
        this.recentSearches = [];
        this.saveRecentSearches();
        this.hideDropdown();
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
            
            // Add to recent searches if results found
            if (data.results && data.results.length > 0) {
                this.addToRecentSearches(query);
            }
            
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
        
        // Use the title and subtitle as they come from backend (already highlighted)
        const title = result.title || '';
        const subtitle = result.subtitle || '';
        const description = result.description || '';
        
        // Create badge HTML if badge exists
        const badgeHtml = result.badge ? 
            `<span class="badge bg-${result.badge_color || 'secondary'} ms-2">${result.badge}</span>` : '';
        
        resultDiv.innerHTML = `
            <a href="${result.url}" class="search-result-link">
                <div class="d-flex align-items-center">
                    <div class="search-result-icon me-3">
                        <i class="${result.icon}"></i>
                    </div>
                    <div class="search-result-content flex-grow-1">
                        <div class="search-result-title">
                            ${title}
                            ${badgeHtml}
                        </div>
                        <div class="search-result-subtitle">${subtitle}</div>
                        ${description ? `<div class="search-result-description">${description}</div>` : ''}
                    </div>
                    <div class="search-result-arrow">
                        <i class="fas fa-chevron-right"></i>
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
            this.addToRecentSearches(query);
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
                this.addToRecentSearches(this.searchInput.value.trim());
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