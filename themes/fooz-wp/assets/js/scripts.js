/**
 * Custom scripts for Fooz WP theme
 */

(function($) {
    'use strict';

    // DOM is ready
    $(document).ready(function() {
        initBookLoader();
    });

    /**
     * Initialize book loader functionality
     */
    function initBookLoader() {
        const $button = $('.fooz-load-books-btn');
        const $container = $('.fooz-books-container');
        let currentPage = 1;

        $button.on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true).text(foozWP.i18n.loading);

            getBooks(currentPage)
                .then(function(response) {
                    if (response.success && response.data.books.length > 0) {
                        displayBooks(response.data.books, $container);
                        currentPage++;

                        if (response.data.hasMore) {
                            $btn.prop('disabled', false).text(foozWP.i18n.loadBooks);
                        } else {
                            $btn.remove();
                        }
                    } else {
                        throw new Error('No books found');
                    }
                })
                .catch(function(error) {
                    console.error('Error loading books:', error);
                    $btn.text(foozWP.i18n.error).addClass('error');
                    setTimeout(() => {
                        $btn.text(foozWP.i18n.loadBooks).removeClass('error').prop('disabled', false);
                    }, 2000);
                });
        });
    }

    /**
     * Display books in the container
     */
    function displayBooks(books, $container) {
        if (!$container.find('ul').length) {
            $container.append('<ul class="genre-books-list"></ul>');
        }

        const $booksList = $container.find('ul');

        books.forEach(function(book) {
            // Create genre links
            const genreLinks = book.genre.map(genre =>
                `<a href="${genre.url}" class="genre-book-link">${genre.name}</a>`
            ).join(', ');

            const bookElement = $(`
                <li class="genre-book-item">
                    <div class="book-content">
                        <a href="${book.url}" class="book-link">
                            <h3 class="genre-book-title">${book.name}</h3>
                        </a>
                        <div class="book-meta">
                            <time class="book-date">${book.date}</time>
                            <div class="book-genres">${genreLinks}</div>
                        </div>
                        <div class="book-excerpt">${book.excerpt}</div>
                    </div>
                </li>
            `);
            $booksList.append(bookElement);
        });
    }

    /**
     * Fetch books from the server
     */
    function getBooks(page = 1) {
        return $.ajax({
            url: foozWP.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_books',
                nonce: foozWP.nonce,
                page: page
            }
        });
    }

    // Make getBooks function globally available
    window.foozWPGetBooks = getBooks;

})(jQuery);