<footer>
        <div class="container footer-grid">
            <div class="ft-col">
                <h3>Công ty Cổ phần Truyền thông Nhịp Sống Mới</h3>
                <p> - Tổng biên tập: Phan Nhật Huy <br>
 - Giấy phép hoạt động báo điện tử Nhịp Sống Mới số 36/GP-BVHTTDL Hà Nội, ngày 10-1-2026<br>
 - Địa chỉ tòa soạn: Số 18, ngõ 36, Đường Khuất Duy Tiến, Phường Thanh Xuân, thành phố Hà Nội<br>
 - Văn phòng đại diện miền Nam: Số 51-53 Võ Văn Tần, phường Xuân Hòa, thành phố Hồ Chí Minh<br>
<br>
</p>
            </div>
            <div class="ft-col">
                <h3>Liên Hệ</h3>
                <p><i class="fas fa-phone"></i>Điện thoại: 088-86-63632.</p>
                <p><i class="fas fa-envelope"></i> Email: info@nhipsongmoi.com.vn</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; Bản quyền thuộc về Báo điện tử Nhịp Sống Mới. Cấm sao chép dưới mọi hình thức nếu không có sự chấp thuận bằng văn bản.</p>
        </div>
    </footer>
</html>

<button id="scrollToTopBtn" title="Lên đầu trang">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="public/js/main.js"></script>
</body>
</html>

<div id="ai-chatbox" class="chatbox-container">
    <div class="chatbox-header">
        <div><i class="fas fa-robot"></i> <strong>Trợ lý AI</strong></div>
        <button id="close-chat" title="Đóng"><i class="fas fa-times"></i></button>
    </div>
    
    <div class="chatbox-messages" id="chatbox-messages">
        <div class="msg bot-msg">Chào Huy! Tôi là trợ lý AI của tòa soạn Nhịp Sống Mới. Bạn muốn cập nhật tin tức hay tìm hiểu gì hãy bảo cho mình biết nhé...</div>
    </div>
    
    <div class="chatbox-input">
        <input type="text" id="chat-input" placeholder="Hỏi tôi bất cứ điều gì...">
        <button id="send-chat"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('ai-toggle-btn');
        const chatbox = document.getElementById('ai-chatbox');
        const closeBtn = document.getElementById('close-chat');
        const sendBtn = document.getElementById('send-chat');
        const inputField = document.getElementById('chat-input');
        const messagesContainer = document.getElementById('chatbox-messages');

        // Bật/Tắt Chatbox
        toggleBtn.addEventListener('click', () => chatbox.classList.add('show'));
        closeBtn.addEventListener('click', () => chatbox.classList.remove('show'));

       // Hàm xử lý gửi tin nhắn (ĐÃ NÂNG CẤP KẾT NỐI API)
        async function sendMessage() {
            const text = inputField.value.trim();
            if(text !== '') {
                // 1. In tin nhắn của User ra màn hình
                const userMsg = document.createElement('div');
                userMsg.className = 'msg user-msg';
                userMsg.textContent = text;
                messagesContainer.appendChild(userMsg);
                inputField.value = '';
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // 2. Hiện hiệu ứng chờ "Đang suy nghĩ..."
                const loadingMsg = document.createElement('div');
                loadingMsg.className = 'msg bot-msg';
                loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang suy nghĩ...';
                messagesContainer.appendChild(loadingMsg);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // 3. Gửi request xuống Backend PHP của chúng ta
                try {
                    const response = await fetch('index.php?controller=chat', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ message: text })
                    });
                    
                    const data = await response.json();
                    
                    // Xóa dòng "Đang suy nghĩ..."
                    messagesContainer.removeChild(loadingMsg);

                    // 4. In câu trả lời thật từ AI ra màn hình
                    const botMsg = document.createElement('div');
                    botMsg.className = 'msg bot-msg';
                    
                    // Chuyển đổi dấu xuống dòng của AI thành thẻ <br> của HTML
                    // và xử lý in đậm (**) thành thẻ <strong>
                    let formattedReply = data.reply.replace(/\n/g, '<br>');
                    formattedReply = formattedReply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    
                    botMsg.innerHTML = formattedReply;
                    messagesContainer.appendChild(botMsg);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;

                } catch (error) {
                    messagesContainer.removeChild(loadingMsg);
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'msg bot-msg';
                    errorMsg.innerHTML = '<strong style="color:red;">Lỗi kết nối máy chủ!</strong> Vui lòng thử lại sau.';
                    messagesContainer.appendChild(errorMsg);
                }
            }
        }

        // Bắt sự kiện Click nút Gửi hoặc Bấm Enter
        sendBtn.addEventListener('click', sendMessage);
        inputField.addEventListener('keypress', function(e) {
            if(e.key === 'Enter') sendMessage();
        });
    });
</script>