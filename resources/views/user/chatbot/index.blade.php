@extends('user.layout')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <span style="font-size: 1.2rem;">🤖</span> Trợ lý ảo BeeFast
                </h4>
                <button id="clear-chat" class="btn btn-sm btn-light">
                    🗑️ Xóa lịch sử
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <!-- Chat container -->
            <div id="chat-box" class="chat-container">
                @if($messages->count() > 0)
                    @foreach($messages as $msg)
                        <div class="message-wrapper {{ $msg->type == 'user' ? 'user-message' : 'bot-message' }}">
                            <div class="message-bubble {{ $msg->type == 'user' ? 'user-bubble' : 'bot-bubble' }}">
                                @if($msg->type == 'bot')
                                    <div class="bot-avatar">
                                        🤖
                                    </div>
                                @endif

                                <div class="message-content">
                                    @if($msg->type == 'user')
                                        <div class="message-sender">Bạn</div>
                                    @else
                                        <div class="message-sender">BeeFast Bot</div>
                                    @endif
                                    <div class="message-text">{!! nl2br(e($msg->message)) !!}</div>
                                    <div class="message-time">
                                        {{ $msg->created_at->format('H:i') }}
                                    </div>
                                </div>

                                @if($msg->type == 'user')
                                    <div class="user-avatar">
                                        👤
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="welcome-message text-center py-5">
                        <div class="welcome-icon mb-3">
                            <span style="font-size: 3rem;">🤖</span>
                        </div>
                        <h5 class="text-muted">Chào mừng đến với trợ lý ảo BeeFast!</h5>
                        <p class="text-muted">Tôi có thể giúp bạn tìm sản phẩm, kiểm tra giá và tư vấn cấu hình.</p>
                        <div class="suggestions mt-3">
                            <button class="btn btn-outline-primary btn-sm quick-question" data-question="Xin chào">👋 Chào bot</button>
                            <button class="btn btn-outline-primary btn-sm quick-question" data-question="Danh mục sản phẩm">📁 Danh mục</button>
                            <button class="btn btn-outline-primary btn-sm quick-question" data-question="BeeFast Pro X1">💻 Sản phẩm</button>
                            <button class="btn btn-outline-primary btn-sm quick-question" data-question="help">❓ Trợ giúp</button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Input form -->
            <div class="chat-input-container p-3 border-top">
                <form id="chat-form" class="mb-2">
                    <div class="input-group">
                        <input type="text" id="message" class="form-control"
                               placeholder="Nhập câu hỏi của bạn... (VD: giá BeeFast Pro X1, cấu hình laptop gaming)"
                               autocomplete="off">
                        <button type="submit" class="btn btn-primary" id="send-btn">
                            📤 Gửi
                        </button>
                    </div>
                </form>

                <!-- Quick actions -->
                <div class="quick-actions">
                    <small class="text-muted me-2">Hỏi nhanh:</small>
                    <button class="btn btn-sm btn-outline-secondary quick-question" data-question="giá BeeFast Pro X1">💰 Giá sản phẩm</button>
                    <button class="btn btn-sm btn-outline-secondary quick-question" data-question="laptop gaming">🎮 Laptop gaming</button>
                    <button class="btn btn-sm btn-outline-secondary quick-question" data-question="còn hàng không">📦 Kho hàng</button>
                    <button class="btn btn-sm btn-outline-secondary quick-question" data-question="help">❓ Trợ giúp</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Chat container */
.chat-container {
    height: 500px;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
}

/* Message styles */
.message-wrapper {
    margin-bottom: 15px;
    display: flex;
}

.user-message {
    justify-content: flex-end;
}

.bot-message {
    justify-content: flex-start;
}

.message-bubble {
    max-width: 80%;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.user-bubble {
    flex-direction: row-reverse;
}

.bot-bubble {
    flex-direction: row;
}

.bot-avatar, .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 20px;
}

.bot-avatar {
    background: #007bff;
    color: white;
}

.user-avatar {
    background: #6c757d;
    color: white;
}

.message-content {
    background: white;
    padding: 12px 15px;
    border-radius: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    max-width: 100%;
    word-wrap: break-word;
}

.user-message .message-content {
    background: #007bff;
    color: white;
}

.message-sender {
    font-weight: bold;
    font-size: 0.85em;
    margin-bottom: 5px;
    color: #666;
}

.user-message .message-sender {
    color: rgba(255,255,255,0.9);
}

.message-text {
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.5;
}

.message-time {
    font-size: 0.75em;
    color: #999;
    margin-top: 5px;
    text-align: right;
}

.user-message .message-time {
    color: rgba(255,255,255,0.7);
}

/* Welcome message */
.welcome-message {
    opacity: 0.7;
}

/* Quick question buttons */
.quick-question {
    margin: 2px;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid #dee2e6;
    padding: 5px 10px;
    border-radius: 5px;
    background: white;
}

.quick-question:hover {
    background: #f8f9fa;
    border-color: #007bff;
    transform: translateY(-1px);
}

.quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    align-items: center;
}

/* Scrollbar */
#chat-box::-webkit-scrollbar {
    width: 8px;
}

#chat-box::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#chat-box::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

#chat-box::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Loading spinner */
.spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #007bff;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-rotate 0.75s linear infinite;
    vertical-align: middle;
    margin-right: 8px;
}

@keyframes spinner-rotate {
    to { transform: rotate(360deg); }
}

/* Card styles */
.card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

/* Form styles */
.input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 5px;
    overflow: hidden;
}

#message {
    border: 1px solid #ced4da;
    border-right: none;
}

#message:focus {
    box-shadow: none;
    border-color: #80bdff;
}

#send-btn {
    border: 1px solid #007bff;
}

#send-btn:hover {
    background: #0056b3;
    border-color: #0056b3;
}

#clear-chat {
    font-size: 0.875rem;
}

#clear-chat:hover {
    background: #f8f9fa;
}
</style>

<script>
// Sử dụng JavaScript thuần, không cần jQuery
document.addEventListener('DOMContentLoaded', function() {
    console.log('Chatbot loaded');

    // Các biến
    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message');
    const clearChatBtn = document.getElementById('clear-chat');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // Scroll xuống cuối
    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Thêm tin nhắn vào chat box
    function addMessageToChat(message, type, timestamp = null) {
        const wrapper = document.createElement('div');
        wrapper.className = `message-wrapper ${type}-message`;

        const bubble = document.createElement('div');
        bubble.className = `message-bubble ${type}-bubble`;

        const avatar = document.createElement('div');
        avatar.className = type === 'user' ? 'user-avatar' : 'bot-avatar';
        avatar.textContent = type === 'user' ? '👤' : '🤖';

        const content = document.createElement('div');
        content.className = 'message-content';

        const sender = document.createElement('div');
        sender.className = 'message-sender';
        sender.textContent = type === 'user' ? 'Bạn' : 'BeeFast Bot';

        const text = document.createElement('div');
        text.className = 'message-text';
        text.innerHTML = message.replace(/\n/g, '<br>');

        const time = document.createElement('div');
        time.className = 'message-time';
        time.textContent = timestamp || new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        content.appendChild(sender);
        content.appendChild(text);
        content.appendChild(time);

        if (type === 'bot') {
            bubble.appendChild(avatar);
            bubble.appendChild(content);
        } else {
            bubble.appendChild(content);
            bubble.appendChild(avatar);
        }

        wrapper.appendChild(bubble);
        chatBox.appendChild(wrapper);

        scrollToBottom();

        return wrapper;
    }

    // Hiển thị loading
    function showLoading() {
        const loadingHtml = `
            <div class="message-wrapper bot-message">
                <div class="message-bubble bot-bubble">
                    <div class="bot-avatar">🤖</div>
                    <div class="message-content">
                        <div class="message-sender">BeeFast Bot</div>
                        <div class="message-text">
                            <div class="spinner"></div>
                            Đang xử lý...
                        </div>
                    </div>
                </div>
            </div>
        `;

        chatBox.insertAdjacentHTML('beforeend', loadingHtml);
        scrollToBottom();

        return chatBox.lastElementChild;
    }

    // Gửi tin nhắn
    function sendChatMessage(message) {
        console.log('Sending:', message);

        // Hiển thị tin nhắn người dùng
        addMessageToChat(message, 'user');

        // Hiển thị loading
        const loadingElement = showLoading();

        // Gửi request
        fetch("{{ route('chatbot.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);

            // Xóa loading
            if (loadingElement && loadingElement.parentNode) {
                loadingElement.remove();
            }

            // Hiển thị phản hồi của bot
            if (data.success) {
                addMessageToChat(data.message, 'bot');
            } else {
                addMessageToChat('❌ Đã xảy ra lỗi. Vui lòng thử lại sau!', 'bot');
            }
        })
        .catch(error => {
            console.error('Error:', error);

            // Xóa loading
            if (loadingElement && loadingElement.parentNode) {
                loadingElement.remove();
            }

            // Hiển thị lỗi
            addMessageToChat('❌ Lỗi kết nối! Vui lòng thử lại sau.', 'bot');
        });
    }

    // Xử lý submit form
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const message = messageInput.value.trim();
        if (!message) {
            messageInput.focus();
            return;
        }

        sendChatMessage(message);
        messageInput.value = '';
        messageInput.focus();
    });

    // Xóa lịch sử chat
    clearChatBtn.addEventListener('click', function() {
        if (confirm('Bạn có chắc muốn xóa toàn bộ lịch sử chat?')) {
            fetch("{{ route('chatbot.clear') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Lỗi khi xóa lịch sử chat');
                }
            })
            .catch(error => {
                console.error('Clear error:', error);
                alert('Lỗi kết nối');
            });
        }
    });

    // Xử lý các nút câu hỏi nhanh
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quick-question')) {
            const question = e.target.getAttribute('data-question');
            if (question) {
                messageInput.value = question;
                chatForm.dispatchEvent(new Event('submit'));
            }
        }
    });

    // Enter để gửi
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Tự động focus vào input
    messageInput.focus();

    // Scroll xuống cuối khi load
    scrollToBottom();
});
</script>
@endsection
