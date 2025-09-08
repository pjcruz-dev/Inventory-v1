/**
 * Modal Handler for Success and Cancel Operations
 * Uses SweetAlert2 for consistent modal experience across all modules
 */

class ModalHandler {
    /**
     * Show success modal for create operations
     * @param {string} itemName - Name of the item created (e.g., 'Asset', 'User')
     * @param {function} callback - Optional callback function to execute after confirmation
     */
    static showCreateSuccess(itemName = 'Item', callback = null) {
        Swal.fire({
            title: 'Success!',
            text: `${itemName} has been created successfully.`,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        }).then((result) => {
            if (callback && typeof callback === 'function') {
                callback(result);
            }
        });
    }

    /**
     * Show success modal for update operations
     * @param {string} itemName - Name of the item updated
     * @param {function} callback - Optional callback function
     */
    static showUpdateSuccess(itemName = 'Item', callback = null) {
        Swal.fire({
            title: 'Updated!',
            text: `${itemName} has been updated successfully.`,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        }).then((result) => {
            if (callback && typeof callback === 'function') {
                callback(result);
            }
        });
    }

    /**
     * Show confirmation modal for delete operations
     * @param {string} itemName - Name of the item to delete
     * @param {function} onConfirm - Function to execute when confirmed
     * @param {function} onCancel - Optional function to execute when cancelled
     */
    static showDeleteConfirmation(itemName = 'Item', onConfirm = null, onCancel = null) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete this ${itemName.toLowerCase()}. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (onConfirm && typeof onConfirm === 'function') {
                    onConfirm();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                if (onCancel && typeof onCancel === 'function') {
                    onCancel();
                } else {
                    this.showCancelMessage();
                }
            }
        });
    }

    /**
     * Show success modal after successful delete
     * @param {string} itemName - Name of the deleted item
     * @param {function} callback - Optional callback function
     */
    static showDeleteSuccess(itemName = 'Item', callback = null) {
        Swal.fire({
            title: 'Deleted!',
            text: `${itemName} has been deleted successfully.`,
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        }).then((result) => {
            if (callback && typeof callback === 'function') {
                callback(result);
            }
        });
    }

    /**
     * Show cancel message
     * @param {string} message - Custom cancel message
     */
    static showCancelMessage(message = 'Operation cancelled.') {
        Swal.fire({
            title: 'Cancelled',
            text: message,
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6c757d',
            timer: 2000,
            timerProgressBar: true
        });
    }

    /**
     * Show error modal
     * @param {string} message - Error message to display
     * @param {function} callback - Optional callback function
     */
    static showError(message = 'An error occurred. Please try again.', callback = null) {
        Swal.fire({
            title: 'Error!',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (callback && typeof callback === 'function') {
                callback(result);
            }
        });
    }

    /**
     * Show confirmation modal for form submission
     * @param {string} action - Action being performed (e.g., 'create', 'update')
     * @param {string} itemName - Name of the item
     * @param {function} onConfirm - Function to execute when confirmed
     * @param {function} onCancel - Optional function to execute when cancelled
     */
    static showFormConfirmation(action = 'submit', itemName = 'item', onConfirm = null, onCancel = null) {
        const actionText = action === 'create' ? 'create' : action === 'update' ? 'update' : 'submit';
        
        Swal.fire({
            title: 'Confirm Action',
            text: `Are you sure you want to ${actionText} this ${itemName.toLowerCase()}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionText}!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                if (onConfirm && typeof onConfirm === 'function') {
                    onConfirm();
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                if (onCancel && typeof onCancel === 'function') {
                    onCancel();
                } else {
                    this.showCancelMessage(`${itemName} ${actionText} cancelled.`);
                }
            }
        });
    }

    /**
     * Show loading modal
     * @param {string} message - Loading message
     */
    static showLoading(message = 'Processing...') {
        Swal.fire({
            title: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Close any open modal
     */
    static close() {
        Swal.close();
    }

    /**
     * Show form confirmation modal with AJAX submission
     * @param {string} title - Modal title
     * @param {string} message - Modal message
     * @param {HTMLFormElement} form - Form element to submit
     */
    static showFormConfirmModal(title, message, form) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submitFormWithAjax(form);
            }
        });
    }

    /**
     * Show delete confirmation modal with AJAX submission
     * @param {string} title - Modal title
     * @param {string} message - Modal message
     * @param {HTMLFormElement} form - Form element to submit
     */
    static showDeleteModal(title, message, form) {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submitFormWithAjax(form);
            }
        });
    }

    /**
     * Submit form with AJAX
     * @param {HTMLFormElement} form - Form element to submit
     */
    static submitFormWithAjax(form) {
        const formData = new FormData(form);
        const url = form.action;
        const method = form.method || 'POST';

        this.showLoading('Processing...');

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            this.close();
            if (data.success) {
                this.showCreateSuccess(data.message || 'Operation completed successfully', () => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                this.showError(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            this.close();
            this.showError('An error occurred while processing your request');
            console.error('Error:', error);
        });
    }
}

// Make ModalHandler globally available
window.ModalHandler = ModalHandler;