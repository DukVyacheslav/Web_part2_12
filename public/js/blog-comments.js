document.addEventListener('DOMContentLoaded', function() {
    function loadComments(postId) {
        var commentsContainer = document.getElementById('comments-' + postId);
        if (!commentsContainer) return;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'index.php?controller=blog&action=getComments&post_id=' + postId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                commentsContainer.innerHTML = '';
                if (response.comments && response.comments.length > 0) {
                    response.comments.forEach(function(comment) {
                        var commentDiv = document.createElement('div');
                        commentDiv.className = 'comment mb-2 p-2 border rounded';
                        commentDiv.innerHTML = '<strong>' + comment.user_fio + '</strong> <small class="text-muted">' + comment.created_at + '</small><br>' + comment.message;
                        commentsContainer.appendChild(commentDiv);
                    });
                } else {
                    commentsContainer.innerHTML = '<p class="text-muted">Комментариев пока нет.</p>';
                }
            }
        };
        xhr.send();
    }

    function showCommentModal(postId) {
        var modal = document.createElement('div');
        modal.className = 'modal fade show';
        modal.style.display = 'block';
        modal.tabIndex = -1;
        modal.role = 'dialog';
        modal.innerHTML = `
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить комментарий</h5>
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea id="commentMessage" class="form-control" rows="4" placeholder="Введите комментарий"></textarea>
                        <div id="commentError" class="text-danger mt-2" style="display:none;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submitComment" class="btn btn-primary">Отправить</button>
                        <button type="button" class="btn btn-secondary">Отмена</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        function closeModal() {
            modal.classList.remove('show');
            modal.style.display = 'none';
            document.body.removeChild(modal);
        }

        modal.querySelector('.close').addEventListener('click', closeModal);
        modal.querySelector('.btn-secondary').addEventListener('click', closeModal);

        modal.querySelector('#submitComment').addEventListener('click', function() {
            var message = modal.querySelector('#commentMessage').value.trim();
            var errorDiv = modal.querySelector('#commentError');
            if (!message) {
                errorDiv.textContent = 'Комментарий не может быть пустым';
                errorDiv.style.display = 'block';
                return;
            }
            errorDiv.style.display = 'none';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php?controller=blog&action=addComment', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            closeModal();
                            loadComments(postId);
                        } else if (response.error) {
                            errorDiv.textContent = response.error;
                            errorDiv.style.display = 'block';
                        }
                    } else {
                        errorDiv.textContent = 'Ошибка при отправке комментария';
                        errorDiv.style.display = 'block';
                    }
                }
            };
            xhr.send('post_id=' + encodeURIComponent(postId) + '&message=' + encodeURIComponent(message));
        });
    }

    document.querySelectorAll('.add-comment-btn').forEach(function(button) {
        var postId = button.getAttribute('data-post-id');
        loadComments(postId);
        button.addEventListener('click', function() {
            showCommentModal(postId);
        });
    });
});
