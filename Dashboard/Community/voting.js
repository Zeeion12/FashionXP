document.addEventListener('DOMContentLoaded', function() {
    const votingSection = document.querySelector('.voting-section');
    if (!votingSection) return;

    const komunitas_id = votingSection.dataset.komunitasId;
    const likeBtn = votingSection.querySelector('.like-btn');
    const dislikeBtn = votingSection.querySelector('.dislike-btn');
    const likeBar = votingSection.querySelector('.like-bar');
    const dislikeBar = votingSection.querySelector('.dislike-bar');
    const likePercentage = votingSection.querySelector('.like-percentage');
    const dislikePercentage = votingSection.querySelector('.dislike-percentage');

    // Function to update UI
    function updateVoteUI(likes, dislikes, userVote = null) {
        likeBar.style.width = `${likes}%`;
        dislikeBar.style.width = `${dislikes}%`;
        likePercentage.textContent = `${likes}%`;
        dislikePercentage.textContent = `${dislikes}%`;
        
        // Update button states
        likeBtn.classList.toggle('active', userVote === 'like');
        dislikeBtn.classList.toggle('active', userVote === 'dislike');
    }

    // Function to load vote stats
    async function loadVoteStats() {
        try {
            const response = await fetch(`get_vote_stats.php?id=${komunitas_id}`);
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }

            updateVoteUI(data.likes, data.dislikes, data.user_vote);
        } catch (error) {
            console.error('Error loading vote stats:', error);
        }
    }

    // Function to handle votes
    async function handleVote(voteType) {
        try {
            const response = await fetch('handle_vote.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    komunitas_id: komunitas_id,
                    vote_type: voteType
                })
            });

            const data = await response.json();
            
            if (data.error) {
                if (data.error === 'Please login to vote') {
                    window.location.href = '../Authentication/sign-in.php';
                    return;
                }
                throw new Error(data.error);
            }

            updateVoteUI(data.likes, data.dislikes, data.user_vote);

        } catch (error) {
            console.error('Error:', error);
            alert('Failed to submit vote. Please try again.');
        }
    }

    // Add click event listeners
    likeBtn.addEventListener('click', () => handleVote('like'));
    dislikeBtn.addEventListener('click', () => handleVote('dislike'));

    // Load initial vote stats
    loadVoteStats();





    // Comment functionality
    const commentForm = document.querySelector('#comment-form');
    const commentInput = commentForm.querySelector('.comment-input');
    const commentList = document.querySelector('.comment-list');

    // Function to create comment HTML
    function createCommentElement(comment) {
        const commentDiv = document.createElement('div');
        commentDiv.className = 'comment';
        commentDiv.innerHTML = `
            <div class="comment-avatar">
                <img src="${comment.avatar}" alt="User avatar">
            </div>
            <div class="comment-content">
                <div class="comment-author">${comment.author}</div>
                <div class="comment-text">${comment.text}</div>
            </div>
        `;
        return commentDiv;
    }

    // Function to load comments
    async function loadComments() {
        try {
            const response = await fetch(`get_comments.php?id=${komunitas_id}`);
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }

            commentList.innerHTML = ''; // Clear existing comments
            data.comments.forEach(comment => {
                commentList.appendChild(createCommentElement(comment));
            });
        } catch (error) {
            console.error('Error loading comments:', error);
        }
    }

    // Handle comment submission
    commentForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const comment = commentInput.value.trim();
        if (!comment) return;

        try {
            const response = await fetch('handle_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    komunitas_id: komunitas_id,
                    comment: comment
                })
            });

            const data = await response.json();
            
            if (data.error) {
                if (data.error === 'Please login to comment') {
                    window.location.href = '../Authentication/sign-in.php';
                    return;
                }
                throw new Error(data.error);
            }

            // Add new comment to the top of the list
            const commentElement = createCommentElement(data.comment);
            commentList.insertBefore(commentElement, commentList.firstChild);
            
            // Clear the input
            commentInput.value = '';

        } catch (error) {
            console.error('Error:', error);
            alert('Failed to submit comment. Please try again.');
        }
    });

    // Load initial comments
    loadComments();
});