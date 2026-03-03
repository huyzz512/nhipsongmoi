document.addEventListener('DOMContentLoaded', function() {
    const dateElement = document.getElementById("current-date");
    if (dateElement) {
        const today = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'numeric', 
            day: 'numeric' 
        };
        let formattedDate = today.toLocaleDateString('vi-VN', options);
        
        formattedDate = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1);
        
        dateElement.innerHTML = formattedDate;
    }
});

// 2. XỬ LÝ DROPDOWN MENU TÀI KHOẢN (Avatar)
function toggleUserMenu() {
    const dropdown = document.getElementById("userDropdown");
    if (dropdown) {
        dropdown.classList.toggle("show");
    }
}

// 3. ĐÓNG MENU KHI CLICK RA NGOÀI VÙNG AVATAR
window.onclick = function(event) {
    // Nếu nơi click chuột không phải là .user-area hoặc các thành phần con của nó
    if (!event.target.closest('.user-area')) {
        let dropdowns = document.getElementsByClassName("dropdown-menu");
        for (let i = 0; i < dropdowns.length; i++) {
            let openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

// ==========================================
// 4. TÍNH NĂNG SCROLL TO TOP (LÊN ĐẦU TRANG)
// ==========================================
const scrollToTopBtn = document.getElementById("scrollToTopBtn");

if (scrollToTopBtn) {
    // Sự kiện lắng nghe khi người dùng cuộn chuột
    window.addEventListener("scroll", function() {
        // Nếu cuộn xuống quá 300px thì hiện nút, ngược lại thì ẩn
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.add("show");
        } else {
            scrollToTopBtn.classList.remove("show");
        }
    });

    // Sự kiện khi click vào nút
    scrollToTopBtn.addEventListener("click", function() {
        // Cuộn mượt mà (smooth) lên vị trí top: 0
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
}

// ==========================================
// 5. CHẾ ĐỘ TỐI (DARK MODE)
// ==========================================
const darkModeBtn = document.getElementById('darkModeBtn');
const body = document.body;

// Kiểm tra xem lần trước user có đang bật Dark Mode không (Lưu trong LocalStorage)
if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    if (darkModeBtn) darkModeBtn.innerHTML = '<i class="fas fa-sun" style="color:#ffeb3b;"></i>';
}

if (darkModeBtn) {
    darkModeBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
            darkModeBtn.innerHTML = '<i class="fas fa-sun" style="color:#ffeb3b;"></i>'; // Đổi icon thành mặt trời
        } else {
            localStorage.setItem('darkMode', 'disabled');
            darkModeBtn.innerHTML = '<i class="fas fa-moon"></i>'; // Đổi icon về mặt trăng
        }
    });
}

// ==========================================
// 6. XEM THÊM BÀI VIẾT (LOAD MORE AJAX)
// ==========================================
const loadMoreBtn = document.getElementById('loadMoreBtn');
const listNewsContainer = document.getElementById('latestNewsContainer');
let currentOffset = 20; // Bắt đầu từ bài thứ 21 (do đã hiện 20 bài trên web)

if (loadMoreBtn && listNewsContainer) {
    loadMoreBtn.addEventListener('click', function() {
        const originalText = loadMoreBtn.innerHTML;
        loadMoreBtn.innerHTML = 'Đang tải... <i class="fas fa-spinner fa-spin"></i>';
        loadMoreBtn.disabled = true;

        // Gọi ngầm lên Server lấy dữ liệu
        fetch(`index.php?controller=home&action=loadMore&offset=${currentOffset}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.html !== '') {
                        // Chèn thêm bài báo mới vào cuối danh sách
                        listNewsContainer.insertAdjacentHTML('beforeend', data.html);
                        currentOffset += 10; // Cập nhật mốc tải tiếp theo
                        
                        loadMoreBtn.innerHTML = originalText;
                        loadMoreBtn.disabled = false;
                    } else {
                        // Nếu hết bài trong Database
                        loadMoreBtn.innerHTML = 'Đã hiển thị toàn bộ tin tức';
                        loadMoreBtn.style.cursor = 'default';
                        loadMoreBtn.style.borderColor = '#ccc';
                        loadMoreBtn.style.color = '#ccc';
                    }
                }
            })
            .catch(error => {
                console.error('Lỗi tải tin tức:', error);
                loadMoreBtn.innerHTML = originalText;
                loadMoreBtn.disabled = false;
            });
    });
}